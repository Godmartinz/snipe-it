<?php

namespace App\Livewire;

use App\Helpers\Helper;
use App\Models\Setting;
use App\Rules\ExternalUrl;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Livewire\Component;
use Osama\LaravelTeamsNotification\TeamsNotification;

class SlackSettingsForm extends Component
{
    public $webhook_endpoint;

    public $webhook_channel;

    public $webhook_botname;

    public $isDisabled = 'disabled';

    public $webhook_name;

    public $webhook_link;

    public $webhook_placeholder;

    public $webhook_icon;

    public $webhook_selected;

    public $teams_webhook_deprecated;

    public array $webhook_text;

    public Setting $setting;

    public $save_button;

    public $webhook_endpoint_rules;

    public ?string $warning = null;

    public ?string $success = null;

    public ?string $error = null;

    protected function rules(): array
    {
        return [
            'webhook_endpoint' => [
                'nullable',
                'required_with:webhook_channel',
                'url',
                new ExternalUrl,
            ],
            'webhook_channel' => 'required_with:webhook_endpoint|starts_with:#|nullable',
            'webhook_botname' => 'string|nullable',
        ];
    }

    /**
     * Route-level middleware on the notifications settings page requires
     * superuser, but snapshot replay to POST /livewire/update bypasses
     * that gate. Without this check, a low-privilege user with a valid
     * snapshot could invoke testWebhook / clearSettings / submit and
     * mutate global webhook configuration or exfiltrate the configured
     * webhook_endpoint / channel through the render payload.
     */
    public function boot(): void
    {
        if (! auth()->user()?->isSuperUser()) {
            abort(403);
        }
    }

    public function mount()
    {
        $this->webhook_text = [
            'slack' => [
                'name' => trans('admin/settings/general.slack'),
                'icon' => 'fab fa-slack',
                'placeholder' => 'https://hooks.slack.com/services/XXXXXXXXXXXXXXXXXXXXX',
                'link' => 'https://api.slack.com/messaging/webhooks',
            ],
            'general' => [
                'name' => trans('admin/settings/general.general_webhook'),
                'icon' => 'fab fa-hashtag',
                'placeholder' => trans('general.url'),
                'link' => '',
            ],
            'google' => [
                'name' => trans('admin/settings/general.google_workspaces'),
                'icon' => 'fa-brands fa-google',
                'placeholder' => 'https://chat.googleapis.com/v1/spaces/xxxxxxxx/messages?key=xxxxxx',
                'link' => 'https://developers.google.com/chat/how-tos/webhooks#register_the_incoming_webhook',
            ],
            'microsoft' => [
                'name' => trans('admin/settings/general.ms_teams'),
                'icon' => 'fa-brands fa-microsoft',
                'placeholder' => 'https://abcd.webhook.office.com/webhookb2/XXXXXXX',
                'link' => 'https://support.microsoft.com/en-us/office/create-incoming-webhooks-with-workflows-for-microsoft-teams-8ae491c7-0394-4861-ba59-055e33f75498',
            ],
        ];

        $this->setting = Setting::getSettings();
        $this->save_button = trans('general.save');
        if (!$this->webhook_selected) {
            $this->webhook_selected = 'slack';
        }


        $this->webhook_options = $this->setting->webhook_selected ? $this->setting->webhook_selected : 'slack';


        $this->updatedWebhookSelected();
        $this->webhook_endpoint = $this->setting->webhook_endpoint;
        $this->webhook_channel = $this->setting->webhook_channel;
        $this->webhook_botname = $this->setting->webhook_botname;
        $this->teams_webhook_deprecated = !Str::contains($this->webhook_endpoint, 'workflows'); // consider moving this to webhook_link updated? (updatedWebhookLink?)

        if ($this->setting->webhook_endpoint != null && $this->setting->webhook_channel != null) {
            $this->isDisabled = '';
        }
        if ($this->webhook_selected === 'microsoft' && $this->teams_webhook_deprecated) { //since this is URL-aware, maybe also move this?
            $this->warning = trans('admin/settings/message.webhook.ms_teams_deprecation');
        }
    }

    public function updated($field)
    {
        //anything changes; then clear the succcess message (and error message)
        $this->success = null;
        $this->error = null;
        $this->validateOnly($field);

    }

    public function updatedWebhookSelected()
    {
        $this->webhook_name = $this->webhook_text[$this->webhook_selected]['name'];
        $this->webhook_icon = $this->webhook_text[$this->webhook_selected]['icon'];
        $this->webhook_placeholder = $this->webhook_text[$this->webhook_selected]['placeholder'];
        $this->webhook_endpoint = null; // TODO - do we really want to blank this?
        $this->webhook_link = $this->webhook_text[$this->webhook_selected]['link'];
        if ($this->webhook_selected != 'slack') { // TODO: hrm. Wouldn't we want to test all of them? Or at least some of them? Maybe not "generic webhook"?
            $this->isDisabled = '';
            $this->save_button = trans('general.save');
        }
        if ($this->webhook_selected == 'microsoft' || $this->webhook_selected == 'google') {
            $this->webhook_channel = '#NA';
        }
    }

    public function updatedwebhookEndpoint()
    {
        $this->teams_webhook_deprecated = !Str::contains($this->webhook_endpoint, 'workflows');
    }

    public function render()
    {
        if (empty($this->webhook_endpoint) || empty($this->webhook_channel)) {
            $this->isDisabled = 'disabled';
            $this->save_button = trans('admin/settings/general.webhook_presave');
        }

        return view('livewire.slack-settings-form');

    }

    public function clearSettings()
    {

        if (Helper::isDemoMode()) {
            $this->error = trans('general.feature_disabled');
        } else {
            $this->webhook_endpoint = '';
            $this->webhook_channel = '';
            $this->webhook_botname = '';
            $this->setting->webhook_endpoint = '';
            $this->setting->webhook_channel = '';
            $this->setting->webhook_botname = '';

            $this->setting->save();

            $this->success = trans('admin/settings/message.update.success');
        }
    }

    public function submit()
    {
        if (Helper::isDemoMode()) {
            $this->error = trans('general.feature_disabled');
        } else {
            $this->validate();

            $this->setting->webhook_selected = $this->webhook_selected;
            $this->setting->webhook_endpoint = $this->webhook_endpoint;
            $this->setting->webhook_channel = $this->webhook_channel;
            $this->setting->webhook_botname = $this->webhook_botname;

            $this->setting->save();

            $this->success = trans('admin/settings/message.update.success');
        }

    }

    public function universalWebhookTest()
    {
        $executed = RateLimiter::attempt(
            key: 'test-connection:' . auth()->id(),
            maxAttempts: 5,
            decaySeconds: 60,
            callback: function () {
                $validator = Validator::make(
                    ['webhook_endpoint' => $this->webhook_endpoint],
                    ['webhook_endpoint' => ['required', 'url', new ExternalUrl]],
                );

                if ($validator->fails()) {
                    $this->isDisabled = 'disabled';
                    $this->save_button = trans('admin/settings/general.webhook_presave');

                    $this->error = $validator->errors()->first('webhook_endpoint');
                    return;
                }
                $this->error = '';
                $this->success = '';

                $payload = match ($this->webhook_selected) {
                    'slack', 'general' => [
                        'channel' => e($this->webhook_channel),
                        'text' => trans('general.webhook_test_msg', ['app' => $this->webhook_name]),
                        'username' => e($this->webhook_botname),
                        'icon_emoji' => ':heart:',
                    ],
                    'google' => [
                        'text' => trans('general.webhook_test_msg', ['app' => $this->webhook_name]),
                    ],
                    'microsoft' => [
                        '@type' => 'MessageCard',
                        '@context' => 'http://schema.org/extensions',
                        'summary' => trans('mail.snipe_webhook_summary'),
                        'title' => trans('mail.snipe_webhook_test'),
                        'text' => trans('general.webhook_test_msg', ['app' => $this->webhook_name]),
                    ],
                    default => throw new \Exception("Unknown provider")
                };

                $status_code = null;

                try {
                    if ($this->webhook_selected == "microsoft" && !$this->teams_webhook_deprecated) {
                        //new-fangled webhook - use package
                        $notification = new TeamsNotification($this->webhook_endpoint);
                        $message = trans('general.webhook_test_msg', ['app' => $this->webhook_name]);
                        $status_code = $notification->success()->sendMessage($message);
                    } else {
                        $response = Http::withHeaders([
                            'content-type' => 'application/json',
                        ])->withOptions(['allow_redirects' => false])
                            ->post($this->webhook_endpoint, $payload)/*->throw()*/
                        ;
                        $status_code = $response->getStatusCode();
                    }

                    if ($status_code >= 300 && $status_code < 400) {
                        //these, still, might happen. It seems like allow_redirects being false just doesn't follow them, it doesn't cause an Exception on them.
                        $this->error = trans('admin/settings/message.webhook.error_redirect', ['endpoint' => $this->webhook_endpoint]);
                        //TODO: one possibliity here is to re-throw so that everything goes back through the exception handler?
                    } elseif ($status_code >= 400 && $status_code < 500) {
                        //this _shouldn't_ happen because the "->throw()" should catch it
                        $this->error = trans('admin/settings/message.webhook.error_redirect', ['endpoint' => $this->webhook_endpoint]);
                    } elseif ($status_code >= 500) {
                        //this _shouldn't_ happen because the "->throw()" should catch it
                        $this->error = trans('admin/settings/message.webhook.error_server');
                    } elseif ($status_code >= 200 && $status_code < 300) {
                        $this->isDisabled = '';
                        $this->save_button = trans('general.save');

                        $this->success = trans('admin/settings/message.webhook.success', ['webhook_name' => $this->webhook_name]);
                        return true; // This is just for EqualTiming to use; this method on this controller doesn't actually return anything
                    } else {
                        throw new \Exception(trans('admin/settings/message.webhook.error_misc'));
                    }

                } catch (\Exception $e) {
                    Log::warning('Webhook test failed', [
                        'endpoint' => $this->webhook_endpoint,
                        'app' => $this->webhook_name,
                        'exception' => $e::class,
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ]);

                    $this->isDisabled = 'disabled';
                    $this->save_button = trans('admin/settings/general.webhook_presave');

                    $this->error = trans('admin/settings/message.webhook.error', [
                        'error_message' => $e->getMessage(),
                        'app' => $this->webhook_name,
                    ]);
                    return false;
                }
            },
        );

        if (!$executed) {
            $this->addError('connection', trans('admin/settings/general.rate_limited'));
        }
    }
}