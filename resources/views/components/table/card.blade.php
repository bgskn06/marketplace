@props([
    'title',
    'count' => null,
    'button' => '',
    'id' => '',
    'class' => ' py-2',
    'icon' => '',
    'color' => 'purple',
    'font_size' => '14',
])

<div id="{{ $id }}" class="d-flex bg-light-{{ $color }} justify-content-between {{ $class }}"
    style="border-radius: 5px;margin-bottom: 7px;padding: 0.7rem;">
    <div class="gap-2 col-auto d-flex align-items-center">
        @if ($icon != '')
            <x-general.icon icon="{{ $icon }}" size="18" color="{{ $color }}-500" />
        @endif
        <h5 class="color-{{ $color }} fw-bold" style="font-size: {{ $font_size }}px">{{ $title }}</h5>
        @if (!is_null($count))
            <span class="badge badge-success sasi">{{ $count }}</span>
        @endif
    </div>
    <div class="gap-3 col-auto pe-0 d-flex align-items-center justify-content-flex-end">
        @if (!empty($button))
            {{ $button }}
        @else
            <div style="font-size: 20px;">&nbsp;</div>
        @endif
    </div>
</div>
