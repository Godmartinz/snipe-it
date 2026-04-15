<?php

namespace Tests\Feature\Assets\Api;

use App\Models\Asset;
use App\Models\User;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('auditing')]
class AuditAssetTest extends TestCase
{
    public function test_permission_required_to_bulk_audit_assets()
    {
        $this->actingAsForApi(User::factory()->create())
            ->postJson(route('api.asset.audit', Asset::factory()->create()))
            ->assertForbidden();
    }

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
        $future = now()->addMonths(5)->toDateString();

        $this->actingAsForApi(User::factory()->auditAssets()->create())
            ->postJson(route('api.asset.audit.legacy'), [
                'asset_tag' => $asset->asset_tag,
                'next_audit_date' => $future,
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

        $asset->refresh();
        $this->assertEquals($future, $asset->next_audit_date);
    }

    public function test_asset_audit_is_saved()
    {
        $asset = Asset::factory()->create(['next_audit_date' => now()->subMonth()->toDateString()]);
        $now = now();
        $future = now()->addMonths(3)->toDateString();

        $this->actingAsForApi(User::factory()->auditAssets()->create())
            ->postJson(route('api.asset.audit', $asset), [
                'next_audit_date' => $future,
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

        $asset->refresh();
        $this->assertEquals($now, $asset->last_audit_date);
        $this->assertEquals($future, $asset->next_audit_date);
    }

    /**
     * @link https://github.com/grokability/snipe-it/issues/18495
     */
    public function test_audit_does_not_set_next_audit_date_if_given_null()
    {
        $this->settings->setAuditInterval(null);

        $asset = Asset::factory()->create(['next_audit_date' => null]);

        $this->actingAsForApi(User::factory()->auditAssets()->create())
            ->postJson(route('api.asset.audit', $asset), [
                'asset_tag' => $asset->asset_tag,
                // this is the important part
                'next_audit_date' => null,
                'note' => null,
            ])
            ->assertStatusMessageIs('success')
            ->assertStatus(200);

        $asset->refresh();
        $this->assertNull($asset->next_audit_date);
    }
}
