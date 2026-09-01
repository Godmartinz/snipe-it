<?php

namespace Tests\Unit\Models;

use App\Models\CheckoutRequest;
use Tests\TestCase;

class CheckoutRequestTest extends TestCase
{
    public function test_checkout_request_soft_deleted_when_requested_asset_soft_deleted()
    {
        $checkoutRequest = CheckoutRequest::factory()->forAsset()->create();

        $requestedAsset = $checkoutRequest->requestedItem;

        $requestedAsset->delete();

        $this->assertSoftDeleted($checkoutRequest->fresh());
    }

    public function test_checkout_request_deleted_when_requested_asset_force_deleted()
    {
        $checkoutRequest = CheckoutRequest::factory()->forAsset()->create();

        $requestedAsset = $checkoutRequest->requestedItem;

        $requestedAsset->forceDelete();

        $this->assertDatabaseMissing('checkout_requests', ['id' => $checkoutRequest->id]);
    }

    public function test_checkout_request_soft_deleted_when_requested_model_soft_deleted()
    {
        $checkoutRequest = CheckoutRequest::factory()->forAssetModel()->create();

        $requestedAssetModel = $checkoutRequest->requestedItem;

        $requestedAssetModel->delete();

        $this->assertSoftDeleted($checkoutRequest->fresh());
    }

    public function test_checkout_request_deleted_when_requested_model_force_deleted()
    {
        $checkoutRequest = CheckoutRequest::factory()->forAssetModel()->create();

        $requestedAsset = $checkoutRequest->requestedItem;

        $requestedAsset->forceDelete();

        $this->assertDatabaseMissing('checkout_requests', ['id' => $checkoutRequest->id]);
    }

    public function test_checkout_request_soft_deleted_when_requesting_user_soft_deleted()
    {
        $checkoutRequest = CheckoutRequest::factory()->forAsset()->create();

        $requestingUser = $checkoutRequest->user;

        $requestingUser->delete();

        $this->assertSoftDeleted($checkoutRequest->fresh());
    }

    public function test_checkout_request_deleted_when_requesting_user_force_deleted()
    {
        $checkoutRequest = CheckoutRequest::factory()->forAsset()->create();

        $requestingUser = $checkoutRequest->user;

        $requestingUser->forceDelete();

        $this->assertDatabaseMissing('checkout_requests', ['id' => $checkoutRequest->id]);
    }

    public function test_name_property_access_does_not_trigger_relation_resolution()
    {
        // Regression: CalendarEventsTransformer::titleFor() falls through
        // to `$source->display_name ?? $source->name ?? ...` when the
        // source has no Presentable presenter. CheckoutRequest defines a
        // plain name() method that returns the requestable's display
        // string. Without an accessor, Eloquent's __get('name') routes
        // through getRelationValue('name') → getRelationshipFromMethod()
        // which calls name(), gets a string back instead of a Relation,
        // and throws `LogicException: name must return a relationship
        // instance`, taking down the calendar API for any install that
        // has a reservation-shape CheckoutRequest.
        $checkoutRequest = CheckoutRequest::factory()->forAsset()->create();

        $this->assertSame(
            $checkoutRequest->name(),
            $checkoutRequest->name,
            'Property access `->name` must return the accessor value, not throw when Eloquent misinterprets name() as a relation.'
        );
    }
}
