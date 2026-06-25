<div class="modal fade" id="new-label-modal" tabindex="-1" role="dialog" aria-labelledby="newLabelModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form action="{{ route('settings.labels.create') }}" method="GET">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>

                    <h4 class="modal-title" id="newLabelModalLabel">
                        Create New Label
                    </h4>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Label Type</label>

                        <div class="row label-type-selector">
                            <div class="col-xs-6">
                                <label class="label-type-card active">
                                    <input type="radio" name="type" value="sheet" checked>
                                    <i class="fa-regular fa-file-lines"></i>
                                    <span>Sheet Labels</span>
                                </label>
                            </div>

                            <div class="col-xs-6">
                                <label class="label-type-card">
                                    <input type="radio" name="type" value="tape">
                                    <i class="fa-solid fa-tape"></i>
                                    <span>Tape Labels</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="sheet-options">
                        <div class="form-group">
                            <label>Page Size</label>

                            <select name="page_size" class="form-control">
                                @foreach(\App\Models\Labels\RectangleSheet::supportedPageSizes() as $key => $page)
                                    <option value="{{ $key }}">
                                        {{ $page['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Width (mm)</label>
                            <input
                                    type="number"
                                    step="0.1"
                                    min="0.1"
                                    name="label_width"
                                    class="form-control"
                                    required
                            >
                        </div>

                        <div class="col-md-6">
                            <label>Height (mm)</label>
                            <input
                                    type="number"
                                    step="0.1"
                                    min="0.1"
                                    name="label_height"
                                    class="form-control"
                                    required
                            >
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Continue
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
<script>
    $('.label-type-card').on('click', function () {
        $('.label-type-card').removeClass('active');

        $(this)
            .addClass('active')
            .find('input')
            .prop('checked', true)
            .trigger('change');
    });
</script>