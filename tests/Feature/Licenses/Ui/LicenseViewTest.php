<?php

namespace Tests\Feature\Licenses\Ui;

use App\Models\Depreciation;
use App\Models\License;
use App\Models\User;
use Tests\TestCase;

class LicenseViewTest extends TestCase
{
    public function test_permission_required_to_view_license()
    {
        $license = License::factory()->create();
        $this->actingAs(User::factory()->create())
            ->get(route('licenses.show', $license))
            ->assertForbidden();
    }

    public function test_page_renders()
    {
        $this->actingAs(User::factory()->superuser()->create())
            ->get(route('licenses.show', License::factory()->create()->id))
            ->assertOk();
    }

    public function test_license_with_purchase_date_depreciates_correctly()
    {
        $depreciation = Depreciation::factory()->create(['months' => 12]);
        $license = License::factory()->create(['depreciation_id' => $depreciation->id, 'purchase_date' => '2020-01-01']);
        $this->actingAs(User::factory()->superuser()->create())
            ->get(route('licenses.show', $license))
            ->assertOk()
            ->assertSee([
                '2021-01-01',
            ], false);
    }
}
