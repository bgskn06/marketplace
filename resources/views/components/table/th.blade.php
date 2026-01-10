@props([
    'title' => '',
    'sort_by',
    'width',
    'sort_by_url',
    'txtalign',
    'class' => '',
    'filter' => false,
    'filterUrl' => '',
    'sortByName' => 'sortby',
    'filterName' => '',
    'filterData' => [],
    'textClass' => '',
    'style' => '',
    'ajax' => false,
    'target' => '#',
    'controller' => '',
    'function' => '',
    'resetClick' => false,
    'additionalReset' => [],
])

@php
    // Default filterUrl
    $finalResetUrl = $filterUrl;

    if ($resetClick) {
        // Ambil semua query sekarang
        $query = request()->all();

        // Hapus filterName utama
        if ($filterName) {
            unset($query[$filterName]);
        }

        // Hapus additionalReset jika ada
        if (!empty($additionalReset)) {
            foreach ($additionalReset as $resetKey) {
                unset($query[$resetKey]);
            }
        }

        // Ambil query yg sudah ada di $filterUrl biar nggak dobel
        $parsedUrl = parse_url($filterUrl);
        $existingQuery = [];
        if (!empty($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $existingQuery);
        }

        // Buang query yang sudah ada di filterUrl
        foreach ($existingQuery as $key => $value) {
            unset($query[$key]);
        }

        if (count($query)) {
            // Tentukan separator
            $separator = Str::contains($filterUrl, '?') ? '&' : '?';
            $finalResetUrl = $filterUrl . $separator . http_build_query($query);
        }
    }
@endphp




<th {{ $attributes }} class="py-3 {{ $class }} {{ $resetClick ? 'cursor-pointer' : '' }}" scope="col"
    style="{{ $style }} width:{{ $width ?? 'auto' }}; text-align:{{ $txtalign ?? 'left' }};">
    <div class="gap-2 d-flex align-items-center justify-content-{{ $txtalign ?? 'left' }}">
        @if ($title)
            <div @if ($resetClick) onclick="window.location.href='{{ $finalResetUrl }}'" @endif
                style="text-transform: uppercase">{!! $title !!}</div>
        @else
            {{ $slot }}
        @endif

        @if ($filter)
            <x-table.table-filter :filterData="$filterData" ajax="{{ $ajax }}" controller="{{ $controller }}"
                filterName="{{ $filterName }}" filterUrl="{{ $filterUrl }}" function="{{ $function }}"
                sortByName="{{ $sortByName }}" target="{{ $target }}" />
        @endif

        @if (isset($sort_by))
            <a
                href="{{ route(
                    $sort_by_url,
                    array_merge(request()->all(), [
                        'sort_by' => $sort_by,
                        'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc',
                    ]),
                ) }}">
                @if (request('sort_order') == 'asc' && request('sort_by') == $sort_by)
                    <i class="fluent-mdl2--sort-up color-purple"></i>
                @elseif(request('sort_order') == 'desc' && request('sort_by') == $sort_by)
                    <i class="fluent-mdl2--sort-down color-purple"></i>
                @else
                    <i class="fluent-mdl2--sort"></i>
                @endif
            </a>
        @endif
    </div>
</th>
