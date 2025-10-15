<?php

use App\Events\AssetsTransferredInBulk;
use App\Mail\CheckoutAccessoryMail;
use App\Mail\CheckoutAssetMail;
use App\Mail\CheckoutComponentMail;
use App\Mail\CheckoutConsumableMail;
use App\Mail\CheckoutLicenseMail;
use App\Models\Accessory;
use App\Models\Asset;
use App\Models\CheckoutAcceptance;
use App\Models\Component;
use App\Models\Consumable;
use App\Models\LicenseSeat;
use App\Models\Location;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpClient\Exception\ClientException;

class TransferrableListener
{
    public function subscribe($events)
    {
        $events->listen(
            AssetsTransferredInBulk::class,
            'App\Listeners\TransferrableListener@onTransfer'
        );
    }
    public function onTransfer($event){
        if($this->shouldNotSendAnyNotifications($event->transferrable)){
            return;
        }
        $acceptance = $this->getTransferAcceptance($event->transferedTo);

        $shouldSendEmailToUser = $this->shouldSendTransferEmailToUser($event->transferrable);
        $shouldSendEmailToAlertAddress = $this->shouldSendEmailToAlertAddress($acceptance);
        $shouldSendWebhookNotification = $this->shouldSendWebhookNotification();

        if (!$shouldSendEmailToUser && !$shouldSendEmailToAlertAddress && !$shouldSendWebhookNotification) {
            return;
        }
        if ($shouldSendEmailToUser || $shouldSendEmailToAlertAddress) {
            $mailable = new TransferredEmail($event->transferrable, $event->transferedTo, $event->transferedBy, $acceptance, $event->note);
            $notifiable = $this->getNotifiableUser($event);
            $notifiableHasEmail = $notifiable instanceof User && $notifiable->email;
            $shouldSendEmailToUser = $shouldSendEmailToUser && $notifiableHasEmail;

            [$to, $cc] = $this->generateEmailRecipients($shouldSendEmailToUser, $shouldSendEmailToAlertAddress, $notifiable);

            if (!empty($to)) {
                try {
                    $toMail = (clone $mailable)->locale($notifiable->locale);
                    Mail::to(array_flatten($to))->send($toMail);
                    Log::info('Transfer Mail sent to transfer target');
                } catch (ClientException $e) {
                    Log::debug("Exception caught during transfer email: " . $e->getMessage());
                } catch (Exception $e) {
                    Log::debug("Exception caught during transfer email: " . $e->getMessage());
                }
            }
            if(!empty($cc)) {
                try {
                    $ccMail = (clone $mailable)->locale(Setting::getSettings()->locale);
                    Mail::to(array_flatten($cc))->send($ccMail);
                } catch (ClientException $e) {
                    Log::debug("Exception caught during transfer email: " . $e->getMessage());
                }
                catch (Exception $e) {
                    Log::debug("Exception caught during transfer email: " . $e->getMessage());
                }
            }
        }
    }
    /**
     * Generates a checkout acceptance
     * @param  Event $event
     * @return mixed
     */
    private function getTransferAcceptance($event)
    {
        $checkedOutToType = get_class($event->checkedOutTo);
        if ($checkedOutToType != "App\Models\User") {
            return null;
        }

        if (!$event->checkoutable->requireAcceptance()) {
            return null;
        }

        $acceptance = new CheckoutAcceptance;
        $acceptance->checkoutable()->associate($event->checkoutable);
        $acceptance->assignedTo()->associate($event->checkedOutTo);

        $acceptance->qty = 1;

        if (isset($event->checkoutable->checkout_qty)) {
            $acceptance->qty = $event->checkoutable->checkout_qty;
        }

        $category = $this->getCategoryFromCheckoutable($event->checkoutable);

        if ($category?->alert_on_response) {
            $acceptance->alert_on_response_id = auth()->id();
        }

        $acceptance->save();

        return $acceptance;
    }
    private function shouldNotSendAnyNotifications($transferrable): bool
    {
        return in_array(get_class($transferrable), $this->skipNotificationsFor);
    }
    private function shouldSendWebhookNotification(): bool
    {
        return Setting::getSettings() && Setting::getSettings()->webhook_endpoint;
    }

    private function shouldSendTransferEmailToUser(Model $checkoutable): bool
    {
        /**
         * Send an email if any of the following conditions are met:
         * 1. The asset requires acceptance
         * 2. The item has a EULA
         * 3. The item should send an email at check-in/check-out
         */

        if (Context::get('action') === 'transfer') {
            return true;
        }

        if ($checkoutable->requireAcceptance()) {
            return true;
        }

        if ($checkoutable->getEula()) {
            return true;
        }

        if ($this->checkoutableCategoryShouldSendEmail($checkoutable)) {
            return true;
        }

        return false;
    }

    private function shouldSendEmailToAlertAddress($acceptance = null): bool
    {
        $setting = Setting::getSettings();

        if (!$setting) {
            return false;
        }

        if (is_null($acceptance) && !$setting->admin_cc_always) {
            return false;
        }

        return (bool) $setting->admin_cc_email;
    }
    private function getFormattedAlertAddresses(): array
    {
        $alertAddresses = Setting::getSettings()->admin_cc_email;

        if ($alertAddresses !== '') {
            return array_filter(array_map('trim', explode(',', $alertAddresses)));
        }

        return [];
    }
    /**
     * This gets the recipient objects based on the type of checkoutable.
     * The 'name' property for users is set in the boot method in the User model.
     *
     * @see \App\Models\User::boot()
     * @param $event
     * @return mixed
     */
    private function getNotifiableUser($event)
    {

        // If it's assigned to an asset, get that asset's assignedTo object
        if ($event->checkedOutTo instanceof Asset){
            $event->checkedOutTo->load('assignedTo');
            return $event->checkedOutTo->assignedto;

            // If it's assigned to a location, get that location's manager object
        } elseif ($event->checkedOutTo instanceof Location) {
            return $event->checkedOutTo->manager;

            // Otherwise just return the assigned to object
        } else {
            return $event->checkedOutTo;
        }
    }
    private function generateEmailRecipients(
        bool $shouldSendEmailToUser,
        bool $shouldSendEmailToAlertAddress,
        mixed $notifiable
    ): array {
        $to = [];
        $cc = [];

        // if user && cc: to user, cc admin
        if ($shouldSendEmailToUser && $shouldSendEmailToAlertAddress) {
            $to[] = $notifiable;
            $cc[] = $this->getFormattedAlertAddresses();
        }

        // if user && no cc: to user
        if ($shouldSendEmailToUser && !$shouldSendEmailToAlertAddress) {
            $to[] = $notifiable;
        }

        // if no user && cc: to admin
        if (!$shouldSendEmailToUser && $shouldSendEmailToAlertAddress) {
            $to[] = $this->getFormattedAlertAddresses();
        }

        return array($to, $cc);
    }
}