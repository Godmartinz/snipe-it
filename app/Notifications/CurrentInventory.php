<?php

namespace App\Notifications;

use App\Models\Asset;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Symfony\Component\Mime\Email;

#[AllowDynamicProperties]
class CurrentInventory extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via()
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail()
    {
        //assigned to user
        $userAssets = $this->user->assets;
        $assetIds = $userAssets->pluck('id')->toArray();
        $userAccessories = $this->user->accessories;
        $userLicenses = $this->user->licenses()
            ->wherePivotNull('asset_id')
            ->get();;

        //assigned through assets to user
        $assetsAssets = $userAssets->flatMap(fn ($asset) => $asset->assignedAssets);
        $assetsAccessories = $userAssets->flatMap(function ($asset) {
            return $asset->assignedAccessories()
                ->with('assignedTo', 'accessory.category')
                ->get();
        })->values();
        $assetsLicenseSeats = \App\Models\LicenseSeat::query()
            ->whereIn('asset_id', $assetIds)
            ->with(['license.category', 'asset'])
            ->get();
        $assetsComponents = \App\Models\Asset::query()
            ->whereIn('id', $assetIds)
            ->whereHas('components')
            ->with('components')
            ->get();

       $assetsAssignmentCount = $assetsComponents->count() + $assetsLicenseSeats->count() + $assetsAssets->count() + $assetsAccessories->count();

        $message = (new MailMessage)->markdown('notifications.markdown.user-inventory',
            [
                'assets'  => $userAssets,
                'accessories'  => $userAccessories,
                'licenses'  => $userLicenses,
                'consumables'  => $this->user->consumables,
                'components'  => $assetsComponents,
                'assetsAssets'  => $assetsAssets,
                'assetsAccessories'  => $assetsAccessories,
                'assetsLicenseSeats'  => $assetsLicenseSeats,
                'assetsComponents'  => $assetsComponents,
                'asset2AssetCount' => $assetsAssignmentCount,
            ])
            ->subject(trans('mail.inventory_report'))
            ->withSymfonyMessage(function (Email $message) {
                $message->getHeaders()->addTextHeader(
                    'X-System-Sender', 'Snipe-IT'
                );
            });

        return $message;
    }
}
