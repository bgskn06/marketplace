@props([
    'search' => false,
    'link',
    'filter' => '',
    'button' => '',
    'split' => false,
    'removes' => [],
    'search_placeholder' => '',
])

<div class="row mb-4">
    <div class="col-md-6">
        <div class="row ps-4">
            <div class="col-md-5 p-1">
                @if ($search)
                    <x-table.search placeholder="{{ $search_placeholder }}" url="{{ $link }}" />
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6 d-flex align-items-center justify-content-flex-end gap-3">
        @foreach (request()->except(['tab', 'search', 'per_page', 'sort_by', 'sort_order', 'page']) as $key => $request)
            @if (is_array($request))
                @foreach ($request as $i => $item)
                    <form action="{{ $link }}" method="GET">
                        @foreach (request()->all() as $paramKey => $paramValue)
                            @if (is_array($paramValue))
                                @foreach ($paramValue as $val)
                                    @if ($val != $item)
                                        <input type="hidden" name="{{ $paramKey }}[]" value="{{ $val }}">
                                    @endif
                                @endforeach
                            @else
                                @if ($paramKey != $key)
                                    <input type="hidden" name="{{ $paramKey }}" value="{{ $paramValue }}">
                                @endif
                            @endif
                        @endforeach
                        <button type="submit"
                            class="btn bg-light-purple border-purple rounded-pill color-purple gap-1 align-items-center"
                            style="min-width: max-content;">
                            <i class="ic--round-check"></i>{{ $removes[$key][$item] }} <p class="color-grey">x
                            </p>
                        </button>
                    </form>
                @endforeach
            @else
                <form action="{{ $link }}" method="GET">
                    @foreach (request()->all() as $paramKey => $paramValue)
                        @if (is_array($paramValue))
                            @foreach ($paramValue as $val)
                                <input type="hidden" name="{{ $paramKey }}[]" value="{{ $val }}">
                            @endforeach
                        @else
                            @if ($paramKey != $key)
                                <input type="hidden" name="{{ $paramKey }}" value="{{ $paramValue }}">
                            @endif
                        @endif
                    @endforeach
                    <button type="submit"
                        class="btn bg-light-purple border-purple rounded-pill color-purple gap-1 align-items-center"
                        style="min-width: max-content;">
                        @php
                            $formattedKey = str_replace('_', ' ', $key);
                            $formattedKey = ucwords($formattedKey);
                        @endphp
                        <i class="ic--round-check"></i>{{ $formattedKey }} : {{ $request }} <p
                            class="color-grey">x
                        </p>
                    </button>
                </form>
            @endif
        @endforeach
        @if ($filter)
            <x-table.filter title="Filters" link="{{ $link }}">
                <x-slot name="content">
                    {{ $filter }}
                    {{ $clear }}
                </x-slot>
                <x-slot name="content_clear">
                    {{ $clear }}
                </x-slot>
            </x-table.filter>
        @endif

        @if ($split)
            <span style="border-left:1px solid #b1b5c3;">&nbsp;</span>
        @endif

        {{ $button }}

    </div>
</div>
