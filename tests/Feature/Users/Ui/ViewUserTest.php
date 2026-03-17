<?php

namespace Tests\Feature\Users\Ui;

use App\Models\Company;
use App\Models\User;
use Tests\TestCase;

class ViewUserTest extends TestCase
{
    public function test_requires_permission_to_view_user()
    {
        $this->actingAs(User::factory()->create())
            ->get(route('users.show', User::factory()->create()))
            ->assertStatus(403);
    }

    public function test_can_view_user()
    {
        $actor = User::factory()->viewUsers()->create();

        $this->actingAs($actor)
            ->get(route('users.show', User::factory()->create()))
            ->assertOk()
            ->assertStatus(200);
    }

    public function test_cannot_view_user_from_another_company()
    {
        $this->settings->enableMultipleFullCompanySupport();

        [$companyA, $companyB] = Company::factory()->count(2)->create();

        $actor = User::factory()->for($companyA)->viewUsers()->create();
        $user = User::factory()->for($companyB)->create();

        $this->actingAs($actor)
            ->get(route('users.show', $user))
            ->assertStatus(302);
    }
}
