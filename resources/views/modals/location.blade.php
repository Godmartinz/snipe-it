{{-- See snipeit_modals.js for what powers this --}}
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title">{{ trans('admin/locations/table.create')  }}</h2>
        </div>
        <div class="modal-body">
            <form class="form-horizontal" action="{{ route('api.locations.store') }}" onsubmit="return false">
                    <x-alert type="danger" id="modal_error_msg" style="display:none">
                </x-alert>

                <div class="dynamic-form-row">
                    <label for="modal-name" class="col-md-3 col-xs-12 control-label">{{ trans('general.name') }}:</label>
                    <div class="col-md-9 col-xs-12"><input type='text' name="name" id='modal-name' class="form-control"></div>
                </div>

                <!-- Setup of default company, taken from asset creator if scoped locations are activated in the settings -->
				@if (($snipeSettings->scope_locations_fmcs == '1') && ($user->companies->isNotEmpty()))
					<input type="hidden" name="company_id" id='modal-company' value='{{ $user->companies->first()->id }}' class="form-control">
				@endif

				<!-- Select company, only for users with multicompany access - replace default company -->
				<div class="dynamic-form-row">
					<label for="modal-company_id" class="col-md-3 col-xs-12 control-label">{{ trans('general.company') }}:</label>
					<div class="col-md-9 col-xs-12">
						<select
							class="js-data-ajax"
							data-endpoint="companies"
							data-placeholder="{{ trans('general.select_company') }}"
							name="company_id"
							id="modal-company_id"
							style="width: 100%"
							aria-label="{{ trans('general.company') }}"
						>
							<option value=""></option>
						</select>
					</div>
				</div>

                <div class="dynamic-form-row">
                    <label for="modal-city" class="col-md-3 col-xs-12 control-label">{{ trans('general.city') }}:</label>
                    <div class="col-md-9 col-xs-12"><input type='text' name="city" id='modal-city' class="form-control"></div>
                </div>

                <div class="dynamic-form-row">
                    <label for="modal-country" class="col-md-3 col-xs-12 country control-label">{{ trans('general.country') }}:</label>
                    <div class="col-md-9 col-xs-12">
                        <x-input.country-select
                            name="country"
                            :selected="old('country')"
                            id="modal-country"
                        />
                    </div>
                </div>
            </form>
        </div>
        @include('modals.partials.footer')
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
