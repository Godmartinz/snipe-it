<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Category;
use App\Models\Company;
use App\Models\CustomField;
use App\Models\Labels\CustomLabels\CustomLabel;
use App\Models\Labels\CustomLabels\PreviewLabel;
use App\Models\Labels\CustomUserLabel;
use App\Models\Labels\CustomLabels\CustomSheetLabel;
use App\Models\Labels\DefaultLabel;
use App\Models\Labels\Label;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\User;
use App\View\Label as LabelView;
use Illuminate\Validation\ValidationException;

class LabelsController extends Controller
{
    /**
     * Returns the Label view with test data
     *
     * @author Grant Le Roux <grant.leroux+snipe-it@gmail.com>
     */
    public function show(string $labelName)
    {
        $labelName = str_replace('/', '\\', $labelName);

        if (str_starts_with($labelName, 'custom:')) {

            $id = (int)str_replace('custom:', '', $labelName);
            $customLabel = CustomUserLabel::find($id);

            if (!$customLabel) {
                $template = new DefaultLabel();
            } else {
                $baseLabel = CustomUserLabel::makeBaseLabel(
                    data_get($customLabel->config_snapshot, 'template', $customLabel->base_label)
                );

                $template = new PreviewLabel();

                if ($baseLabel) {
                    $template->seedFromTemplate($baseLabel);
                }

                $template->applyEditorConfig($customLabel->config_snapshot ?? []);
            }

        } else {

            $template = $labelName === 'DefaultLabel'
                ? new DefaultLabel()
                : Label::find($labelName);

        }


        $exampleAsset = new Asset;

        $exampleAsset->id = 999999;
        $exampleAsset->name = 'JEN-867-5309';
        $exampleAsset->asset_tag = '100001';
        $exampleAsset->serial = 'SN9876543210';
        $exampleAsset->asset_eol_date = '2025-01-01';
        $exampleAsset->order_number = '12345';
        $exampleAsset->purchase_date = '2023-01-01';
        $exampleAsset->status_id = 1;
        $exampleAsset->location_id = 1;

        $exampleAsset->company = new Company([
            'name' => trans('admin/labels/table.example_company'),
            'phone' => '1-555-555-5555',
            'email' => 'company@example.com',
            'logo' => 'label-preview-logo.png',
        ]);
        $exampleAsset->is_label_preview = true;

        $exampleAsset->setRelation('assignedTo', new User(['first_name' => 'Luke', 'last_name' => 'Skywalker']));
        $exampleAsset->defaultLoc = new Location(['name' => trans('admin/labels/table.example_defaultloc'), 'phone' => '1-555-555-5555']);
        $exampleAsset->location = new Location(['name' => trans('admin/labels/table.example_location'), 'phone' => '1-555-555-5555']);

        $exampleAsset->model = new AssetModel;
        $exampleAsset->model->id = 999999;
        $exampleAsset->model->name = trans('admin/labels/table.example_model');
        $exampleAsset->model->model_number = 'MDL5678';
        $exampleAsset->model->manufacturer = new Manufacturer;
        $exampleAsset->model->manufacturer->id = 999999;
        $exampleAsset->model->manufacturer->name = trans('admin/labels/table.example_manufacturer');
        $exampleAsset->model->manufacturer->support_email = 'support@test.com';
        $exampleAsset->model->manufacturer->support_phone = '1-555-555-5555';
        $exampleAsset->model->manufacturer->support_url = 'https://example.com';
        $exampleAsset->supplier = new Supplier(['name' => trans('admin/labels/table.example_company')]);
        $exampleAsset->model->category = new Category;
        $exampleAsset->model->category->id = 999999;
        $exampleAsset->model->category->name = trans('admin/labels/table.example_category');

        $customFieldColumns = CustomField::where('field_encrypted', '=', 0)->pluck('db_column');

        collect(explode(';', Setting::getSettings()->label2_fields))
            ->filter()
            ->each(function ($item) use ($customFieldColumns, $exampleAsset) {
                $pair = explode('=', $item);

                if (array_key_exists(1, $pair)) {
                    if ($customFieldColumns->contains($pair[1])) {
                        $exampleAsset->{$pair[1]} = "{{$pair[0]}}";
                    }
                }
            });

        $settings = Setting::getSettings();
        if (request()->has('settings')) {
            $overrides = request()->input('settings');
            foreach ($overrides as $key => $value) {
                $settings->$key = $value;
            }
        }

        return (new LabelView())
            ->with('assets', collect([$exampleAsset]))
            ->with('settings', $settings)
            ->with('template', $template)
            ->with('bulkedit', false)
            ->with('count', 0);

    }

    public function edit(CustomUserLabel $label)
    {
        return view('settings.label-edit', [
            'config' => $label->config_snapshot,
            'selectedLabel' => $label->base_label,
            'importedConfig' => null,
            'customLabel' => $label,
            'formMethod' => 'PUT',
            'formAction' => route('settings.labels.update', $label),
        ]);
    }

    public function update(Request $request, CustomUserLabel $label)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string'],
            'page' => ['required', 'array'],
            'grid' => ['required', 'array'],
            'label' => ['required', 'array'],
            'content' => ['required', 'array'],
            'supports' => ['required', 'array'],
        ]);

        $supports = collect($validated['supports'])
            ->map(function ($value, $key) {
                if ($key === 'fields') {
                    return (int)$value;
                }

                return in_array($key, ['asset_tag', 'barcode_1d', 'barcode_2d', 'logo', 'title'], true)
                    ? (bool)$value
                    : $value;
            })
            ->toArray();

        $castNumeric = function ($array) {
            return collect($array)->map(function ($value) {
                return is_numeric($value) ? (float)$value : $value;
            })->toArray();
        };

        $page = $castNumeric($validated['page']);
        $grid = $castNumeric($validated['grid']);
        $labelConfig = $castNumeric($validated['label']);
        $content = $castNumeric($validated['content']);

        $baseLabel = CustomUserLabel::makeBaseLabel($label->base_label);

        if (!$baseLabel) {
            return redirect()->back()->with('error', trans('admin/labels/labels.base_label_missing'));
        }


        $baseWorkingLabel = new PreviewLabel();
        $baseWorkingLabel->seedFromTemplate($baseLabel);

        $baseEditorConfig = $baseWorkingLabel->toEditorConfig();
        $baseConfig = $baseWorkingLabel->getEditorConfigSections();

        $submittedConfig = [
            'page' => $page,
            'grid' => $grid,
            'label' => $labelConfig,
            'content' => $content,
            'supports' => $supports,
        ];

        $mergedConfig = array_replace_recursive($baseConfig, $submittedConfig);

        $workingLabel = new PreviewLabel();
        $workingLabel->seedFromTemplate($baseLabel);
        $workingLabel->applyEditorConfig($mergedConfig);

        $finalConfig = $workingLabel->getEditorConfigSections();

        $configSnapshot = [
            'unit' => $baseEditorConfig['unit'] ?? 'mm',
            'template' => $label->base_label,
            'type' => $label->type ?? 'sheet',
            'name' => $validated['name'],
        ];

        foreach (['printable_area', 'label_printable_area'] as $key) {
            if (array_key_exists($key, $baseEditorConfig)) {
                $configSnapshot[$key] = $baseEditorConfig[$key];
            }
        }

        $configSnapshot = [
            ...$configSnapshot,
            ...$finalConfig,
        ];

        $overrides = CustomUserLabel::diffEditorConfig($finalConfig, $baseConfig);

        $label->update([
            'name' => $validated['name'],
            'overrides' => $overrides,
            'config_snapshot' => $configSnapshot,
        ]);

        return redirect()
            ->route('settings.labels.index')
            ->with('success', $label->name . ' updated successfully.');
    }
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'template' => ['nullable', 'string'],
            'type' => ['nullable', 'string'],
            'page' => ['required', 'array'],
            'grid' => ['required', 'array'],
            'label' => ['required', 'array'],
            'content' => ['required', 'array'],
            'supports' => ['required', 'array'],
        ]);

        $supports = collect($validated['supports'])
            ->map(function ($value, $key) {
                if ($key === 'fields') {
                    return (int)$value;
                }

                return in_array($key, ['asset_tag', 'barcode_1d', 'barcode_2d', 'logo', 'title'], true)
                    ? (bool)$value
                    : $value;
            })
            ->toArray();

        $castNumeric = function ($array) {
            return collect($array)->map(function ($value) {
                if (is_numeric($value)) {
                    return (float)$value;
                }
                return $value;
            })->toArray();
        };

        $page = $castNumeric($validated['page']);
        $grid = $castNumeric($validated['grid']);
        $labelConfig = $castNumeric($validated['label']);
        $content = $castNumeric($validated['content']);

        $baseLabel = CustomUserLabel::makeBaseLabel($validated['template'] ?? null);

        if (!$baseLabel) {
            return redirect()->back()->with('error', trans('admin/labels/labels.base_label_missing'));
        }

        // Loading both base and working label in the preview to ensure millimeter-based comparisons for overrides.
        $baseWorkingLabel = new PreviewLabel();
        $baseWorkingLabel->seedFromTemplate($baseLabel);
        $baseConfig = $baseWorkingLabel->getEditorConfigSections();

        $submittedConfig = [
            'page' => $page,
            'grid' => $grid,
            'label' => $labelConfig,
            'content' => $content,
            'supports' => $supports,
        ];

        $mergedConfig = array_replace_recursive($baseConfig, $submittedConfig);

        $workingLabel = new PreviewLabel();
        $workingLabel->seedFromTemplate($baseLabel);
        $workingLabel->applyEditorConfig($mergedConfig);

        $finalConfig = $workingLabel->getEditorConfigSections();

        $configSnapshot = [
            'unit' => 'mm',
            'template' => $validated['template'],
            'type' => $validated['type'] ?? 'sheet',
            'name' => $validated['name'],
            ...$finalConfig,
        ];

        $overrides = CustomUserLabel::diffEditorConfig($finalConfig, $baseConfig);

        $customLabel = CustomUserLabel::create([
            'name' => $validated['name'],
            'base_label' => $validated['template'] ?? null,
            'type' => $validated['type'] ?? 'sheet',
            'overrides' => $overrides,
            'config_snapshot' => $configSnapshot,
            'is_default' => false,
        ]);

        return app(SettingsController::class)->getLabels()
            ->with('success', $customLabel->name . ' created successfully.');
    }

    public function create(Request $request)
    {
        $customLabelId = $request->get('custom_label_id');

        if ($customLabelId) {
            //creating a custom label from another custom label
            $customLabel = CustomUserLabel::findOrFail($customLabelId);

            $config = $customLabel->config_snapshot;
            $config['name'] = 'Copy of ' . $customLabel->name;

            return view('settings.label-edit', [
                'config' => $config,
                'selectedLabel' => $customLabel->base_label,
                'importedConfig' => null,
                'customLabel' => null,
                'formMethod' => 'POST',
                'formAction' => route('settings.labels.store'),
            ]);
        }
        $selectedLabel = $request->get('label');

        if ($selectedLabel) {
            $selectedLabel = str_replace('/', '\\', $selectedLabel);
        }
        $importedConfig = session('imported_label_config');

        if ($importedConfig) {
            $selectedLabel = data_get($importedConfig, 'template', $selectedLabel);
        }
        try {
            $template = $selectedLabel
                ? Label::find(str_replace('/', '\\', $selectedLabel))
                : new DefaultLabel();
        } catch (\Throwable $e) {
            $template = new DefaultLabel();
            $selectedLabel = "DefaultLabel";
        }

        $label = (new PreviewLabel())->seedFromTemplate($template);
        $config = $importedConfig ?: $label->toEditorConfig();

        return view('settings.label-edit', [
            'config' => $config,
            'selectedLabel' => $selectedLabel,
            'importedConfig' => $importedConfig,
            'customLabel' => null,
            'formMethod' => 'POST',
            'formAction' => route('settings.labels.store'),
        ]);
    }

    public function destroy(CustomUserLabel $label)
    {
        $labelName = $label->name;
        if ($label->is_default) {
            return response()->json([
                'status' => 'error',
                'message' => trans('admin/labels/labels.default_label_cannot_be_deleted'),
            ], 403);
        }

        $label->delete();

        return redirect()
            ->route('settings.labels.index')
            ->with('success', $labelName . ' ' . trans('admin/labels/labels.label_deleted_successfully'));
    }

    public function customLabelPreview(Request $request, string $labelName)
    {
        $labelName = str_replace('/', '\\', $labelName);

        $baseTemplate = $labelName === 'DefaultLabel'
            ? new DefaultLabel()
            : Label::find($labelName);

        $editorConfig = [
            'page' => $request->input('page', []),
            'grid' => $request->input('grid', []),
            'label' => $request->input('label', []),
            'content' => $request->input('content', []),
            'supports' => $request->input('supports', []),
        ];

        $template = new \App\Models\Labels\CustomLabels\PreviewLabel();

        if (method_exists($template, 'seedFromTemplate')) {
            $template->seedFromTemplate($baseTemplate);
        }

        if (method_exists($template, 'applyEditorConfig')) {
            $template->applyEditorConfig($editorConfig);
        }

        $exampleAsset = new Asset;
        $exampleAsset->id = 999999;
        $exampleAsset->name = 'JEN-867-5309';
        $exampleAsset->asset_tag = '100001';
        $exampleAsset->serial = 'SN9876543210';
        $exampleAsset->asset_eol_date = '2025-01-01';
        $exampleAsset->order_number = '12345';
        $exampleAsset->purchase_date = '2023-01-01';
        $exampleAsset->status_id = 1;
        $exampleAsset->location_id = 1;

        $exampleAsset->company = new Company([
            'name' => trans('admin/labels/table.example_company'),
            'phone' => '1-555-555-5555',
            'email' => 'company@example.com',
        ]);

        $exampleAsset->setRelation('assignedTo', new User([
            'first_name' => 'Luke',
            'last_name' => 'Skywalker',
        ]));

        $exampleAsset->defaultLoc = new Location([
            'name' => trans('admin/labels/table.example_defaultloc'),
            'phone' => '1-555-555-5555',
        ]);

        $exampleAsset->location = new Location([
            'name' => trans('admin/labels/table.example_location'),
            'phone' => '1-555-555-5555',
        ]);

        $exampleAsset->model = new AssetModel;
        $exampleAsset->model->id = 999999;
        $exampleAsset->model->name = trans('admin/labels/table.example_model');
        $exampleAsset->model->model_number = 'MDL5678';

        $exampleAsset->model->manufacturer = new Manufacturer;
        $exampleAsset->model->manufacturer->id = 999999;
        $exampleAsset->model->manufacturer->name = trans('admin/labels/table.example_manufacturer');
        $exampleAsset->model->manufacturer->support_email = 'support@test.com';
        $exampleAsset->model->manufacturer->support_phone = '1-555-555-5555';
        $exampleAsset->model->manufacturer->support_url = 'https://example.com';

        $exampleAsset->supplier = new Supplier([
            'name' => trans('admin/labels/table.example_company'),
        ]);

        $exampleAsset->model->category = new Category;
        $exampleAsset->model->category->id = 999999;
        $exampleAsset->model->category->name = trans('admin/labels/table.example_category');

        $exampleAsset->is_label_preview = true;

        $customFieldColumns = CustomField::where('field_encrypted', 0)->pluck('db_column');

        collect(explode(';', Setting::getSettings()->label2_fields))
            ->filter()
            ->each(function ($item) use ($customFieldColumns, $exampleAsset) {
                $pair = explode('=', $item);

                if (isset($pair[0], $pair[1]) && $customFieldColumns->contains($pair[1])) {
                    $exampleAsset->{$pair[1]} = "{{$pair[0]}}";
                }
            });

        $settings = Setting::getSettings();
        $settingOverrides = [
            'label2_title',
            'label2_asset_logo',
            'label2_fields',
            'label2_1d_type',
            'label2_2d_type',
            'label2_2d_target',
            'label2_2d_prefix',
            'label2_empty_row_count',
        ];

        foreach ($settingOverrides as $key) {
            if ($request->has($key)) {
                $settings->{$key} = $request->input($key);
            }
        }

        return (new LabelView)
            ->with('assets', collect([$exampleAsset]))
            ->with('settings', $settings)
            ->with('template', $template)
            ->with('bulkedit', false)
            ->with('count', 0);
    }

    public function import(Request $request)
    {
        $validated = $request->validate([
            'config_snapshot' => ['required', 'json'],
        ]);

        $config = json_decode($validated['config_snapshot'], true);

        if (!is_array($config)) {
            throw ValidationException::withMessages([
                'config_snapshot' => 'The imported label config must be a JSON object.',
            ]);
        }

        foreach (['template', 'type', 'name'] as $requiredKey) {
            if (!array_key_exists($requiredKey, $config)) {
                throw ValidationException::withMessages([
                    'config_snapshot' => "The imported label config is missing [{$requiredKey}].",
                ]);
            }
        }

        return redirect()
            ->route('settings.labels.create')
            ->with('imported_label_config', $config);
    }

}
