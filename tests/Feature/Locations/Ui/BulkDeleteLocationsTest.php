<?php

namespace Tests\Feature\Locations\Ui;

use App\Models\Asset;
use App\Models\Location;
use App\Models\User;
use Tests\TestCase;

class BulkDeleteLocationsTest extends TestCase
{
    public function test_shows_confirmation_when_at_least_one_location_is_deletable()
    {
        // One location has assets (not deletable), one is clean.
        $locationWithAssets = Location::factory()->create();
        Asset::factory()->for($locationWithAssets, 'location')->create();
        $cleanLocation = Location::factory()->create();

        $this->actingAs(User::factory()->superuser()->create())
            ->post(route('locations.bulkdelete.show'), [
                'ids' => [$locationWithAssets->id, $cleanLocation->id],
            ])
            ->assertStatus(200)
            ->assertSee($locationWithAssets->name)
            ->assertSee($cleanLocation->name);
    }

    public function test_redirects_to_index_with_error_when_no_selected_locations_are_deletable()
    {
        // Both locations have assets, so neither is deletable.
        $locationA = Location::factory()->create();
        Asset::factory()->for($locationA, 'location')->create();
        $locationB = Location::factory()->create();
        Asset::factory()->for($locationB, 'location')->create();

        $this->actingAs(User::factory()->superuser()->create())
            ->from(route('locations.index'))
            ->post(route('locations.bulkdelete.show'), [
                'ids' => [$locationA->id, $locationB->id],
            ])
            ->assertRedirect(route('locations.index'))
            ->assertSessionHas('error');
    }

    public function test_redirects_to_index_when_no_locations_selected()
    {
        $this->actingAs(User::factory()->superuser()->create())
            ->from(route('locations.index'))
            ->post(route('locations.bulkdelete.show'), [
                'ids' => null,
            ])
            ->assertRedirect(route('locations.index'))
            ->assertSessionHas('error');
    }
}
