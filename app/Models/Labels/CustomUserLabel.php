<?php

namespace App\Models\Labels;

use Illuminate\Database\Eloquent\Model;

class CustomUserLabel extends Model
{
    protected $table = 'custom_user_labels';

    protected $fillable = [
        'name',
        'base_label',
        'type',
        'overrides',
        'config_snapshot',
        'is_default',
    ];

    protected $casts = [
        'overrides' => 'array',
        'config_snapshot' => 'array',
        'is_default' => 'boolean',
    ];


    public function applyOverrides(array $config): array
    {
        return array_merge($config, $this->overrides ?? []);
    }

    protected function resolveLabelClass(string $template): string
    {
        // Custom labels first
        $custom = "App\\Models\\Labels\\CustomLabels\\{$template}";
        if (class_exists($custom)) {
            return $custom;
        }

        // Avery sheets
        if (!str_starts_with($template, '_') && !str_starts_with($template, 'L')) {
            $template = '_' . $template;
        }

        $avery = "App\\Models\\Labels\\Sheets\\Avery\\{$template}";
        if (class_exists($avery)) {
            return $avery;
        }

        throw new \InvalidArgumentException("Unknown label template: {$template}");
    }
}