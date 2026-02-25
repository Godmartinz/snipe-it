@extends('layouts/default')

{{-- Page title --}}
@section('title')
{{ trans('admin/manufacturers/table.asset_manufacturers') }} 
@parent
@stop

{{-- Page content --}}
@section('content')
    <x-container>
        <x-box>

            @if ($manufacturer_count == 0)

                    <form action="{{ route('manufacturers.seed') }}" method="POST">
                      {{ csrf_field() }}
                    <div class="callout callout-info">
                      <p>
                          {{ trans('general.seeding.manufacturers.prompt') }}
                        <button class="btn btn-sm btn-theme hidden-print" rel="noopener">
                          {{ trans('general.seeding.manufacturers.button') }}
                        </button>
                      </p>
                    </div>
                    </form>

              @else
                <x-slot:bulkactions>
                    <x-table.bulk-actions
                            name='manufacturer'
                            action_route="{{route('manufacturers.bulk.delete')}}"
                            model_name="manufacturer"
                    >
                        @can('delete', App\Models\Manufacturer::class)
                            <option>Delete</option>
                        @endcan
                    </x-table.bulk-actions>
                </x-slot:bulkactions>


                <x-table
                        name="manufacturer"
                        buttons="manufacturerButtons"
                        fixed_right_number="1"
                        fixed_number="1"
                        api_url="{{ route('api.manufacturers.index') }}"
                        :presenter="\App\Presenters\ManufacturerPresenter::dataTableLayout()"
                        export_filename="export-manufacturers-{{ date('Y-m-d') }}"
                />




            @endif
        </x-box>
    </x-container>
@stop

@section('moar_scripts')


  @include ('partials.bootstrap-table')
@stop
