<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Category;
use App\Models\Company;
use App\Models\CustomField;
use App\Models\Labels\CustomLabels\PreviewLabel;
use App\Models\Labels\CustomUserLabel;
use App\Models\Labels\DefaultLabel;
use App\Models\Labels\Label;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Setting;
use App\Models\Supplier;
use App\Models\User;
use App\View\Label as LabelView;
use Illuminate\Http\Request;
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
                $template = new DefaultLabel;
            } else {
                $baseLabel = CustomUserLabel::makeBaseLabel(
                    data_get($customLabel->config_snapshot, 'template', $customLabel->base_label)
                );

                $template = new PreviewLabel;

                if ($baseLabel) {
                    $template->seedFromTemplate($baseLabel);
                }

                $template->applyEditorConfig($customLabel->config_snapshot ?? []);
            }

        } else {

            $template = $labelName === 'DefaultLabel'
                ? new DefaultLabel
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

        return (new LabelView)
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

        $baseWorkingLabel = new PreviewLabel;
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

        $workingLabel = new PreviewLabel;
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
        $baseWorkingLabel = new PreviewLabel;
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

        $workingLabel = new PreviewLabel;
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
            // creating a custom label from another custom label
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
                : new DefaultLabel;
        } catch (\Throwable $e) {
            $template = new DefaultLabel;
            $selectedLabel = 'DefaultLabel';
        }

        $label = (new PreviewLabel)->seedFromTemplate($template);
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
            ? new DefaultLabel
            : Label::find($labelName);

        $editorConfig = [
            'page' => $request->input('page', []),
            'grid' => $request->input('grid', []),
            'label' => $request->input('label', []),
            'content' => $request->input('content', []),
            'supports' => $request->input('supports', []),
        ];

        $template = new PreviewLabel;

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
        $request->validate([
            'import_method' => ['required', 'in:json,text'],
            'config_file' => ['required_if:import_method,json', 'file', 'mimes:json,txt'],
            'config_snapshot' => ['required_if:import_method,text', 'nullable', 'string'],
        ]);

        $rawJson = $request->input('import_method') === 'json'
            ? file_get_contents($request->file('config_file')->getRealPath())
            : $request->input('config_snapshot');

        $config = json_decode($rawJson, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($config)) {
            return back()
                ->withErrors(['config_snapshot' => 'The imported label config must be valid JSON.'])
                ->withInput();
        }

        $configValidator = validator(
            $config,
            [
                'unit' => ['required', 'string', 'in:mm'],
                'template' => ['required', 'string'],
                'type' => ['required', 'string', 'in:sheet'],
                'name' => ['required', 'string'],

                /*
                |--------------------------------------------------------------------------
                | Page
                |--------------------------------------------------------------------------
                */
                'page' => ['required', 'array'],
                'page.width' => ['required', 'numeric'],
                'page.height' => ['required', 'numeric'],
                'page.margin_top' => ['required', 'numeric'],
                'page.margin_right' => ['required', 'numeric'],
                'page.margin_bottom' => ['required', 'numeric'],
                'page.margin_left' => ['required', 'numeric'],

                /*
                |--------------------------------------------------------------------------
                | Grid
                |--------------------------------------------------------------------------
                */
                'grid' => ['required', 'array'],
                'grid.columns' => ['required', 'integer'],
                'grid.rows' => ['required', 'integer'],
                'grid.column_spacing' => ['required', 'numeric'],
                'grid.row_spacing' => ['required', 'numeric'],

                /*
                |--------------------------------------------------------------------------
                | Printable Area
                |--------------------------------------------------------------------------
                */
                'printable_area' => ['required', 'array'],
                'printable_area.x1' => ['required', 'numeric'],
                'printable_area.y1' => ['required', 'numeric'],
                'printable_area.x2' => ['required', 'numeric'],
                'printable_area.y2' => ['required', 'numeric'],
                'printable_area.width' => ['required', 'numeric'],
                'printable_area.height' => ['required', 'numeric'],

                /*
                |--------------------------------------------------------------------------
                | Label
                |--------------------------------------------------------------------------
                */
                'label' => ['required', 'array'],
                'label.width' => ['required', 'numeric'],
                'label.height' => ['required', 'numeric'],
                'label.border' => ['required', 'numeric'],
                'label.padding_top' => ['required', 'numeric'],
                'label.padding_right' => ['required', 'numeric'],
                'label.padding_bottom' => ['required', 'numeric'],
                'label.padding_left' => ['required', 'numeric'],

                /*
                |--------------------------------------------------------------------------
                | Content
                |--------------------------------------------------------------------------
                */
                'content' => ['required', 'array'],

                'content.barcode_size' => ['required', 'numeric'],
                'content.barcode_margin' => ['required', 'numeric'],

                'content.barcode2D_h_align' => ['required', 'string', 'in:L,C,R'],
                'content.barcode2D_v_align' => ['required', 'string', 'in:T,C,B'],

                'content.tag_alignment' => ['required', 'string', 'in:L,C,R'],

                'content.barcode_2d_size' => ['required', 'numeric'],

                'content.logo_max_width' => ['required', 'numeric'],
                'content.logo_margin' => ['required', 'numeric'],

                'content.logo_h_align' => ['required', 'string', 'in:L,C,R'],
                'content.logo_v_align' => ['required', 'string', 'in:T,C,B'],

                'content.tag_font_size' => ['required', 'numeric'],
                'content.tag_offset_x' => ['required', 'numeric'],
                'content.tag_offset_y' => ['required', 'numeric'],

                'content.title_font_size' => ['required', 'numeric'],
                'content.title_margin' => ['required', 'numeric'],
                'content.title_offset_x' => ['required', 'numeric'],

                'content.field_label_font_size' => ['required', 'numeric'],
                'content.field_label_margin' => ['required', 'numeric'],

                'content.field_value_font_size' => ['required', 'numeric'],
                'content.field_value_margin' => ['required', 'numeric'],

                'content.text_area_width' => ['nullable', 'numeric'],
                'content.text_area_height' => ['nullable', 'numeric'],

                /*
                |--------------------------------------------------------------------------
                | Supports
                |--------------------------------------------------------------------------
                */
                'supports' => ['required', 'array'],
                'supports.asset_tag' => ['required', 'boolean'],
                'supports.barcode_1d' => ['required', 'boolean'],
                'supports.barcode_2d' => ['required', 'boolean'],
                'supports.fields' => ['required', 'integer'],
                'supports.logo' => ['required', 'boolean'],
                'supports.title' => ['required', 'boolean'],
            ],
            [],
            [
                'page.width' => 'page width',
                'page.height' => 'page height',
                'page.margin_top' => 'page top margin',
                'page.margin_right' => 'page right margin',
                'page.margin_bottom' => 'page bottom margin',
                'page.margin_left' => 'page left margin',

                'grid.columns' => 'grid columns',
                'grid.rows' => 'grid rows',
                'grid.column_spacing' => 'grid column spacing',
                'grid.row_spacing' => 'grid row spacing',

                'printable_area.x1' => 'printable area x1',
                'printable_area.y1' => 'printable area y1',
                'printable_area.x2' => 'printable area x2',
                'printable_area.y2' => 'printable area y2',
                'printable_area.width' => 'printable area width',
                'printable_area.height' => 'printable area height',

                'label.width' => 'label width',
                'label.height' => 'label height',
                'label.border' => 'label border',
                'label.padding_top' => 'label top padding',
                'label.padding_right' => 'label right padding',
                'label.padding_bottom' => 'label bottom padding',
                'label.padding_left' => 'label left padding',

                'content.barcode_size' => 'barcode size',
                'content.barcode_margin' => 'barcode margin',
                'content.barcode2D_h_align' => '2D barcode horizontal alignment',
                'content.barcode2D_v_align' => '2D barcode vertical alignment',
                'content.tag_alignment' => 'tag alignment',
                'content.barcode_2d_size' => '2D barcode size',
                'content.logo_max_width' => 'logo max width',
                'content.logo_margin' => 'logo margin',
                'content.logo_h_align' => 'logo horizontal alignment',
                'content.logo_v_align' => 'logo vertical alignment',
                'content.tag_font_size' => 'tag font size',
                'content.tag_offset_x' => 'tag horizontal offset',
                'content.tag_offset_y' => 'tag vertical offset',
                'content.title_font_size' => 'title font size',
                'content.title_margin' => 'title margin',
                'content.title_offset_x' => 'title horizontal offset',
                'content.field_label_font_size' => 'field label font size',
                'content.field_label_margin' => 'field label margin',
                'content.field_value_font_size' => 'field value font size',
                'content.field_value_margin' => 'field value margin',
                'content.text_area_width' => 'text area width',
                'content.text_area_height' => 'text area height',

                'supports.asset_tag' => 'supports asset tag',
                'supports.barcode_1d' => 'supports 1D barcode',
                'supports.barcode_2d' => 'supports 2D barcode',
                'supports.fields' => 'supports fields',
                'supports.logo' => 'supports logo',
                'supports.title' => 'supports title',
            ]
        );

        if ($configValidator->fails()) {
            return back()
                ->withErrors([
                    'config_snapshot' => $configValidator->errors()->all(),
                ])
                ->withInput();
        }

        return redirect()
            ->route('settings.labels.create')
            ->with('imported_label_config', $config);
    }
}
