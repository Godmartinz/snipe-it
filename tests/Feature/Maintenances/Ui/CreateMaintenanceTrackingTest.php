<?php

namespace Tests\Feature\Maintenances\Ui;

use App\Models\Asset;
use App\Models\MaintenanceType;
use App\Models\User;
use Tests\TestCase;

class CreateMaintenanceTrackingTest extends TestCase
{
    public function test_checkout_snapshot_is_captured_when_asset_is_checked_out()
    {
        $actor = User::factory()->superuser()->create();
        $assignedUser = User::factory()->create();
        $asset = Asset::factory()->assignedToUser($assignedUser)->create();
        $type = MaintenanceType::factory()->create();

        $this->actingAs($actor)
            ->post(route('maintenances.store'), [
                'name' => 'Snapshot Test',
                'selected_assets' => [$asset->id],
                'maintenance_type_id' => $type->id,
                'start_date' => '2026-01-01',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('maintenances.index'));

        $this->assertDatabaseHas('maintenances', [
            'asset_id' => $asset->id,
            'checked_out_to_id' => $assignedUser->id,
            'checked_out_to_type' => User::class,
        ]);
    }

    public function test_checkout_snapshot_is_null_when_asset_is_not_checked_out()
    {
        $actor = User::factory()->superuser()->create();
        $asset = Asset::factory()->create();
        $type = MaintenanceType::factory()->create();

        $this->actingAs($actor)
            ->post(route('maintenances.store'), [
                'name' => 'No Checkout Test',
                'selected_assets' => [$asset->id],
                'maintenance_type_id' => $type->id,
                'start_date' => '2026-01-01',
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('maintenances', [
            'asset_id' => $asset->id,
            'checked_out_to_id' => null,
            'checked_out_to_type' => null,
        ]);
    }

    public function test_responsible_party_defaults_to_creating_user()
    {
        $actor = User::factory()->superuser()->create();
        $asset = Asset::factory()->create();
        $type = MaintenanceType::factory()->create();

        $this->actingAs($actor)
            ->post(route('maintenances.store'), [
                'name' => 'RP Default Test',
                'selected_assets' => [$asset->id],
                'maintenance_type_id' => $type->id,
                'start_date' => '2026-01-01',
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('maintenances', [
            'asset_id' => $asset->id,
            'responsible_party_id' => $actor->id,
        ]);
    }

    public function test_responsible_party_can_be_set_to_another_user()
    {
        $actor = User::factory()->superuser()->create();
        $technician = User::factory()->create();
        $asset = Asset::factory()->create();
        $type = MaintenanceType::factory()->create();

        $this->actingAs($actor)
            ->post(route('maintenances.store'), [
                'name' => 'RP Explicit Test',
                'selected_assets' => [$asset->id],
                'maintenance_type_id' => $type->id,
                'responsible_party_id' => $technician->id,
                'start_date' => '2026-01-01',
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('maintenances', [
            'asset_id' => $asset->id,
            'responsible_party_id' => $technician->id,
        ]);
    }

    public function test_maintenance_type_id_syncs_legacy_asset_maintenance_type()
    {
        $actor = User::factory()->superuser()->create();
        $asset = Asset::factory()->create();
        $type = MaintenanceType::factory()->create(['name' => 'Custom Calibration']);

        $this->actingAs($actor)
            ->post(route('maintenances.store'), [
                'name' => 'Type Sync Test',
                'selected_assets' => [$asset->id],
                'maintenance_type_id' => $type->id,
                'start_date' => '2026-01-01',
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('maintenances', [
            'asset_id' => $asset->id,
            'maintenance_type_id' => $type->id,
            'asset_maintenance_type' => 'Custom Calibration',
        ]);
    }
}
