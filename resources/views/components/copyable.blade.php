<div class="copyable relative group hidden-print">
    <span class="js-copy-{{ $id }}" style="font-size: 0px;">
        {{ $text }}
    </span>

    <i class="fa-regular fa-clipboard js-copy-link absolute right-0 top-0 opacity-0 group-hover:opacity-100 transition-opacity"
       data-clipboard-target=".js-copy-{{ $id }}"
       aria-hidden="true"
       data-tooltip="true"
       data-placement="top"
       title="{{ trans('general.copy_to_clipboard') }}">
        <span class="sr-only">{{ trans('general.copy_to_clipboard') }}</span>
    </i>
</div>

