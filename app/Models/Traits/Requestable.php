<?php

namespace App\Models\Traits;

use App\Models\CheckoutRequest;
use App\Models\User;
use Carbon\Carbon;

// $asset->requests
// $asset->isRequestedBy($user)
// $asset->whereRequestedBy($user)
trait Requestable
{
    public function requests()
    {
        return $this->morphMany(CheckoutRequest::class, 'requestable');
    }

    public function isRequestedBy(User $user)
    {
        // Fresh query rather than filtering the loaded ->requests
        // collection so a same-request-cycle check-then-cancel sees
        // current DB state instead of a possibly stale eager-loaded
        // snapshot.
        return $this->requests()->whereNull('canceled_at')->where('user_id', $user->id)->first();
    }

    public function scopeRequestedBy($query, User $user)
    {
        return $query->whereHas(
            'requests', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }
        );
    }

    public function request($qty = 1)
    {
        $this->requests()->save(
            new CheckoutRequest(['user_id' => auth()->id(), 'quantity' => $qty])
        );
    }

    public function deleteRequest()
    {
        $this->requests()->where('user_id', auth()->id())->delete();
    }

    /**
     * Mark every active CheckoutRequest for $user_id (or the current
     * auth user) as canceled. Returns the number of rows actually
     * flipped so callers can gate side effects (counter decrement,
     * notifications, log entries) on real work happening. The
     * whereNull filter prevents already-canceled rows from getting
     * their canceled_at bumped, and prevents no-op cancellations from
     * looking like real events downstream.
     */
    public function cancelRequest($user_id = null): int
    {
        if (! $user_id) {
            $user_id = auth()->id();
        }

        return $this->requests()
            ->where('user_id', $user_id)
            ->whereNull('canceled_at')
            ->update(['canceled_at' => Carbon::now()]);
    }
}
