<?php

namespace Tests\Feature\Users\Ui;

use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    public function test_permission_required_to_create_user()
    {
        $this->actingAs(User::factory()->create())
            ->get(route('users.create'))
            ->assertForbidden();
    }

    public function test_page_renders()
    {
        $this->actingAs(User::factory()->createUsers()->create())
            ->get(route('users.create'))
            ->assertOk();

    }

    public function test_can_create_user()
    {
        Notification::fake();

        $response = $this->actingAs(User::factory()->createUsers()->viewUsers()->create())
            ->from(route('users.index'))
            ->post(route('users.store'), [
                'first_name' => 'Test First Name',
                'last_name' => 'Test Last Name',
                'username' => 'testuser',
                'password' => 'testpassword1235!!',
                'password_confirmation' => 'testpassword1235!!',
                'activated' => '1',
                'email' => 'foo@example.org',
                'notes' => 'Test Note',
            ])
            ->assertSessionHasNoErrors()
            ->assertStatus(302)
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'first_name' => 'Test First Name',
            'last_name' => 'Test Last Name',
            'username' => 'testuser',
            'activated' => '1',
            'email' => 'foo@example.org',
            'notes' => 'Test Note',

        ]);
        Notification::assertNothingSent();
        $this->followRedirects($response)->assertSee('Success');

    }

    public function test_can_create_and_notify_user()
    {

        Notification::fake();

        $response = $this->actingAs(User::factory()->createUsers()->viewUsers()->create())
            ->from(route('users.index'))
            ->post(route('users.store'), [
                'first_name' => 'Test First Name',
                'last_name' => 'Test Last Name',
                'username' => 'testuser',
                'password' => 'testpassword1235!!',
                'password_confirmation' => 'testpassword1235!!',
                'send_welcome' => '1',
                'activated' => '1',
                'email' => 'foo@example.org',
                'notes' => 'Test Note',
            ])
            ->assertSessionHasNoErrors()
            ->assertStatus(302)
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'first_name' => 'Test First Name',
            'last_name' => 'Test Last Name',
            'username' => 'testuser',
            'activated' => '1',
            'email' => 'foo@example.org',
            'notes' => 'Test Note',
        ]);

        $user = User::where('username', 'testuser')->first();
        Notification::assertSentTo($user, WelcomeNotification::class);
        $this->followRedirects($response)->assertSee('Success');

    }
}
