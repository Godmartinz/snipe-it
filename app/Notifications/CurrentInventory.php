<?php

namespace App\Notifications;

use App\Models\Asset;
use App\Models\Setting;
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
        $this->inventory = $user->inventoryReportData();

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
        $message = (new MailMessage)->markdown('notifications.markdown.user-inventory',
            [
                'assets' => $this->inventory['userAssets'],
                'accessories' => $this->inventory['userAccessories'],
                'consumables' => $this->user->consumables,
                'licenses' => $this->inventory['userLicenses'],
                'assetsAssets' => $this->inventory['assetsAssets'],
                'assetsAccessories' => $this->inventory['assetsAccessories'],
                'assetsLicenseSeats' => $this->inventory['assetsLicenseSeats'],
                'assetsComponents' => $this->inventory['assetsComponents'],
                'item2AssetCount' => $this->inventory['assetsAssignmentCount'],
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
