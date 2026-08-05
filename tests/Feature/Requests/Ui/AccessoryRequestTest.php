<?php

namespace Tests\Feature\Requests\Ui;

use App\Models\Accessory;
use App\Models\CheckoutRequest;
use App\Models\User;
use Tests\TestCase;

class AccessoryRequestTest extends TestCase
{
    public function test_requestable_index_lists_requestable_accessories(): void
    {
        $accessory = Accessory::factory()->create(['requestable' => true]);
        Accessory::factory()->create(['requestable' => false]);

        $this->actingAs(User::factory()->create())
            ->get(route('requestable-assets'))
            ->assertOk()
            ->assertViewHas('accessories')
            ->assertSeeText($accessory->name);
    }

    public function test_user_can_request_a_requestable_accessory(): void
    {
        $accessory = Accessory::factory()->create(['requestable' => true]);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('account/request-item', [
                'itemType' => 'accessory',
                'itemId' => $accessory->id,
            ]), ['request-quantity' => 3])
            ->assertRedirect();

        $request = CheckoutRequest::where('requestable_id', $accessory->id)
            ->where('requestable_type', Accessory::class)
            ->where('user_id', $user->id)
            ->first();

        $this->assertNotNull($request, 'A checkout request should have been created for the accessory.');
        $this->assertEquals(3, $request->quantity, 'The requested quantity should be persisted.');
        $this->assertNull($request->canceled_at);
    }

    public function test_user_cannot_request_an_accessory_that_is_not_requestable(): void
    {
        $accessory = Accessory::factory()->create(['requestable' => false]);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('account/request-item', [
                'itemType' => 'accessory',
                'itemId' => $accessory->id,
            ]))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertNull(
            CheckoutRequest::where('requestable_id', $accessory->id)
                ->where('requestable_type', Accessory::class)
                ->first()
        );
    }

    public function test_user_can_cancel_their_own_accessory_request(): void
    {
        $accessory = Accessory::factory()->create(['requestable' => true]);
        $user = User::factory()->create();
        CheckoutRequest::factory()->create([
            'requestable_id' => $accessory->id,
            'requestable_type' => Accessory::class,
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->post(route('account/request-item', [
                'itemType' => 'accessory',
                'itemId' => $accessory->id,
            ]))
            ->assertRedirect();

        $this->assertNotNull(
            CheckoutRequest::where('requestable_id', $accessory->id)
                ->where('requestable_type', Accessory::class)
                ->where('user_id', $user->id)
                ->whereNotNull('canceled_at')
                ->first()
        );
    }

    public function test_admin_requested_index_renders_accessory_requests(): void
    {
        $accessory = Accessory::factory()->create(['requestable' => true]);
        CheckoutRequest::factory()->create([
            'requestable_id' => $accessory->id,
            'requestable_type' => Accessory::class,
            'user_id' => User::factory()->create()->id,
        ]);

        // The admin "Requested" queue is a polymorphic list of all checkout
        // requests, so an accessory request should render there by name.
        $this->actingAs(User::factory()->viewAssets()->create())
            ->get(route('assets.requested'))
            ->assertOk()
            ->assertViewHas('requestedItems')
            ->assertSeeText($accessory->name);
    }
}
