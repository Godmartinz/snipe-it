<?php

namespace Tests\Feature\Assets\Ui;

use App\Models\Asset;
use App\Models\User;
use Tests\TestCase;

class AuditAssetTest extends TestCase
{
    public function test_permission_required_to_view_audit_create_page()
    {
        $this->actingAs(User::factory()->create())
            ->get(route('asset.audit.create', Asset::factory()->create()))
            ->assertForbidden();
    }

    public function test_page_can_be_accessed(): void
    {
        $this->actingAs(User::factory()->auditAssets()->create())
            ->get(route('asset.audit.create', Asset::factory()->create()))
            ->assertStatus(200);
    }

    public function test_permission_required_to_audit_asset()
    {
        $this->actingAs(User::factory()->create())
            ->post(route('asset.audit.store', Asset::factory()->create()))
            ->assertForbidden();
    }

    public function test_asset_audit_post_is_redirected_to_asset_index_if_redirect_selection_is_index()
    {
        $asset = Asset::factory()->create();

        $response = $this->actingAs(User::factory()->viewAssets()->editAssets()->auditAssets()->create())
            ->from(route('asset.audit.create', $asset))
            ->post(route('asset.audit.store', $asset),
                [
                    'redirect_option' => 'index',
                ])
            ->assertStatus(302)
            ->assertRedirect(route('hardware.index'));
        $this->followRedirects($response)->assertSee('success');

        $this->assertHasTheseActionLogs($asset, ['create', 'audit']);
    }

    public function test_asset_audit_post_is_redirected_to_asset_page_if_redirect_selection_is_asset()
    {
        $asset = Asset::factory()->create();

        $response = $this->actingAs(User::factory()->viewAssets()->editAssets()->auditAssets()->create())
            ->from(route('asset.audit.create', $asset))
            ->post(route('asset.audit.store', $asset),
                [
                    'redirect_option' => 'item',
                ])
            ->assertStatus(302)
            ->assertRedirect(route('hardware.show', $asset));
        $this->followRedirects($response)->assertSee('success');
        $this->assertHasTheseActionLogs($asset, ['create', 'audit']); // WAT.
    }

    public function test_asset_audit_post_is_redirected_to_audit_due_page_if_redirect_selection_is_list()
    {
        $asset = Asset::factory()->create();

        $response = $this->actingAs(User::factory()->viewAssets()->editAssets()->auditAssets()->create())
            ->from(route('asset.audit.create', $asset))
            ->post(route('asset.audit.store', $asset),
                [
                    'redirect_option' => 'other_redirect',
                ])
            ->assertStatus(302)
            ->assertRedirect(route('assets.audit.due'));
        $this->followRedirects($response)->assertSee('success');
        $this->assertHasTheseActionLogs($asset, ['create', 'audit']);
    }
}
