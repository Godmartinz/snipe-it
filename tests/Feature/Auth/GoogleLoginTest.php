<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GoogleLoginTest extends TestCase
{
    #[Test]
    public function successful_google_callback_updates_the_last_login_timestamp(): void
    {

        $user = User::factory()->create([
            'username' => 'me@example.org',
            'last_login' => null,
        ]);

        $socialUser = Mockery::mock(SocialiteUser::class);
        $socialUser->shouldReceive('getEmail')->andReturn('me@example.org');
        $socialUser->avatar = 'https://lh3.googleusercontent.com/a/sample-avatar';

        $driver = Mockery::mock();
        $driver->shouldReceive('user')->andReturn($socialUser);
        Socialite::shouldReceive('driver')->with('google')->andReturn($driver);

        $this->get(route('google.callback'))->assertRedirect(route('home'));

        $this->assertNotNull($user->fresh()->last_login, 'last_login must be stamped after a successful Google callback.');
        $this->assertTrue(Auth::check(), 'User must be authenticated after a successful Google callback.');
    }
}
