<?php

namespace Tests\Feature\Uploads;

use App\Models\Manufacturer;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

/**
 * Regression coverage for the Storage write/delete failure ordering bug
 * Christopher Finks reported as Issue 4.
 *
 * Pre-fix flow inside ImageUploadRequest::handleImages:
 *   Storage::put(new file)   // return value discarded
 *   deleteExistingImage()    // unconditional
 *   $item->image = $new;     // unconditional
 *
 * If put returned false (silent-fail mode on the default local disk), the
 * old file was destroyed and the model row ended up referencing a file
 * that never landed on disk. The fix captures the put return, only
 * proceeds to delete + reassign when the write succeeded, and mirrors the
 * same check inside deleteExistingImage so a failed delete does not null
 * out the model reference either.
 *
 * We exercise the fix through the manufacturers update endpoint because it
 * has a straightforward image field and the fewest confounding variables
 * of the many handleImages callers, but the fix applies to every model
 * whose controller ultimately routes through ImageUploadRequest.
 */
class ImageUploadRequestFailurePathsTest extends TestCase
{
    public function test_failed_new_image_write_preserves_existing_image_reference(): void
    {
        Storage::fake('public');
        $publicDisk = Storage::disk('public');

        // Seed a real "existing image" file so the test can assert it still
        // exists on disk after the failed write.
        $publicDisk->put('manufacturers/manufacturer-image-pre-existing.png', 'original bytes');

        $manufacturer = Manufacturer::factory()->create([
            'image' => 'manufacturer-image-pre-existing.png',
        ]);

        // Proxy the public disk so put() returns false (simulating a write
        // failure that Storage does not throw on) while every other call
        // still passes through to the real fake disk.
        $proxy = Mockery::mock($publicDisk);
        $proxy->shouldReceive('put')->andReturn(false);
        $proxy->shouldReceive('exists')->andReturnUsing(fn (...$a) => $publicDisk->exists(...$a));
        $proxy->shouldReceive('delete')->andReturnUsing(fn (...$a) => $publicDisk->delete(...$a));
        $proxy->shouldReceive('makeDirectory')->andReturnUsing(fn (...$a) => $publicDisk->makeDirectory(...$a));
        Storage::shouldReceive('disk')->with('public')->andReturn($proxy);

        $this->actingAs(User::factory()->superuser()->create())
            ->put(route('manufacturers.update', $manufacturer), [
                'name' => $manufacturer->name,
                'image' => UploadedFile::fake()->image('replacement.png'),
            ])
            ->assertStatus(302)
            ->assertSessionHasNoErrors();

        $manufacturer->refresh();
        $this->assertEquals(
            'manufacturer-image-pre-existing.png',
            $manufacturer->image,
            'A failed new-image write must not clear or replace the existing image reference on the model.',
        );
        $this->assertTrue(
            $publicDisk->exists('manufacturers/manufacturer-image-pre-existing.png'),
            'A failed new-image write must not delete the existing image from disk.',
        );
    }

    public function test_failed_delete_preserves_model_reference(): void
    {
        // Directly exercises ImageUploadRequest::deleteExistingImage in
        // isolation because the ManufacturersController path nulls the
        // model reference itself before handing off to handleImages (see
        // ManufacturersController::update line 153). The controller-level
        // pre-null defeats the deleteExistingImage guard for that specific
        // caller. Every other caller (ProfileController::update for the
        // user avatar, and any future callsite that relies on
        // deleteExistingImage to null the field) benefits from the guard.
        Storage::fake('public');
        $publicDisk = Storage::disk('public');
        $publicDisk->put('manufacturers/manufacturer-image-still-there.png', 'still here');

        $manufacturer = Manufacturer::factory()->create([
            'image' => 'manufacturer-image-still-there.png',
        ]);

        $proxy = Mockery::mock($publicDisk);
        $proxy->shouldReceive('delete')->andReturn(false);
        $proxy->shouldReceive('exists')->andReturnUsing(fn (...$a) => $publicDisk->exists(...$a));
        $proxy->shouldReceive('put')->andReturnUsing(fn (...$a) => $publicDisk->put(...$a));
        $proxy->shouldReceive('makeDirectory')->andReturnUsing(fn (...$a) => $publicDisk->makeDirectory(...$a));
        Storage::shouldReceive('disk')->with('public')->andReturn($proxy);

        $request = app(\App\Http\Requests\ImageUploadRequest::class);
        $result = $request->deleteExistingImage($manufacturer, 'manufacturers', 'image');

        $this->assertEquals(
            'manufacturer-image-still-there.png',
            $result->image,
            'A failed delete must not null the model image reference - the file may still be on disk and orphaning it is worse than leaving the reference intact.',
        );
    }

    public function test_successful_write_still_replaces_existing_image(): void
    {
        // Sanity: the fix must not regress the happy path. Successful writes
        // continue to delete the old file and update the model reference.
        Storage::fake('public');
        $publicDisk = Storage::disk('public');
        $publicDisk->put('manufacturers/manufacturer-image-old.png', 'old bytes');

        $manufacturer = Manufacturer::factory()->create([
            'image' => 'manufacturer-image-old.png',
        ]);

        $this->actingAs(User::factory()->superuser()->create())
            ->put(route('manufacturers.update', $manufacturer), [
                'name' => $manufacturer->name,
                'image' => UploadedFile::fake()->image('new.png'),
            ])
            ->assertStatus(302)
            ->assertSessionHasNoErrors();

        $manufacturer->refresh();
        $this->assertNotEquals('manufacturer-image-old.png', $manufacturer->image);
        $this->assertNotNull($manufacturer->image);
        $this->assertStringContainsString('Manufacturer-image', $manufacturer->image);
        $this->assertFalse(
            $publicDisk->exists('manufacturers/manufacturer-image-old.png'),
            'On a successful new write, the old image file is deleted from disk.',
        );
        $this->assertTrue(
            $publicDisk->exists('manufacturers/'.$manufacturer->image),
            'On a successful new write, the new file lands at the expected path.',
        );
    }
}
