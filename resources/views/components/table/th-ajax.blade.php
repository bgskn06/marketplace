@props(['title', 'sort_by', 'width', 'txtalign', 'target', 'class', 'function'])

<th scope="col" style="width:{{ $width ?? 'auto' }}; text-align:{{ $txtalign ?? 'left' }}; text-transform: uppercase">
    {!! $title !!}
    @if (isset($sort_by))
        <a
            href="javascript:void(0);"
            class="ajax-sort"
            data-sort-by="{{ $sort_by }}"
            data-sort-order="{{ request('sort_order') == 'asc' ? 'desc' : 'asc' }}"
            data-target="{{ $target }}"
            data-class="{{ $class }}"
            data-function="{{ $function }}"
        >
            @if (request('sort_order') == 'asc' && request('sort_by') == $sort_by)
                <i class="fluent-mdl2--sort-up color-purple"></i>
            @elseif(request('sort_order') == 'desc' && request('sort_by') == $sort_by)
                <i class="fluent-mdl2--sort-down color-purple"></i>
            @else
                <i class="fluent-mdl2--sort"></i>
            @endif
        </a>
    @endif
</th>
