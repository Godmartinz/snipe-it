<?php

namespace App\Console\Commands;

use App\Models\Accessory;
use App\Models\Actionlog;
use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Category;
use App\Models\Company;
use App\Models\Component;
use App\Models\Consumable;
use App\Models\Department;
use App\Models\Import;
use App\Models\Location;
use App\Models\Maintenance;
use App\Models\Manufacturer;
use App\Models\Setting;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\table;

/**
 * Walk every DB reference that points at an uploaded file and check
 * whether the file actually exists on disk. Covers:
 *
 *   - Model (MVC model, not AssetModel) image + avatar columns
 *     (assets, models, accessories, consumables, components, locations,
 *     manufacturers, suppliers, companies, departments, categories, maintenances, users).
 *   - Settings logos (logo, email_logo, label_logo, acceptance_pdf_logo,
 *     favicon).
 *   - action_logs.filename rows that point at file uploads (audit files,
 *     EULA acceptance PDFs, item file attachments).
 *   - imports.file_path rows (CSV files uploaded through the importer,
 *     stored under private_uploads/imports/).
 *
 * S3 compat comes for free by routing everything through the Storage
 * facade.
 */
class CheckOrphanUploads extends Command
{
    protected $signature = 'snipeit:check-orphan-uploads
        {--summary : Show only totals, not the full per-file listing}
        {--json : Emit machine-readable JSON instead of tables}
        {--csv= : Write missing rows to a CSV at this path (empty = no CSV)}
        {--chunk=1000 : DB rows fetched per batch when scanning image columns}
        {--only= : Comma-separated list of categories to scan (models, settings, action_logs, imports). Default = all.}';

    protected $description = 'Walk DB references to uploaded files and report which no longer exist on disk.';

    /**
     * Model classes with an image column that live on the public disk.
     * Each entry is [modelClass, columnName, storagePrefix].
     */
    private const MODEL_IMAGE_SOURCES = [
        [Accessory::class, 'image', 'accessories/'],
        [Asset::class, 'image', 'assets/'],
        [AssetModel::class, 'image', 'models/'],
        [Category::class, 'image', 'categories/'],
        [Company::class, 'image', 'companies/'],
        [Component::class, 'image', 'components/'],
        [Consumable::class, 'image', 'consumables/'],
        [Department::class, 'image', 'departments/'],
        [Location::class, 'image', 'locations/'],
        [Maintenance::class, 'image', 'maintenances/'],
        [Manufacturer::class, 'image', 'manufacturers/'],
        [Supplier::class, 'image', 'suppliers/'],
        [User::class, 'avatar', 'avatars/'],
    ];

    /**
     * Setting columns that carry uploaded logos or icons. All sit on
     * the public disk at the root w/no prefix.
     */
    private const SETTING_LOGO_COLUMNS = [
        'logo',
        'email_logo',
        'label_logo',
        'acceptance_pdf_logo',
        'favicon',
    ];

    public function handle(): int
    {
        $only = $this->parseOnlyOption();
        $summary = (bool) $this->option('summary');
        $json = (bool) $this->option('json');
        $csv = $this->option('csv');
        $chunk = max(100, (int) $this->option('chunk'));

        $missing = [];

        if (in_array('models', $only, true)) {
            if (! $json) {
                $this->info('Scanning model image + avatar columns...');
            }
            $missing = array_merge($missing, $this->scanModelImages($chunk));
        }

        if (in_array('settings', $only, true)) {
            if (! $json) {
                $this->info('Scanning settings logos...');
            }
            $missing = array_merge($missing, $this->scanSettingsLogos());
        }

        if (in_array('action_logs', $only, true)) {
            if (! $json) {
                $this->info('Scanning action_logs.filename...');
            }
            $missing = array_merge($missing, $this->scanActionLogFilenames($chunk));
        }

        if (in_array('imports', $only, true)) {
            if (! $json) {
                $this->info('Scanning imports.file_path...');
            }
            $missing = array_merge($missing, $this->scanImportFiles($chunk));
        }

        if ($csv) {
            $this->writeCsv($csv, $missing);
        }

        if ($json) {
            $this->line(json_encode([
                'total_missing' => count($missing),
                'missing_by_table_type' => $this->tallyByReference($missing),
                'missing' => $summary ? [] : $missing,
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            return self::SUCCESS;
        }

        $this->renderReport($missing, $summary);

        return self::SUCCESS;
    }

    /**
     * Scan every model image / avatar column. Rows with a non-empty
     * value get an existence check against the public disk at the
     * scan target's prefix + the column value.
     *
     * @return array<int, array{table: string, type: string, id: int|string, file: string}>
     */
    private function scanModelImages(int $chunk): array
    {
        $missing = [];

        foreach (self::MODEL_IMAGE_SOURCES as [$modelClass, $column, $prefix]) {
            $table = (new $modelClass)->getTable();
            $count = 0;
            $missCount = 0;

            $modelClass::query()
                ->select(['id', $column])
                ->whereNotNull($column)
                ->where($column, '!=', '')
                ->chunkById($chunk, function ($rows) use (&$missing, &$count, &$missCount, $column, $prefix, $table): void {
                    foreach ($rows as $row) {
                        $count++;
                        $value = (string) $row->{$column};

                        // Skip anything that looks like an external URL
                        // (older installs occasionally stored full
                        // URLs). The exists check would false-negative
                        // on those and pollute the report.
                        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://') || str_starts_with($value, '//')) {
                            continue;
                        }

                        $relative = $prefix.$value;
                        if (! Storage::disk('public')->exists($relative)) {
                            $missCount++;
                            $missing[] = [
                                'table' => $table,
                                'type' => $column,
                                'id' => $row->id,
                                'file' => $this->displayPath('public', $relative),
                            ];
                        }
                    }
                });

            if ($count > 0) {
                $this->line("  $table.$column: ".number_format($count).' references, '.number_format($missCount).' missing.');
            }
        }

        return $missing;
    }

    /**
     * The settings singleton has five upload columns. All sit on the
     * public disk at the root (no prefix). Only one row exists.
     *
     * @return array<int, array{table: string, type: string, id: int|string, file: string}>
     */
    private function scanSettingsLogos(): array
    {
        $missing = [];
        $settings = Setting::first();

        if (! $settings) {
            return [];
        }

        foreach (self::SETTING_LOGO_COLUMNS as $column) {
            $value = (string) ($settings->{$column} ?? '');
            if ($value === '') {
                continue;
            }

            if (! Storage::disk('public')->exists($value)) {
                $missing[] = [
                    'table' => 'settings',
                    'type' => $column,
                    'id' => $settings->id,
                    'file' => $this->displayPath('public', $value),
                ];
            }
        }

        $this->line('  settings logos: '.number_format(count(self::SETTING_LOGO_COLUMNS)).' columns checked, '.number_format(count($missing)).' missing.');

        return $missing;
    }

    /**
     * action_logs.filename rows live under a handful of different
     * private disk directories depending on the action_type +
     * item_type combo. Actionlog already knows how to resolve its
     * own path via uploads_file_path(), so we call through that
     * instead of re-implementing the switch here.
     *
     * The check reads from the default disk (PRIVATE_FILESYSTEM_DISK)
     * so S3-backed setups get their private bucket read instead of
     * the local storage_path().
     *
     * @return array<int, array{table: string, type: string, id: int|string, file: string}>
     */
    private function scanActionLogFilenames(int $chunk): array
    {
        $missing = [];
        $count = 0;

        Actionlog::query()
            ->whereNotNull('filename')
            ->where('filename', '!=', '')
            ->chunkById($chunk, function ($rows) use (&$missing, &$count): void {
                foreach ($rows as $log) {
                    $count++;
                    $path = $log->uploads_file_path();

                    if (! $path) {
                        continue;
                    }

                    if (! Storage::exists($path)) {
                        $missing[] = [
                            'table' => 'action_logs',
                            'type' => 'filename',
                            'id' => $log->id,
                            'file' => $this->displayPath(config('filesystems.default'), $path),
                        ];
                    }
                }
            });

        $this->line('  action_logs.filename: '.number_format($count).' references, '.number_format(count($missing)).' missing.');

        return $missing;
    }

    /**
     * imports.file_path stores the on-disk name of a CSV that was
     * uploaded through the importer UI. Files live in
     * private_uploads/imports/ on the default disk.
     *
     * @return array<int, array{table: string, type: string, id: int|string, file: string}>
     */
    private function scanImportFiles(int $chunk): array
    {
        $missing = [];
        $count = 0;

        Import::query()
            ->whereNotNull('file_path')
            ->where('file_path', '!=', '')
            ->chunkById($chunk, function ($rows) use (&$missing, &$count): void {
                foreach ($rows as $import) {
                    $count++;
                    $path = 'private_uploads/imports/'.$import->file_path;

                    if (! Storage::exists($path)) {
                        $missing[] = [
                            'table' => 'imports',
                            'type' => 'file_path',
                            'id' => $import->id,
                            'file' => $this->displayPath(config('filesystems.default'), $path),
                        ];
                    }
                }
            });

        $this->line('  imports.file_path: '.number_format($count).' references, '.number_format(count($missing)).' missing.');

        return $missing;
    }

    /**
     * Turn a disk-relative path into something readable in the output.
     * On local disks that means prepending the disk root relative to
     * the project root, so `models/mbp9.jpg` on the public disk
     * displays as `public/uploads/models/mbp9.jpg`. On non-local
     * disks (S3, etc.) the driver's own path isn't a filesystem
     * location, so we prefix the disk name in brackets and keep the
     * relative key.
     */
    private function displayPath(string $disk, string $relative): string
    {
        $config = config("filesystems.disks.$disk");
        $driver = $config['driver'] ?? '';

        if ($driver !== 'local') {
            return "[$disk] $relative";
        }

        $root = (string) ($config['root'] ?? '');
        $base = base_path();

        if ($root !== '' && str_starts_with($root, $base)) {
            $repoRelative = ltrim(substr($root, strlen($base)), DIRECTORY_SEPARATOR.'/');

            return $repoRelative === '' ? $relative : "$repoRelative/$relative";
        }

        // Root is outside the project (unusual for local disks). Show
        // the absolute filesystem path so user can still find
        // the file.
        return rtrim($root, DIRECTORY_SEPARATOR.'/').'/'.$relative;
    }

    /**
     * @param  array<int, array{table: string, type: string, id: int|string, file: string}>  $missing
     */
    private function renderReport(array $missing, bool $summary): void
    {
        if ($missing === []) {
            $this->info('No missing files. Every DB reference points at a file that exists on disk.');

            return;
        }

        $tallies = $this->tallyByReference($missing);
        $idGroups = $this->idsByReference($missing);
        $totals = [];
        foreach ($tallies as $key => $count) {
            [$table, $type] = explode('.', $key, 2);
            $totals[] = [$table, $type, number_format($count), $this->formatIdList($idGroups[$key] ?? [])];
        }
        $totals[] = ['TOTAL', '', number_format(count($missing)), ''];

        table(['Table', 'Type', 'Missing', 'Row IDs'], $totals);

        if ($summary) {
            return;
        }

        $this->warn('Full list of missing files:');
        table(
            ['Table', 'ID', 'Type', 'File'],
            array_map(fn (array $row): array => [
                $row['table'],
                (string) $row['id'],
                $row['type'],
                $row['file'],
            ], $missing),
        );
    }

    /**
     * Roll missing rows up by table.type so the summary table can show
     * counts per column source.
     *
     * @param  array<int, array{table: string, type: string, id: int|string, file: string}>  $missing
     * @return array<string, int>
     */
    private function tallyByReference(array $missing): array
    {
        $tally = [];
        foreach ($missing as $row) {
            $key = $row['table'].'.'.$row['type'];
            $tally[$key] = ($tally[$key] ?? 0) + 1;
        }
        ksort($tally);

        return $tally;
    }

    /**
     * Group IDs by table.type so the summary table can show which rows
     * to go look at without having to scroll through the full list.
     *
     * @param  array<int, array{table: string, type: string, id: int|string, file: string}>  $missing
     * @return array<string, array<int, int|string>>
     */
    private function idsByReference(array $missing): array
    {
        $ids = [];
        foreach ($missing as $row) {
            $key = $row['table'].'.'.$row['type'];
            $ids[$key][] = $row['id'];
        }

        return $ids;
    }

    /**
     * Cap the ID list at ~10 to keep the table row from wrapping to
     * three lines on installs with hundreds of missing files. The
     * full list is still available in the per-row table below (or
     * via --csv / --json).
     *
     * @param  array<int, int|string>  $ids
     */
    private function formatIdList(array $ids): string
    {
        if ($ids === []) {
            return '';
        }

        $limit = 10;
        if (count($ids) <= $limit) {
            return implode(', ', $ids);
        }

        $head = array_slice($ids, 0, $limit);

        return implode(', ', $head).', ... ('.number_format(count($ids) - $limit).' more)';
    }

    /**
     * @param  array<int, array{table: string, type: string, id: int|string, file: string}>  $missing
     */
    private function writeCsv(string $csvPath, array $missing): void
    {
        $handle = fopen($csvPath, 'w');
        if ($handle === false) {
            $this->warn("Could not open CSV path for writing: $csvPath");

            return;
        }

        fputcsv($handle, ['table', 'id', 'type', 'file']);
        foreach ($missing as $row) {
            fputcsv($handle, [$row['table'], $row['id'], $row['type'], $row['file']]);
        }
        fclose($handle);

        $this->info('Wrote '.number_format(count($missing))." row(s) to $csvPath.");
    }

    /**
     * @return array<int, string>
     */
    private function parseOnlyOption(): array
    {
        $only = (string) $this->option('only');

        if ($only === '') {
            return ['models', 'settings', 'action_logs', 'imports'];
        }

        $valid = ['models', 'settings', 'action_logs', 'imports'];
        $requested = array_values(array_filter(array_map('trim', explode(',', $only))));
        $intersect = array_values(array_intersect($valid, $requested));

        if ($intersect === []) {
            $this->warn('No valid categories in --only. Valid: '.implode(', ', $valid).'. Falling back to all.');

            return $valid;
        }

        return $intersect;
    }
}
