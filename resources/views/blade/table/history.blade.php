@props([
    'route',
    'name' => 'default',
    'presenter' => \App\Presenters\HistoryPresenter::dataTableLayout(),
    'table_header' => trans('general.history'),
    'model' => null,
])


<!-- start history tab pane -->
@can('history', $model)
    <x-slot:table_header>
        {{ $table_header }}
    </x-slot:table_header>

    <x-table
        :$presenter
        show_advanced_search="false"
        api_url="{{ $route }}"
        export_filename="export-history-{{ date('Y-m-d') }}"
    />
@endcan
<!-- end assets tab pane -->