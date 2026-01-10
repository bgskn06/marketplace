@props([
    'id',
    'search' => false,
    'route',
    'target',
    'class',
    'function',
    'filter' => '',
    'button' => '',
    'split' => false,
    'removes' => [],
    'search_placeholder' => '',
])

<div class="mb-4 row">
    <div class="col-md-6">
        <div class="row ps-4">
            <div class="p-1 col-md-5">
                @if ($search)
                    <x-table.search-ajax target="{{ $target }}" class="{{ $class }}"  function="{{ $function }}"  placeholder="{{ $search_placeholder }}" />
                @endif
            </div>
        </div>
    </div>
    <div class="gap-3 col-md-6 d-flex align-items-center justify-content-flex-end">
        @foreach (request()->except(['tab', 'search', 'per_page', 'sort_by', 'sort_order', 'class', 'function']) as $key => $request)
        @if (is_array($request))
            @foreach ($request as $i => $item)
                <form class="ajax-form" method="GET">
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
                    <button type="submit" class="gap-1 btn btn-filter color-purple border-purple rounded-3">
                        <i class="ic--round-check"></i>{{ $removes[$key][$item] ?? $item }} <span class="color-grey">x</span>
                    </button>
                </form>
            @endforeach
        @else
            <form class="ajax-form" method="GET">
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
            </form>
        @endif
    @endforeach


        @if ($filter)
            <x-table.filter-ajax target="{{ $target }}" class="{{ $class }}" function="{{ $function }}" title="Filters">
                <x-slot name="content">
                    {{ $filter }}
                </x-slot>
            </x-table.filter-ajax>
        @endif

        @if ($split)
            <span class="border-divider"></span>
        @endif

        {{ $button }}
    </div>
</div>

@push('script')
<script>
    $(document).ready(function() {
        const $dropdownContent = $('#dropdownContent');
        const $searchInput = $('#search-input'); // Assume an input field with this ID for search

        // Toggle dropdown visibility when filter button is clicked
        $('#dropdownButton').on('click', function(event) {
            event.stopPropagation(); // Prevent event from bubbling up
            $dropdownContent.toggle(); // Toggles dropdown visibility
        });

        // Close dropdown if clicking outside the filter area
        $(document).on('click', function(event) {
            if (!$(event.target).closest('#dropdownButton').length &&
                !$(event.target).closest('#dropdownContent').length) {
                $dropdownContent.hide(); // Hide dropdown if clicked outside
            }
        });

        // AJAX filter form submission
        $('#dropdownForm').on('submit', function(event) {
            event.preventDefault();
            fetchItems(1); // Re-fetch items with filters, resetting to page 1
        });

        // Search input listener for real-time search
        $searchInput.on('input', function() {
            fetchItems(1); // Trigger AJAX call whenever search input changes
        });

        // AJAX sorting
        $(document).on('click', '.ajax-sort', function(event) {
            event.preventDefault();
            const $sortLink = $(this);
            const sortBy = $sortLink.data('sort-by');
            const sortOrder = $sortLink.data('sort-order');
            fetchItems(1, sortBy, sortOrder); // Sort items on header click
        });

        // Pagination with filters, search, and sorting
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            const page = $(this).attr('href').split('page=')[1];
            fetchItems(page); // Fetch items for the requested page
        });

        // Unified AJAX function to fetch items with filters, pagination, sorting, and search
        function fetchItems(page = 1, sortBy = null, sortOrder = null) {
            let formData = $('#dropdownForm').serialize(); // Serialize filter form data
            formData += `&page=${page}`; // Append page number to form data
            formData += `&search=${$searchInput.val()}`; // Append search input value

            // Append sorting parameters if available
            if (sortBy && sortOrder) {
                formData += `&sort_by=${sortBy}&sort_order=${sortOrder}`;
            }

            // AJAX request
            ajaxRequest(
                'GET',
                '{{ $target }}', { // `target` is the endpoint URL to get updated content
                    class: '{{ $class }}',
                    function: '{{ $function }}',
                    data: formData
                },
                function(response) {
                    $('{{ $target }}').html(response.original.html); // Update target content
                    $dropdownContent.hide(); // Optionally hide filter dropdown after request
                }
            );
        }
    });
</script>
@endpush

