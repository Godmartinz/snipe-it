@props([
    'infoObject',
])

@if (($infoObject) && ($infoObject->assetstatus))

    <div class="row">
        <div class="col-md-3">
            <strong>{{ trans('general.status') }}</strong>
        </div>
        <div class="col-md-9">
            @if (($infoObject->assignedTo) && ($infoObject->deleted_at==''))
                <x-icon type="circle-solid" class="text-blue" />
                {{ $infoObject->assetstatus->name }}
                <label class="label label-default">{{ trans('general.deployed') }}</label>
                <x-icon type="long-arrow-right" />
                <x-icon type="{{ $infoObject->assignedType() }}" class="fa-fw" />
                {!!  $infoObject->assignedTo->present()->nameUrl() !!}
            @else
                @if (($infoObject->assetstatus) && ($infoObject->assetstatus->deployable=='1'))
                    <x-icon type="circle-solid" class="text-green" />
                @elseif (($infoObject->assetstatus) && ($infoObject->assetstatus->pending=='1'))
                    <x-icon type="circle-solid" class="text-orange" />
                @else
                    <x-icon type="x" class="text-red" />
                @endif
                <a href="{{ route('statuslabels.show', $infoObject->assetstatus->id) }}">
                    {{ $infoObject->assetstatus->name }}</a>
                <label class="label label-default">{{ $infoObject->present()->statusMeta }}</label>

            @endif
        </div>
    </div>
@endif
