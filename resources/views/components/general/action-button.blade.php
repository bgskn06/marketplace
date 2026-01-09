@props([
    'icon',
    'variant' => 'primary',
    'title' => '',
    'disabled' => false,
])

<button
    {{ $attributes->merge([
        'class' => 'btn btn-sm btn-' . $variant
    ]) }}
    title="{{ $title }}"
    @if($disabled) disabled @endif
    
>
    <span class="iconify"
          data-icon="{{ $icon }}"></span>
</button>
