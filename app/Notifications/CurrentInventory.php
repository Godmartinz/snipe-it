<?php

namespace App\Notifications;

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
        $userAccessories = $this->user->accessories;
        $userLicenses = $this->user->licenses;

        //assigned through assets to user
        $assetsAssets = $userAssets->flatMap(fn ($asset) => $asset->assignedAssets);
        $assetsAccessories = $userAssets->flatMap(fn ($asset) => $asset->assignedAccessories->map(fn ($checkout) => $checkout->accessory)->filter());
        $assetsLicenses = $userAssets->flatMap(fn ($asset) => $asset->licenses);
        $assetsComponents = $userAssets->flatMap(fn ($asset) => $asset->components);

        $allAssets = $userAssets
            ->concat($assetsAssets)
            ->unique()
            ->values();
        $allLicenses = $userLicenses
            ->concat($assetsLicenses)
            ->values();
        $allAccessories = $userAccessories
            ->concat($assetsAccessories)
            ->values();

        $message = (new MailMessage)->markdown('notifications.markdown.user-inventory',
            [
                'assets'  => $allAssets,
                'accessories'  => $allAccessories,
                'licenses'  => $allLicenses,
                'consumables'  => $this->user->consumables,
                'components'  => $assetsComponents,
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
