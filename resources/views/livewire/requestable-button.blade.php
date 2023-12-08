<div>
    @if ($requestableModel->isRequestedBy(Auth::user()))
        {{ Form::submit(trans('button.cancel'), ['class' => 'btn btn-danger btn-sm'])}}
    @else
        {{ Form::submit(trans('button.request'), ['class' => 'btn btn-primary btn-sm'])}}
    @endif
</div>
