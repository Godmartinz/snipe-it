@props([
    'type',
    'template',
])

{{-- Right-column "Saved Templates" panel shared by the three custom
     report pages (asset / component / consumable).
     Report-type-specific bits (Livewire component type, the route
     name for the show-form guard) are derived from $type. --}}

@php
    $showFormRoutes = match ($type) {
        'asset' => ['reports/custom'],
        'component' => ['reports/custom', 'reports.custom.component'],
        'consumable' => ['reports/custom', 'reports.custom.consumable'],
    };
@endphp

@if (request()->routeIs('report-templates.edit'))
    {{-- Edit mode: template name + is_shared are the only fields the
         edit page adds on top of the report-config form. Rendered here
         in the sidebar (rather than shoehorned into the main form's
         box header, which crowded the two-line header). form="custom-report-form"
         associates these inputs with the report form even though they
         live outside its DOM subtree so they still POST as part of it. --}}
    <div class="box box-default">
        <div class="box-header with-border">
            <h2 class="box-title">
                {{ trans('admin/reports/general.template_name') }}
            </h2>
        </div>
        <div class="box-body">
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name" class="sr-only">
                    {{ trans('admin/reports/general.template_name') }}
                </label>
                <input
                    class="form-control"
                    name="name"
                    type="text"
                    id="name"
                    value="{{ $template->name }}"
                    form="custom-report-form"
                    required
                >
                <x-form.error name="name"/>
            </div>

            @if ($template->created_by == auth()->id())
                <label class="form-control">
                    <input
                        type="checkbox"
                        name="is_shared"
                        value="1"
                        form="custom-report-form"
                        @checked($template->is_shared)
                    />
                    {{ trans('admin/reports/general.share_template') }}
                </label>
            @endif

            {{-- The main form's submit button is buried under the report-
                 configuration box further down the page, which makes it
                 unclear that a name / is_shared change here needs the
                 main form to be submitted. Explicit Save here as an
                 anchor so the user can clearly see what they're saving. --}}
            <button type="submit" form="custom-report-form" class="btn btn-success btn-block" style="margin-top: 10px;">
                <x-icon type="checkmark" class="icon-white"/>
                {{ trans('general.save') }}
            </button>
        </div>
    </div>
@endif

@if (! request()->routeIs('report-templates.edit'))
    <livewire:report-template-select :type="$type"/>

    <div class="row">
        <div class="col-md-12">

            <div style="margin-bottom: 5px;">
                @if ($template->name)
                    @if ($template->created_by == auth()->id())
                        <span class="text-center">{!! ($template->is_shared ? '<i class="fa fa-users"></i>'.' '.trans('admin/reports/general.template_shared_with_others') : '<i class="fa-solid fa-user-xmark"></i>'.' '.trans('admin/reports/general.template_not_shared')) !!}</span>
                    @else
                        <span class="text-center">{!! ($template->is_shared ? '<i class="fa fa-users"></i>'.' '.trans('admin/reports/general.template_shared') : '<i class="fa-solid fa-user-xmark"></i>'.' '.trans('admin/reports/general.template_not_shared')) !!}</span>
                    @endif
                @endif
            </div>

            @if ($template->created_by == auth()->id())
                @if (request()->routeIs('report-templates.show'))
                    <a
                        href="{{ route('report-templates.edit', $template) }}"
                        class="btn btn-sm btn-warning btn-social btn-block"
                        data-tooltip="true"
                        title="{{ trans('admin/reports/general.update_template') }}"
                        style="margin-bottom: 5px;"
                    >
                        <x-icon type="edit"/>
                        {{ trans('general.update') }}
                    </a>
                    <span data-tooltip="true" title="{{ trans('general.delete') }}">
                        {{-- .delete-asset is the snipeit.js delete-confirm modal
                             trigger class (legacy name; not asset-specific). --}}
                        <a
                            href="#"
                            class="btn btn-sm btn-danger btn-social btn-block delete-asset"
                            data-toggle="modal"
                            data-title="{{ trans('general.delete') }}"
                            data-content="{{ trans('general.delete_confirm', ['item' => $template->name]) }}"
                            data-target="#dataConfirmModal"
                            type="button"
                        >
                            <x-icon type="delete"/>
                            {{ trans('general.delete') }}
                        </a>
                    </span>
                @endif
            @endif
        </div>
    </div>
@endif

@if (request()->routeIs(...$showFormRoutes))
    <hr>
    <div class="form-group">
        {{-- data-report-save-template hooks the shared submit handler
             in snipeit.js (`form[data-report-save-template]`). That
             handler forwards the template name + type into the main
             custom-report form and submits it to templates.store. --}}
        <form
            method="post"
            id="savetemplateform"
            data-report-save-template
            data-report-type="{{ $type }}"
            data-report-form="#custom-report-form"
            data-store-url="{{ route('report-templates.store') }}"
            action="{{ route('report-templates.store') }}"
        >
            @csrf
            <input type="hidden" name="options">
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name">{{ trans('admin/reports/general.template_name') }}</label>
                <input
                    class="form-control"
                    placeholder=""
                    name="name"
                    type="text"
                    id="name"
                    value="{{ $template->name }}"
                    required
                >
                <x-form.error name="name"/>
            </div>
            <button class="btn btn-primary" style="width: 100%">
                {{ trans('admin/reports/general.save_template') }}
            </button>
        </form>
    </div>
    <div class="box box-success">
        <div class="box-header with-border">
            <h4>{{ trans('admin/reports/message.about_templates') }}</h4>
        </div>
        <div class="box-body">
            <p>{!! trans('admin/reports/message.saving_templates_description') !!}</p>
        </div>
    </div>
@endif
