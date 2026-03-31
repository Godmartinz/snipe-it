<?php

namespace Tests\Feature\Locations\Ui;

use App\Models\Location;
use App\Models\User;
use Tests\TestCase;

class ShowLocationTest extends TestCase
{
    public function test_page_renders()
    {
        $this->actingAs(User::factory()->superuser()->create())
            ->get(route('locations.show', Location::factory()->create()))
            ->assertOk();
    }

    public function test_denies_access_to_regular_user()
    {
        $this->actingAs(User::factory()->create())
            ->get(route('locations.show', Location::factory()->create()))
            ->assertStatus(403)
            ->assertForbidden();
    }

    public function test_denies_print_access_to_regular_user()
    {
        $this->actingAs(User::factory()->create())
            ->get(route('locations.print_all_assigned', Location::factory()->create()))
            ->assertStatus(403)
            ->assertForbidden();
    }

    public function test_page_renders_for_super_admin()
    {
        $this->actingAs(User::factory()->superuser()->create())
            ->get(route('locations.print_all_assigned', Location::factory()->create()))
            ->assertOk();
    }
}
