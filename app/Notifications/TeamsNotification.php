
<?php

namespace App\Services\MicrosoftTeams;

use App\Models\Setting;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsMessage;

class TeamsNotification extends MicrosoftTeamsMessage
{
    protected $payload = [];
    protected string $type = "Good";

    /** @var string webhook url of recipient. */
    protected $webhookUrl = null;

    public function __construct()
    {
        parent::__construct();
        $this->payload = [
            "\$schema" => "https://adaptivecards.io/schemas/adaptive-card.json",
            "type" => "AdaptiveCard",
            "version" => "1.0",
        ];


    }

    public static function create(string $content = ''): self
    {
        return new self();
    }

    public function title(string $title, array $params = []): self
    {
        $this->payload["body"][] = [
            "type" => "TextBlock",
            "wrap" => true,
            "style" => "heading",
            "text" => $title,
            "weight" => "bolder",
            "size" => "large",
        ];

        return $this;
    }

    public function type(string $type): self
    {
        $types = ["info" => "Good", "error" => "Attention", "warning" => "Warning", "success" => "Good"];
        $this->type = $types[$type];

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function to(?string $webhookUrl): self
    {
        if (!$webhookUrl) {
            throw new \Exception("Webhook url is required. Tried to send a teams notification without a webhook url.");
        }
        $this->webhookUrl = $webhookUrl;

        return $this;
    }

    public function content(string $content, array $params = []): self
    {
        // add the content as a new text block
        $this->payload["body"][] = [
            "type" => "TextBlock",
            "wrap" => true,
            "text" => $content,
            "size" => "medium",
            "separator" => true,

        ];
        return $this;
    }

    public function button(string $text, string $url = '', array $params = []): self
    {
        $this->payload["actions"][] = [
            "type" => "Action.OpenUrl",
            "title" => $text,
            "url" => $url,
            "style" => "positive",
        ];

        return $this;
    }

    public function addStartGroupToSection($sectionId = 'standard_section'): self
    {

        return $this;
    }

    public function fact(string $name, string $value, $sectionId = 'standard_section'): self
    {
        $factset = collect($this->payload['body'])->filter(function ($item) {
            return $item['type'] === "FactSet";
        });
        if($factset->isEmpty()) {
            $factset = [
                "type" => "FactSet",
                "facts" => [],
            ];
        }
        $factset['facts'][] = [
            "title" => $name,
            "value" => $value,
        ];

        $this->payload['body'][] = $factset;
        return $this;
    }
    public function getWebhookUrl(): string
    {
        info("Sending to teams wb: $this->webhookUrl");
        return Setting::getSettings()->webhook_endpoint;
    }
    public function toArray(): array
    {
        // check and get the first text block and set the color to the type
         if($this->payload["body"][0] && $this->payload["body"][0]["type"] === "TextBlock") {
             $this->payload["body"][0]["color"] = $this->type;
         }

        return $this->payload;
    }
}
