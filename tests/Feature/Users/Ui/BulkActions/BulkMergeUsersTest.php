<?php

namespace Tests\Feature\Users\Ui\BulkActions;

use App\Models\Asset;
use App\Models\User;
use Tests\TestCase;

class BulkMergeUsersTest extends TestCase
{
    public function test_requires_delete_permission()
    {
        $target = User::factory()->create();
        $to_merge = User::factory()->create();

        $this->actingAs(User::factory()->editUsers()->create())
            ->post(route('users.merge.save'), [
                'ids_to_merge' => [$to_merge->id],
                'merge_into_id' => $target->id,
            ])
            ->assertForbidden();

        $this->assertNotSoftDeleted($to_merge);
    }

    public function test_non_admin_cannot_merge_admin_into_self()
    {
        $actor = User::factory()->deleteUsers()->create();
        $admin = User::factory()->admin()->create();

        $this->actingAs($actor)
            ->post(route('users.merge.save'), [
                'ids_to_merge' => [$admin->id],
                'merge_into_id' => $actor->id,
            ])
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('error');

        $this->assertNotSoftDeleted($admin);
    }

    public function test_non_admin_cannot_merge_superuser_into_self()
    {
        $actor = User::factory()->deleteUsers()->create();
        $superuser = User::factory()->superuser()->create();

        $this->actingAs($actor)
            ->post(route('users.merge.save'), [
                'ids_to_merge' => [$superuser->id],
                'merge_into_id' => $actor->id,
            ])
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('error');

        $this->assertNotSoftDeleted($superuser);
    }

    public function test_admin_cannot_merge_superuser_into_self()
    {
        $admin = User::factory()->admin()->create();
        $superuser = User::factory()->superuser()->create();

        $this->actingAs($admin)
            ->post(route('users.merge.save'), [
                'ids_to_merge' => [$superuser->id],
                'merge_into_id' => $admin->id,
            ])
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('error');

        $this->assertNotSoftDeleted($superuser);
    }

    public function test_assets_are_transferred_and_source_user_is_deleted_on_merge()
    {
        $admin = User::factory()->admin()->create();
        $source = User::factory()->create();
        $target = User::factory()->create();
        $asset = Asset::factory()->assignedToUser($source)->create();

        $this->actingAs($admin)
            ->post(route('users.merge.save'), [
                'ids_to_merge' => [$source->id],
                'merge_into_id' => $target->id,
            ])
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted($source);
        $this->assertEquals($target->id, $asset->fresh()->assigned_to);
    }

    public function test_merge_does_not_transfer_assets_when_source_is_protected()
    {
        $actor = User::factory()->deleteUsers()->create();
        $admin = User::factory()->admin()->create();
        $asset = Asset::factory()->assignedToUser($admin)->create();

        $this->actingAs($actor)
            ->post(route('users.merge.save'), [
                'ids_to_merge' => [$admin->id],
                'merge_into_id' => $actor->id,
            ])
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('error');

        $this->assertEquals($admin->id, $asset->fresh()->assigned_to);
    }
}
