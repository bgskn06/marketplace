@props([
    'link' => '', // URL link untuk form
    'totalDisplayed' => 20, // Jumlah data yang sedang ditampilkan
    'total' => 20, // Total data keseluruhan
    'limit' => 10, // Jumlah data per load
    'ajax' => false,
    'controller' => '',
    'function' => '',
    'target' => '',
])

<style>
    .btn-show-less:hover {
        background: var(--color-yellow-500) !important;
        color: #FFF !important;
    }

    .btn-show-more:hover {
        background: var(--color-blue-500) !important;
        color: #FFF !important;
    }

    .btn-show-all:hover {
        background: var(--color-green-500) !important;
        color: #FFF !important;
    }
</style>

<div class="container mt-2">
    <div class="row justify-content-center align-items-center">
        @if ($totalDisplayed > $limit)
            <div class="w-13">
                <form action="{{ $link }}" method="GET">
                    @php
                        $dataFilter = ['limit', 'total_displayed'];
                    @endphp
                    @foreach (request()->except($dataFilter) as $key => $value)
                        @if (is_array($value))
                            @foreach ($value as $val)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $val }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <input type="hidden" name="limit" value="{{ $limit }}">
                    <input type="hidden" name="total_displayed" value="{{ max($limit, $totalDisplayed - $limit) }}">
                    <button type="submit" {{ $ajax ? ' onclick=setPagination(this)' : '' }}
                        class="gap-2 py-2 btn btn-show-less bg-grey-500 rounded-3 border-grey-600 border-xs w-100 color-yellow justify-content-center align-items-center">
                        <x-general.icon icon="icons8:minus" color="" size="14" />
                        <h6 class="">Show Less</h6>
                    </button>
                </form>
            </div>
        @endif
        @if ($totalDisplayed < $total)
            <div class="w-13">
                <form action="{{ $link }}" method="GET">
                    @php
                        $dataFilter = ['limit', 'total_displayed'];
                    @endphp
                    @foreach (request()->except($dataFilter) as $key => $value)
                        @if (is_array($value))
                            @foreach ($value as $val)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $val }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <input type="hidden" name="limit" value="{{ $limit }}">
                    <input type="hidden" name="total_displayed" value="{{ $totalDisplayed + $limit }}">
                    <button type="submit" {{ $ajax ? ' onclick=setPagination(this)' : '' }}
                        class="gap-2 py-2 btn btn-show-more bg-grey-500 rounded-3 border-grey-600 border-xs w-100 color-blue justify-content-center align-items-center">
                        <x-general.icon icon="tabler:circle-plus-filled" color="" size="14" />
                        <h6 class="">Show More</h6>
                    </button>
                </form>
            </div>
        @endif
        @if ($totalDisplayed != $total)
            <div class="w-13">
                <form action="{{ $link }}" method="GET">
                    @php
                        $dataFilter = ['limit', 'total_displayed'];
                    @endphp
                    @foreach (request()->except($dataFilter) as $key => $value)
                        @if (is_array($value))
                            @foreach ($value as $val)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $val }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <input type="hidden" name="limit" value="{{ $limit }}">
                    <input type="hidden" name="total_displayed" value="{{ $total }}">
                    <button type="submit" {{ $ajax ? 'onclick=setPagination(this)' : '' }}
                        class="gap-2 py-2 btn btn-show-all bg-grey-500 rounded-3 border-grey-600 border-xs w-100 color-green justify-content-center align-items-center">
                        <x-general.icon icon="qlementine-icons:fullscreen-16" color="" size="14" />
                        <h6 class="">Show All</h6>
                    </button>
                </form>
            </div>
        @endif
        <div class="my-3 text-center w-100">
            <h6 class="color-grey">
                Showing <span class="color-black">{{ $totalDisplayed }}</span> of <span class="color-black">
                    {{ $total }}</span>
            </h6>
        </div>
    </div>
</div>
@push('script')
    <script>
        function setPagination(element) {
            event.preventDefault();

            let form = $(element).closest('form');
            let rawData = form.serializeArray();
            let controller = '{{ $controller }}';
            let func = '{{ $function }}';
            let target = '{{ $target }}';

            let formData = {};
            rawData.forEach(item => {
                formData[item.name] = item.value;
            });

            ajaxDynamic(
                'POST',
                controller,
                func,
                formData,
                'html',
                target
            );
        }
    </script>
@endpush
