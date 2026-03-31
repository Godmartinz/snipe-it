<?php

namespace Tests\Feature\History\Api;

use App\Models\Accessory;
use App\Models\Asset;
use App\Models\Component;
use App\Models\Consumable;
use App\Models\License;
use App\Models\Location;
use App\Models\Maintenance;
use App\Models\User;
use Tests\TestCase;

class IndexHistoryTest extends TestCase
{
    /** Assets */
    public function test_viewing_asset_history_index_requires_permission()
    {
        $this->actingAsForApi(User::factory()->create())
            ->getJson(route('api.assets.history', Asset::factory()->create()))
            ->assertForbidden();
    }

    public function test_viewing_asset_history_user_has_permission()
    {
        $this->actingAsForApi(User::factory()->viewAssetHistory()->create())
            ->getJson(route('api.assets.history', Asset::factory()->create()))
            ->assertOk();
    }

    public function test_viewing_asset_history_admin_has_permission()
    {
        $this->actingAsForApi(User::factory()->admin()->create())
            ->getJson(route('api.assets.history', Asset::factory()->create()))
            ->assertOk();
    }

    /** Users */
    public function test_viewing_user_history_index_requires_permission()
    {
        $this->actingAsForApi(User::factory()->create())
            ->getJson(route('api.users.history', User::factory()->create()))
            ->assertForbidden();
    }

    public function test_viewing_user_history_user_has_permission()
    {
        $this->actingAsForApi(User::factory()->viewUserHistory()->create())
            ->getJson(route('api.users.history', User::factory()->create()))
            ->assertOk();
    }

    public function test_viewing_user_history_admin_has_permission()
    {
        $this->actingAsForApi(User::factory()->admin()->create())
            ->getJson(route('api.users.history', User::factory()->create()))
            ->assertOk();
    }

    /** Locations */
    public function test_viewing_location_history_index_requires_permission()
    {
        $this->actingAsForApi(User::factory()->create())
            ->getJson(route('api.locations.history', Location::factory()->create()))
            ->assertForbidden();
    }

    public function test_viewing_location_history_user_has_permission()
    {
        $this->actingAsForApi(User::factory()->viewLocationHistory()->create())
            ->getJson(route('api.locations.history', Location::factory()->create()))
            ->assertOk();
    }

    public function test_viewing_location_history_admin_has_permission()
    {
        $this->actingAsForApi(User::factory()->admin()->create())
            ->getJson(route('api.locations.history', Location::factory()->create()))
            ->assertOk();
    }

    /** Accessories */
    public function test_viewing_accessory_history_index_requires_permission()
    {
        $this->actingAsForApi(User::factory()->create())
            ->getJson(route('api.accessories.history', Accessory::factory()->create()))
            ->assertForbidden();
    }

    public function test_viewing_accessory_history_user_has_permission()
    {
        $this->actingAsForApi(User::factory()->viewAccessoryHistory()->create())
            ->getJson(route('api.accessories.history', Accessory::factory()->create()))
            ->assertOk();
    }

    public function test_viewing_accessory_history_admin_has_permission()
    {
        $this->actingAsForApi(User::factory()->admin()->create())
            ->getJson(route('api.accessories.history', Accessory::factory()->create()))
            ->assertOk();
    }

    /** Licenses */
    public function test_viewing_license_history_index_requires_permission()
    {
        $this->actingAsForApi(User::factory()->create())
            ->getJson(route('api.licenses.history', License::factory()->create()))
            ->assertForbidden();
    }

    public function test_viewing_license_history_user_has_permission()
    {
        $this->actingAsForApi(User::factory()->viewLicenseHistory()->create())
            ->getJson(route('api.licenses.history', License::factory()->create()))
            ->assertOk();
    }

    public function test_viewing_license_history_admin_has_permission()
    {
        $this->actingAsForApi(User::factory()->admin()->create())
            ->getJson(route('api.licenses.history', License::factory()->create()))
            ->assertOk();
    }

    /** Components */
    public function test_viewing_component_history_index_requires_permission()
    {
        $this->actingAsForApi(User::factory()->create())
            ->getJson(route('api.components.history', Component::factory()->create()))
            ->assertForbidden();
    }

    public function test_viewing_component_history_user_has_permission()
    {
        $this->actingAsForApi(User::factory()->viewComponentHistory()->create())
            ->getJson(route('api.components.history', Component::factory()->create()))
            ->assertOk();
    }

    public function test_viewing_component_history_admin_has_permission()
    {
        $this->actingAsForApi(User::factory()->admin()->create())
            ->getJson(route('api.components.history', Component::factory()->create()))
            ->assertOk();
    }

    /** Consumables */
    public function test_viewing_consumable_history_index_requires_permission()
    {
        $this->actingAsForApi(User::factory()->create())
            ->getJson(route('api.consumables.history', Consumable::factory()->create()))
            ->assertForbidden();
    }

    public function test_viewing_consumable_history_user_has_permission()
    {
        $this->actingAsForApi(User::factory()->viewConsumableHistory()->create())
            ->getJson(route('api.consumables.history', Consumable::factory()->create()))
            ->assertOk();
    }

    public function test_viewing_consumable_history_admin_has_permission()
    {
        $this->actingAsForApi(User::factory()->admin()->create())
            ->getJson(route('api.consumables.history', Consumable::factory()->create()))
            ->assertOk();
    }

    /** Maintenances */
    public function test_viewing_maintenance_history_index_requires_permission()
    {
        $this->actingAsForApi(User::factory()->create())
            ->getJson(route('api.maintenances.history', Maintenance::factory()->create()))
            ->assertForbidden();
    }

    public function test_viewing_maintenance_history_user_has_permission()
    {
        $this->actingAsForApi(User::factory()->viewAssetHistory()->create())
            ->getJson(route('api.maintenances.history', Maintenance::factory()->create()))
            ->assertOk();
    }

    public function test_viewing_maintenance_history_admin_has_permission()
    {
        $this->actingAsForApi(User::factory()->admin()->create())
            ->getJson(route('api.maintenances.history', Maintenance::factory()->create()))
            ->assertOk();
    }
}
