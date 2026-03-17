<?php

namespace Tests\Feature\Assets\Api;

use App\Models\Asset;
use App\Models\User;
use Tests\TestCase;

class AuditAssetTest extends TestCase
{
    public function test_that_a_non_existent_asset_id_returns_error()
    {
        $this->actingAsForApi(User::factory()->auditAssets()->create())
            ->postJson(route('api.asset.audit', 123456789))
            ->assertStatusMessageIs('error');
    }

    public function test_requires_permission_to_audit_asset()
    {
        $asset = Asset::factory()->create();
        $this->actingAsForApi(User::factory()->create())
            ->postJson(route('api.asset.audit', $asset))
            ->assertForbidden();
    }

    public function test_legacy_asset_audit_is_saved()
    {
        $asset = Asset::factory()->create();
        $this->actingAsForApi(User::factory()->auditAssets()->create())
            ->postJson(route('api.asset.audit.legacy'), [
                'asset_tag' => $asset->asset_tag,
                'note' => 'test',
            ])
            ->assertStatusMessageIs('success')
            ->assertJson(
                [
                    'messages' => trans('admin/hardware/message.audit.success'),
                    'payload' => [
                        'id' => $asset->id,
                        'asset_tag' => $asset->asset_tag,
                        'note' => 'test',
                    ],
                ])
            ->assertStatus(200);

    }

    public function test_asset_audit_is_saved()
    {
        $asset = Asset::factory()->create();
        $this->actingAsForApi(User::factory()->auditAssets()->create())
            ->postJson(route('api.asset.audit', $asset), [
                'note' => 'test',
            ])
            ->assertStatusMessageIs('success')
            ->assertJson(
                [
                    'messages' => trans('admin/hardware/message.audit.success'),
                    'payload' => [
                        'id' => $asset->id,
                        'asset_tag' => $asset->asset_tag,
                        'note' => 'test',
                    ],
                ])
            ->assertStatus(200);
        $this->assertHasTheseActionLogs($asset, ['create', 'audit']);
    }
}
