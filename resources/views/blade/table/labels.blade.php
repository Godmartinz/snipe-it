@props([
    'route' => route('api.labels.index'),
    'columns' => \App\Presenters\LabelPresenter::dataTableLayout(),
    'tableId' => 'label2TemplateTable',
    'cookieIdTable' => 'label2TemplateTable',
    'selectItemName' => 'label2_template',
    'idField' => 'name',
    'sortName' => 'name',
    'sortOrder' => 'asc',
])

<table
        data-columns="{{ $columns }}"
        data-cookie="true"
        data-cookie-id-table="{{ $cookieIdTable }}"
        data-id-table="{{ $tableId }}"
        data-select-item-name="{{ $selectItemName }}"
        data-id-field="{{ $idField }}"
        data-side-pagination="server"
        data-sort-name="{{ $sortName }}"
        data-sort-order="{{ $sortOrder }}"
        data-url="{{ $route }}"
        id="{{ $tableId }}"
        buttons="labelButtons"
        class="table table-striped snipe-table"
></table>