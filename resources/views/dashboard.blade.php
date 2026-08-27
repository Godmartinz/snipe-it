@extends('layouts/default')
{{-- Page title --}}
@section('title')
{{ trans('general.dashboard') }}
@parent
@stop


{{-- Page content --}}
@section('content')

@if ($snipeSettings->dashboard_message!='')
<div class="row">
    <div class="col-md-12">
        <div class="box box-default">
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        {!!  Helper::parseEscapedMarkedown($snipeSettings->dashboard_message)  !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row">

    <!-- panel -->
    <div class="col-lg-2 col-xs-6">
        <a href="{{ route('hardware.index') }}">
            <!-- small hardware box -->
            <div class="dashboard small-box bg-teal">
                <div class="inner">
                    <h3>{{ number_format(\App\Models\Asset::AssetsForShow()->count()) }}</h3>
                    <p>{{ trans('general.assets') }}</p>
                </div>
                <div class="icon" aria-hidden="true">
                    <x-icon type="assets" />
                </div>
                <span class="small-box-footer">
                    {{ trans('general.view_all') }}
                    <x-icon type="arrow-circle-right" />
                </span>
            </div>
        </a>
    </div><!-- ./col -->

    <div class="col-lg-2 col-xs-6">
        <a href="{{ route('licenses.index') }}" aria-hidden="true">
            <!-- small license box -->
            <div class="dashboard small-box bg-maroon">
                <div class="inner">
                    <h3>{{ number_format($counts['license']) }}</h3>
                    <p>{{ trans('general.licenses') }}</p>
                </div>
                <div class="icon" aria-hidden="true">
                    <x-icon type="licenses" />
                </div>
                <span class="small-box-footer">
                    {{ trans('general.view_all') }}
                    <x-icon type="arrow-circle-right" />
                </span>
            </div>
        </a>
    </div><!-- ./col -->


    <div class="col-lg-2 col-xs-6">
    <!-- small accessories box -->
        <a href="{{ route('accessories.index') }}">
            <div class="dashboard small-box bg-orange">
                <div class="inner">
                    <h3> {{ number_format($counts['accessory']) }}</h3>
                    <p>{{ trans('general.accessories') }}</p>
                </div>
                <div class="icon" aria-hidden="true">
                    <x-icon type="accessories" />
                </div>
                <span class="small-box-footer">
                    {{ trans('general.view_all') }}
                <x-icon type="arrow-circle-right" />
                </span>
            </div>
        </a>
    </div><!-- ./col -->

    <div class="col-lg-2 col-xs-6">
    <!-- small consumables box -->
        <a href="{{ route('consumables.index') }}">
            <div class="dashboard small-box bg-purple">
                <div class="inner">
                    <h3> {{ number_format($counts['consumable']) }}</h3>
                    <p>{{ trans('general.consumables') }}</p>
                </div>
                <div class="icon" aria-hidden="true">
                    <x-icon type="consumables" />
                </div>
                <span class="small-box-footer">
                    {{ trans('general.view_all') }}
                    <x-icon type="arrow-circle-right" />
                </span>
            </div>
        </a>
    </div><!-- ./col -->

    <div class="col-lg-2 col-xs-6">
        <!-- small components box -->
        <a href="{{ route('components.index') }}">
            <div class="dashboard small-box bg-yellow">
                <div class="inner">
                    <h3>{{ number_format($counts['component']) }}</h3>
                    <p>{{ trans('general.components') }}</p>
                </div>
                <div class="icon" aria-hidden="true">
                    <x-icon type="components" />
                </div>
                <span class="small-box-footer">
                    {{ trans('general.view_all') }}
                    <x-icon type="arrow-circle-right" />
                </span>
            </div>
        </a>
    </div><!-- ./col -->

    <div class="col-lg-2 col-xs-6">
        <!-- small users box -->
        <a href="{{ route('users.index') }}">
            <div class="dashboard small-box bg-light-blue">
                <div class="inner">
                    <h3>{{ number_format($counts['user']) }}</h3>
                    <p>{{ trans('general.people') }}</p>
                </div>
                <div class="icon" aria-hidden="true">
                    <x-icon type="users" />
                </div>
                <span class="small-box-footer">
                    {{ trans('general.view_all') }}
                    <x-icon type="arrow-circle-right" />
                </span>
            </div>
        </a>
    </div><!-- ./col -->
</div>

@if ($counts['grand_total'] == 0)

    <div class="row">

        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h2 class="box-title">{{ trans('general.dashboard_info') }}</h2>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="progress">
                                <div class="progress-bar progress-bar-yellow" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                                    <span class="sr-only">{{ trans('general.60_percent_warning') }}</span>
                                </div>
                            </div>


                            <p><strong>{{ trans('general.dashboard_empty') }}</strong></p>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            @can('create', \App\Models\Asset::class)
                            <a class="btn bg-teal" style="width: 100%" href="{{ route('hardware.create') }}">{{ trans('general.new_asset') }}</a>
                            @endcan
                        </div>
                        <div class="col-md-2">
                            @can('create', \App\Models\License::class)
                                <a class="btn bg-maroon" style="width: 100%" href="{{ route('licenses.create') }}">{{ trans('general.new_license') }}</a>
                            @endcan
                        </div>
                        <div class="col-md-2">
                            @can('create', \App\Models\Accessory::class)
                                <a class="btn bg-orange" style="width: 100%" href="{{ route('accessories.create') }}">{{ trans('general.new_accessory') }}</a>
                            @endcan
                        </div>
                        <div class="col-md-2">
                            @can('create', \App\Models\Consumable::class)
                                <a class="btn bg-purple" style="width: 100%" href="{{ route('consumables.create') }}">{{ trans('general.new_consumable') }}</a>
                            @endcan
                        </div>
                        <div class="col-md-2">
                            @can('create', \App\Models\Component::class)
                                <a class="btn bg-yellow" style="width: 100%" href="{{ route('components.create') }}">{{ trans('general.new_component') }}</a>
                            @endcan
                        </div>
                        <div class="col-md-2">
                            @can('create', \App\Models\User::class)
                                <a class="btn bg-light-blue" style="width: 100%" href="{{ route('users.create') }}">{{ trans('general.new_user') }}</a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@else

    <!-- recent activity + today calendar -->
    <div class="row dashboard-row-eq dashboard-row-compact">
  <div class="col-md-8">
    <div class="box box-default">
      <div class="box-header with-border">
        <h2 class="box-title">{{ trans('general.recent_activity') }}</h2>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" aria-hidden="true">
                <x-icon type="minus" />
                <span class="sr-only">{{ trans('general.collapse') }}</span>
            </button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">

                <table
                    data-cookie-id-table="dashActivityReport"
                    data-pagination="false"
                    data-side-pagination="server"
                    data-id-table="dashActivityReport"
                    data-sort-order="desc"
                    data-show-columns="false"
                    data-sort-name="created_at"
                    data-sticky-header="false"
                    data-empty-message="{{ trans('general.dashboard_activity_empty') }}"
                    id="dashActivityReport"
                    class="table table-striped snipe-table"
                    data-url="{{ route('api.activity.index', ['limit' => 25]) }}">
                    <thead>
                    <tr>
                        <th scope="col" data-field="icon" data-visible="true" style="width: 40px;" class="hidden-xs" data-formatter="iconFormatter"><span  class="sr-only">{{ trans('admin/hardware/table.icon') }}</span></th>
                        <th scope="col" class="col-sm-3" data-visible="true" data-field="created_at" data-formatter="dateDisplayFormatter">{{ trans('general.date') }}</th>
                        <th scope="col" class="col-sm-2" data-visible="true" data-field="admin" data-formatter="usersLinkObjFormatter">{{ trans('general.created_by') }}</th>
                        <th scope="col" class="col-sm-2" data-visible="true" data-field="action_type">{{ trans('general.action') }}</th>
                        <th scope="col" class="col-sm-3" data-visible="true" data-field="item" data-formatter="polymorphicItemFormatter">{{ trans('general.item') }}</th>
                        <th scope="col" class="col-sm-2" data-visible="true" data-field="target" data-formatter="polymorphicItemFormatter">{{ trans('general.target') }}</th>
                    </tr>
                    </thead>
                </table>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- ./box-body -->
        <div class="box-footer text-center">
            <a href="{{ route('reports.activity') }}" class="btn btn-theme btn-sm" style="width: 100%">{{ trans('general.viewall') }}</a>
        </div>
    </div><!-- /.box -->
  </div>

        {{-- Today widget: agenda-style list of everything happening
             today across every HasCalendarEvents source. Uses the
             same reusable snipeit-calendar bundle as the main
             /calendar page, initialized with listDay (agenda list
             for a single day). --}}
        <div class="col-md-4">
            @can('view', \App\Models\Asset::class)
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h2 class="box-title">
                            <a href="{{ route('calendar.index') }}">{{ trans('general.calendar_upcoming') }}</a>
                        </h2>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" aria-hidden="true">
                                <x-icon type="minus"/>
                                <span class="sr-only">{{ trans('general.collapse') }}</span>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div id="dashboard-today-calendar"></div>
                    </div>
                    {{-- Matches the "View all" btn-theme footers on the
                         sibling dashboard panels. Shown only when the
                         widget's onFetchMeta reports the API hit its
                         row cap. --}}
                    <div id="dashboard-today-more" class="box-footer text-center" style="display:none;">
                        <a href="{{ route('calendar.index') }}" class="btn btn-theme btn-sm" style="width: 100%" id="dashboard-today-more-link"></a>
                    </div>
                </div>
            @endcan
        </div>
    </div> <!--/row-->

    {{-- Row: pie chart + low-stock + overdue/pending. All three at
         col-md-4 so the row lines up cleanly regardless of box height,
         and each widget serves a distinct "what needs attention" role
         for the admin scanning the dashboard. `dashboard-row-compact`
         caps the box-body heights on this row so the pie/list/table
         trio doesn't dwarf the rest of the dashboard when Needs
         Attention or Low Stock grow long. --}}
    <div class="row dashboard-row-eq dashboard-row-compact">
        <div class="col-md-4">
        <div class="box box-default">
            <div class="box-header with-border">
                <h2 class="box-title">
                    {{ (\App\Models\Setting::getSettings()->dash_chart_type == 'name') ? trans('general.assets_by_status') : trans('general.assets_by_status_type') }}
                </h2>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" aria-hidden="true">
                        <x-icon type="minus" />
                        <span class="sr-only">{{ trans('general.collapse') }}</span>
                    </button>
                </div>
            </div>
            {{-- Fixed-height wrapper with position:relative is the
                 stable Chart.js responsive pattern: canvas inside has
                 no dimensions of its own and the responsive resize
                 fills the wrapper. Height:100% here caused a resize
                 loop against the flex-stretched box-body (canvas
                 grows → box grows → canvas resizes). Pinning to 300px
                 gives the pie enough room without dominating the row
                 and stops the growth loop. --}}
            <div class="box-body dashboard-chart-body">
                <div class="chart-responsive" style="position: relative; height: 300px;">
                    <canvas id="statusPieChart"></canvas>
                </div>
            </div>
        </div>
        </div>

        <div class="col-md-4">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h2 class="box-title">
                        {{ trans('general.dashboard_low_stock') }}
                        {{-- Info icon: explains the formula the alert
                             uses (remaining < min + alert_threshold)
                             so the widget doesn't look arbitrary to
                             someone who hasn't set a min_amt or
                             threshold. No link on the icon since
                             non-superuser admins see the dashboard
                             but can't reach the alert-threshold setting. --}}
                        <span data-tooltip="true"
                              title="{{ trans('general.dashboard_low_stock_help', ['threshold' => (int) $snipeSettings->alert_threshold]) }}"
                              class="text-muted"
                              style="cursor: help;">
                            <x-icon type="more-info" class="fa-fw"/>
                            <span class="sr-only">{{ trans('general.dashboard_low_stock_help', ['threshold' => (int) $snipeSettings->alert_threshold]) }}</span>
                        </span>
                    </h2>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" aria-hidden="true">
                            <x-icon type="minus"/>
                            <span class="sr-only">{{ trans('general.collapse') }}</span>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    {{-- Bs-table backed by /api/v1/low-stock which delegates
                         to Helper::checkLowInventory so this widget and the
                         top-nav alert bell can't drift. polymorphicItemFormatter
                         handles the per-type icon + drilldown link, and the
                         shared adjust-quantity button hangs off each row's
                         available_actions.adjust_quantity via the generic
                         actions column. --}}
                    <table
                        data-cookie-id-table="dashLowStock"
                        data-pagination="false"
                        data-side-pagination="server"
                        data-id-table="dashLowStock"
                        data-sticky-header="false"
                        data-search="false"
                        data-show-columns="false"
                        data-show-columns-toggle-all="false"
                        data-show-fullscreen="false"
                        data-show-print="false"
                        data-show-refresh="false"
                        data-show-export="false"
                        data-empty-message="{{ trans('general.dashboard_low_stock_empty') }}"
                        id="dashLowStock"
                        class="table table-striped snipe-table snipe-table--sticky-right-1"
                        data-url="{{ route('api.low-stock.index', ['limit' => 25]) }}">
                        <thead>
                            <tr>
                                <th scope="col" data-field="item" data-formatter="polymorphicItemFormatter">{{ trans('general.name') }}</th>
                                <th scope="col" data-field="remaining" data-sortable="true" class="text-right">{{ trans('general.remaining') }}</th>
                                <th scope="col" data-field="min_amt" data-sortable="true" data-formatter="minAmtFormatter" class="text-right">{{ trans('general.min_amt') }}</th>
                                <th scope="col" data-field="available_actions" data-formatter="lowStockActionsFormatter" class="hidden-print text-right">
                                    <span class="sr-only">{{ trans('table.actions') }}</span>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            {{-- Lazy Livewire component so the eight count queries
                 that back this widget don't sit on the dashboard's
                 critical render path. Rendered as a placeholder on
                 first paint; Livewire fires a follow-up XHR to hydrate
                 the real counts. Same pattern the top-nav AlertMenu
                 uses for its low-inventory + deprecation queries. --}}
            <livewire:needs-attention/>
        </div>
</div> <!--/row-->
<div class="row">
    <div class="col-md-6">

		@if ((($snipeSettings->scope_locations_fmcs!='1') && ($snipeSettings->full_multiple_companies_support=='1')))
			 <!-- Companies -->	
			<div class="box box-default">
				<div class="box-header with-border">
					<h2 class="box-title">{{ trans('general.companies') }}</h2>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <x-icon type="minus" />
							<span class="sr-only">{{ trans('general.collapse') }}</span>
						</button>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="row">
						<div class="col-md-12">
							<table
									data-cookie-id-table="dashCompanySummary"
									data-height="400"
                                    data-pagination="false"
									data-side-pagination="server"
									data-sort-order="desc"
                                    data-show-columns="false"
									data-sort-field="assets_count"
                                    data-sticky-header="false"
									id="dashCompanySummary"
									class="table table-striped snipe-table"
									data-url="{{ route('api.companies.index', ['sort' => 'assets_count', 'order' => 'asc']) }}">

								<thead>
								<tr>
									<th scope="col" class="col-sm-3" data-visible="true" data-field="name" data-formatter="companiesLinkFormatter" data-sortable="true">{{ trans('general.name') }}</th>
									<th scope="col" class="col-sm-1" data-visible="true" data-field="users_count" data-sortable="true">
                                        <x-icon type="users" />
										<span class="sr-only">{{ trans('general.people') }}</span>
									</th>
									<th scope="col" class="col-sm-1" data-visible="true" data-field="assets_count" data-sortable="true">
                                        <x-icon type="assets" />
										<span class="sr-only">{{ trans('general.asset_count') }}</span>
									</th>
									<th scope="col" class="col-sm-1" data-visible="true" data-field="accessories_count" data-sortable="true">
                                        <x-icon type="accessories" />
										<span class="sr-only">{{ trans('general.accessories_count') }}</span>
									</th>
									<th scope="col" class="col-sm-1" data-visible="true" data-field="consumables_count" data-sortable="true">
                                        <x-icon type="consumables" />
										<span class="sr-only">{{ trans('general.consumables_count') }}</span>
									</th>
									<th scope="col" class="col-sm-1" data-visible="true" data-field="components_count" data-sortable="true">
                                        <x-icon type="components" />
										<span class="sr-only">{{ trans('general.components_count') }}</span>
									</th>
									<th scope="col" class="col-sm-1" data-visible="true" data-field="licenses_count" data-sortable="true">
                                        <x-icon type="licenses" />
										<span class="sr-only">{{ trans('general.licenses_count') }}</span>
									</th>
								</tr>
								</thead>
							</table>
						</div> <!-- /.col -->
						<div class="text-center col-md-12" style="padding-top: 10px;">
							<a href="{{ route('companies.index') }}" class="btn btn-theme btn-sm" style="width: 100%">{{ trans('general.viewall') }}</a>
						</div>
					</div> <!-- /.row -->

				</div><!-- /.box-body -->
			</div> <!-- /.box -->
		
		@else
			 <!-- Locations -->
			 <div class="box box-default">
				<div class="box-header with-border">
					<h2 class="box-title">{{ trans('general.locations') }}</h2>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <x-icon type="minus" />
							<span class="sr-only">{{ trans('general.collapse') }}</span>
						</button>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="row">
						<div class="col-md-12">

							<table
									data-cookie-id-table="dashLocationSummary"
									data-height="400"
									data-side-pagination="server"
                                    data-pagination="false"
									data-sort-order="desc"
									data-sort-field="assets_count"
                                    data-sticky-header="false"
									id="dashLocationSummary"
                                    data-show-columns="false"
									class="table table-striped snipe-table"
									data-url="{{ route('api.locations.index', ['sort' => 'assets_count', 'order' => 'asc']) }}">
								<thead>
								<tr>
									<th scope="col" class="col-sm-3" data-visible="true" data-field="name" data-formatter="locationsLinkFormatter" data-sortable="true">{{ trans('general.name') }}</th>
									
									<th scope="col" class="col-sm-1" data-visible="true" data-field="assets_count" data-sortable="true">
                                        <x-icon type="assets" />
										<span class="sr-only">{{ trans('general.asset_count') }}</span>
									</th>
									<th scope="col" class="col-sm-1" data-visible="true" data-field="assigned_assets_count" data-sortable="true">
										
										{{ trans('general.assigned') }}
									</th>
									<th scope="col" class="col-sm-1" data-visible="true" data-field="users_count" data-sortable="true">
                                        <x-icon type="users" />
										<span class="sr-only">{{ trans('general.people') }}</span>
										
									</th>
									
								</tr>
								</thead>
							</table>
						</div> <!-- /.col -->
						<div class="text-center col-md-12" style="padding-top: 10px;">
							<a href="{{ route('locations.index') }}" class="btn btn-theme btn-sm" style="width: 100%">{{ trans('general.viewall') }}</a>
						</div>
					</div> <!-- /.row -->

				</div><!-- /.box-body -->
			</div> <!-- /.box -->

		@endif
			
    </div>
    <div class="col-md-6">

        <!-- Categories -->
        <div class="box box-default">
            <div class="box-header with-border">
                <h2 class="box-title">{{ trans('general.categories') }}</h2>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <x-icon type="minus" />
                        <span class="sr-only">{{ trans('general.collapse') }}</span>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">

                        <table
                                data-cookie-id-table="dashCategorySummary"
                                data-height="400"
                                data-pagination="false"
                                data-side-pagination="server"
                                data-show-columns="false"
                                data-sort-order="desc"
                                data-sort-field="assets_count"
                                data-sticky-header="false"
                                id="dashCategorySummary"
                                class="table table-striped snipe-table"
                                data-url="{{ route('api.categories.index', ['sort' => 'assets_count', 'order' => 'asc']) }}">
                            <thead>
                            <tr>
                                <th scope="col" class="col-sm-3" data-visible="true" data-field="name" data-formatter="categoriesLinkFormatter" data-sortable="true">{{ trans('general.name') }}</th>
                                <th scope="col" class="col-sm-3" data-visible="true" data-field="category_type" data-sortable="true">
                                    {{ trans('general.type') }}
                                </th>
                                <th scope="col" class="col-sm-1" data-visible="true" data-field="assets_count" data-sortable="true">
                                    <x-icon type="assets" />
                                    <span class="sr-only">{{ trans('general.asset_count') }}</span>
                                </th>
                                <th scope="col" class="col-sm-1" data-visible="true" data-field="accessories_count" data-sortable="true">
                                    <x-icon type="licenses" />
                                    <span class="sr-only">{{ trans('general.accessories_count') }}</span>
                                </th>
                                <th scope="col" class="col-sm-1" data-visible="true" data-field="consumables_count" data-sortable="true">
                                    <x-icon type="consumables" />
                                    <span class="sr-only">{{ trans('general.consumables_count') }}</span>
                                </th>
                                <th scope="col" class="col-sm-1" data-visible="true" data-field="components_count" data-sortable="true">
                                    <x-icon type="components" />
                                    <span class="sr-only">{{ trans('general.components_count') }}</span>
                                </th>
                                <th scope="col" class="col-sm-1" data-visible="true" data-field="licenses_count" data-sortable="true">
                                    <x-icon type="licenses" />
                                    <span class="sr-only">{{ trans('general.licenses_count') }}</span>
                                </th>
                            </tr>
                            </thead>
                        </table>

                    </div> <!-- /.col -->
                    <div class="text-center col-md-12" style="padding-top: 10px;">
                        <a href="{{ route('categories.index') }}" class="btn btn-theme btn-sm" style="width: 100%">{{ trans('general.viewall') }}</a>
                    </div>
                </div> <!-- /.row -->

            </div><!-- /.box-body -->
        </div> <!-- /.box -->
    </div>


@endif

    {{-- Adjust-quantity modal wiring for the low-stock widget's inline
         replenish button. Same shared modal used on the accessories /
         consumables / components index and view pages. Included whenever
         the viewer can update any of the three item types (one of those
         grants is what makes the button actually appear in the widget). --}}
    @if (Gate::allows('update', \App\Models\Consumable::class)
         || Gate::allows('update', \App\Models\Accessory::class)
         || Gate::allows('update', \App\Models\Component::class))
        <x-modals.adjust-quantity/>
    @endif

@stop

@section('moar_scripts')
@include ('partials.bootstrap-table', ['simple_view' => true, 'nopages' => true])

        @can('view', \App\Models\Asset::class)
            {{-- Today widget for the dashboard. Reuses the main calendar
                 bundle; initializes into listDay view scoped to today. No
                 URL sync (widgets don't own the page URL), no filter
                 buttons (too tight for the dashboard column), no toolbar
                 (title suffices). Users who want to filter head over to
                 the full /calendar page. --}}
            <script src="{{ url(mix('js/dist/snipeit-calendar.js')) }}" nonce="{{ csrf_token() }}"></script>
            <script nonce="{{ csrf_token() }}">
                document.addEventListener('DOMContentLoaded', function () {
                    // Format list-view day-group labels. Compares the
                    // group's date to today's local Y-M-D so the
                    // "Today" / "Tomorrow" swap survives a browser
                    // timezone that's east/west of UTC. Anything beyond
                    // tomorrow falls back to FC's own locale-aware
                    // default via arg.text.
                    var upcomingDayFormat = function (arg) {
                        var d = arg.date;
                        var localYmd = d.year + '-' + String(d.month + 1).padStart(2, '0') + '-' + String(d.day).padStart(2, '0');
                        var today = new Date();
                        var todayYmd = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');
                        var tomorrow = new Date(today.getTime() + 86400000);
                        var tomorrowYmd = tomorrow.getFullYear() + '-' + String(tomorrow.getMonth() + 1).padStart(2, '0') + '-' + String(tomorrow.getDate()).padStart(2, '0');
                        if (localYmd === todayYmd) return '{{ trans('general.calendar_today') }}';
                        if (localYmd === tomorrowYmd) return '{{ trans('general.calendar_tomorrow') }}';
                        return arg.text;
                    };

                    window.snipeitCalendar.init('dashboard-today-calendar', {
                        events: '{{ route('api.calendar.events') }}',
                        // Rolling 7-day list starting today (not listWeek,
                        // which starts on the week's Sunday/Monday and
                        // would show yesterday on a Tuesday). Keeps the
                        // widget useful when today itself is empty by
                        // pulling in tomorrow through 6 days out.
                        initialView: 'listUpcoming',
                        views: {
                            listUpcoming: {
                                type: 'list',
                                duration: {days: 7},
                            },
                        },
                        // Left side gets the "Today" / "Tomorrow" /
                        // weekday-default swap. Right side stays a full
                        // human-readable date ("August 14, 2026") so
                        // the viewer can see the actual calendar day
                        // even when the left label is relative.
                        listDayFormat: upcomingDayFormat,
                        listDaySideFormat: {
                            month: 'long',
                            day: 'numeric',
                            year: 'numeric',
                        },
                        headerToolbar: false,
                        direction: '{{ \App\Helpers\Helper::determineLanguageDirection() }}',
                        locale: '{{ str_replace('_', '-', app()->getLocale()) }}',
                        urlState: false,
                        limit: 10,
                        onFetchMeta: function (meta) {
                            var more = document.getElementById('dashboard-today-more');
                            var link = document.getElementById('dashboard-today-more-link');
                            if (!more || !link) {
                                return;
                            }
                            if (meta.truncated) {
                                var remaining = Math.max(0, meta.total - meta.returned);
                                link.textContent = '{{ trans('general.calendar_upcoming_more') }}'.replace(':count', String(remaining));
                                more.style.display = '';
                            }
                            else {
                                more.style.display = 'none';
                            }
                        },
                    });
                });
            </script>
        @endcan
@stop

@push('js')


        <script src="{{ url(mix('js/dist/Chart.min.js')) }}"></script>
<script nonce="{{ csrf_token() }}">
    // Theme-aware default text color for every Chart.js instance on
    // this page. Without this the shipped Chart.js default (#666)
    // reads as illegible on the dark-theme box background. Same
    // isDark() + defaultFontColor pattern the reports page uses.
    function isDark() {
        return document.documentElement.getAttribute('data-theme') === 'dark';
    }
    Chart.defaults.global.defaultFontColor = isDark() ? '#cccccc' : '#666666';

    // ---------------------------
    // - ASSET STATUS CHART -
    // ---------------------------
      var pieChartCanvas = $("#statusPieChart").get(0).getContext("2d");
      var pieChart = new Chart(pieChartCanvas);
      var ctx = document.getElementById("statusPieChart");
      var pieOptions = {
              // `responsive` + `maintainAspectRatio` are top-level
              // chart options in Chart.js, not legend options. Before
              // this fix they were nested under `legend`, which
              // Chart.js silently ignored — so the pie stayed at its
              // canvas height="260" attribute and didn't fill its
              // container. Setting maintainAspectRatio: false lets the
              // pie fill both dimensions of the .chart-responsive
              // wrapper the dashboard puts it in.
              responsive: true,
              maintainAspectRatio: false,
              legend: {
                  position: 'top',
              },
              tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        counts = data.datasets[0].data;
                        total = 0;
                        for(var i in counts) {
                            total += counts[i];
                        }
                        prefix = data.labels[tooltipItem.index] || '';
                        return prefix+" "+Math.round(counts[tooltipItem.index]/total*100)+"%";
                    }
                }
              }
          };

      $.ajax({
          type: 'GET',
          url: '{{ (\App\Models\Setting::getSettings()->dash_chart_type == 'name') ? route('api.statuslabels.assets.byname') : route('api.statuslabels.assets.bytype') }}',
          headers: {
              "X-Requested-With": 'XMLHttpRequest',
              "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
          },
          dataType: 'json',
          success: function (data) {
              var myPieChart = new Chart(ctx,{
                  type   : 'pie',
                  data   : data,
                  options: pieOptions
              });
          },
          error: function (data) {
              // window.location.reload(true);
          },
      });
        var last = document.getElementById('statusPieChart').clientWidth;
        addEventListener('resize', function() {
        var current = document.getElementById('statusPieChart').clientWidth;
        if (current != last) location.reload();
        last = current;
    });
</script>
@endpush
