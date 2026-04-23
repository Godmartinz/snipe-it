<?php

namespace App\Models\Labels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

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

    public static function availableBaseLabels(): array
    {
        $namespaceRoot = 'App\\Models\\Labels\\';
        $basePath = app_path('Models/Labels');

        $allowedRoots = [
            $basePath . '/Sheets',
            $basePath . '/Tapes',
            $basePath . '/DefaultLabel.php',
        ];

        $labels = [];

        foreach ($allowedRoots as $root) {
            if (is_file($root)) {
                $files = [new \SplFileInfo($root)];
            } else {
                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($root)
                );
            }

            foreach ($files as $file) {
                if (!$file->isFile() || $file->getExtension() !== 'php') {
                    continue;
                }

                $fullPath = $file->getPathname();
                $relativePath = Str::after($fullPath, $basePath . DIRECTORY_SEPARATOR);

                $class = $namespaceRoot . str_replace(
                        [DIRECTORY_SEPARATOR, '.php'],
                        ['\\', ''],
                        $relativePath
                    );

                if (!class_exists($class)) {
                    continue;
                }

                $labels[class_basename($class)] = $class;
            }
        }

        ksort($labels);

        return $labels;
    }

    public static function makeBaseLabel(?string $template): ?object
    {
        if (!$template) {
            return null;
        }

        $available = static::availableBaseLabels();

        $class = $available[$template] ?? null;

        if (!$class || !class_exists($class)) {
            return null;
        }

        return new $class();
    }

    public static function diffEditorConfig(array $finalConfig, array $baseConfig): array
    {
        $diff = [];

        foreach ($finalConfig as $key => $value) {
            $baseValue = $baseConfig[$key] ?? null;

            if (is_array($value) && is_array($baseValue)) {
                $nestedDiff = static::diffEditorConfig($value, $baseValue);

                if (!empty($nestedDiff)) {
                    $diff[$key] = $nestedDiff;
                }

                continue;
            }

            if ($value !== $baseValue) {
                $diff[$key] = $value;
            }
        }

        return $diff;
    }
}