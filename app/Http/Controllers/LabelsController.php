<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Category;
use App\Models\Company;
use App\Models\CustomField;
use App\Models\Labels\DefaultLabel;
use App\Models\Labels\Label;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\User;
use App\View\Label as LabelView;

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
        $template = Label::find($labelName);

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

    public function edit(Request $request)
    {
        $selectedLabel = $request->get('label');

        if ($selectedLabel) {
            $selectedLabel = str_replace('/', '\\', $selectedLabel);
        }

        try {
            $label = $selectedLabel
                ? Label::find($selectedLabel)
                : new \App\Models\Labels\DefaultLabel();
        } catch (\Throwable $e) {
            $label = new \App\Models\Labels\DefaultLabel();
            $selectedLabel = null;
        }

        return view('settings.label-edit', [
            'config' => $label->toEditorConfig(),
            'selectedLabel' => $label->getName(),
            'labels' => Label::find(),
        ]);
    }

    public function customLabelPreview(Request $request, string $labelName)
    {
        $labelName = str_replace('/', '\\', $labelName);

        $template = $labelName === 'DefaultLabel'
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
}
