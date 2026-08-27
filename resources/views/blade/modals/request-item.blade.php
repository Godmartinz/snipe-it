<div class="modal fade" id="requestItemModal" tabindex="-1" role="dialog" aria-labelledby="requestItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('button.close') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="requestItemModalLabel">
                    {{ trans('general.request_item') }}
                    <small class="request-item-name text-muted"></small>
                </h4>
            </div>

            {{-- Action URL is set per-click by snipeit.js from the trigger
                 button's data-request-url. Same shape as the adjust-quantity
                 modal so the moves-parts stay familiar. --}}
            <form id="requestItemForm" method="POST" action="" accept-charset="utf-8">
                @csrf

                {{-- Snapshot of the tab the user was on when they
                     opened the modal. Snipeit.js reads the enclosing
                     .tab-pane on click and writes its id here so the
                     controller can restore the same tab on the
                     post-submit redirect. --}}
                <input type="hidden" name="active_tab" id="requestItemActiveTab" value="">

                <div class="modal-body">
                    {{-- Qty row is hidden when the trigger sets
                         data-item-type="asset" - assets are 1:1
                         (you request THE asset, not N of it). The
                         hidden input keeps request-quantity=1
                         posted so the server-side path stays
                         uniform across every requestable type. --}}
                    <div class="form-group" id="requestItemQuantityRow">
                        <label for="requestItemQuantity">{{ trans('general.qty') }}</label>
                        <input
                            type="number"
                            class="form-control"
                            id="requestItemQuantity"
                            name="request-quantity"
                            min="1"
                            step="1"
                            value="1"
                            required
                        >
                    </div>

                    {{-- Start / end dates are optional. Requesters who just
                         want "whenever this becomes available" leave both
                         blank; requesters reserving for a specific window
                         (offsite event, project sprint) fill them in.
                         end_date validates as after_or_equal:start_date in
                         the controller. --}}
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="requestItemStartDate">{{ trans('general.start_date') }}</label>
                            <x-input.datepicker
                                id="requestItemStartDate"
                                name="start_date"

                            />
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="requestItemEndDate">{{ trans('general.end_date') }}</label>
                            <x-input.datepicker
                                id="requestItemEndDate"
                                name="end_date"
                            />
                        </div>
                    </div>

                    {{-- Optional free-text notes so the requester can
                         attach context (why they need it, budget code,
                         project, etc). Persisted on
                         checkout_requests.notes and surfaced on the
                         admin queue + in the "new request"
                         notification. --}}
                    <div class="form-group">
                        <label for="requestItemNotes">{{ trans('general.notes') }}</label>
                        <textarea
                            id="requestItemNotes"
                            name="notes"
                            class="form-control"
                            rows="3"
                        ></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ trans('button.cancel') }}</button>
                    <button type="submit" class="btn btn-primary pull-right">{{ trans('button.request') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
