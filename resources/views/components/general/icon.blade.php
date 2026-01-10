@props(['id' => '', 'icon' => '', 'color' => '', 'cssColor' => '', 'size' => '12', 'class' => '', 'style' => ''])
<span id="{{ $id }}" {{ $attributes }} class="iconify color-{{ $color }} {{ $class }}"
    data-icon="{{ $icon }}" data-inline="false"
    style="font-size: {{ $size }}px;width:fit-content; color: {{ $cssColor }} ;{{ $style }}"></span>