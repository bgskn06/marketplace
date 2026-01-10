@props(['title', 'icon', 'link', 'target', 'class', 'function'])
<style>
    .dropdown-modal-content {
        display: none;
        position: absolute;
        background-color: #fff;
        box-shadow: 3px 8px 16px -1px #0000000A;
        z-index: 1;
        width: 249px;
        right: 100px;
    }

    .dropdown-modal-header {
        padding: 10px 15px;
        border-bottom: 1px solid #F4F4F4;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .dropdown-modal-body {
        padding: 10px 15px;
        height: 160px;
        overflow-y: auto;
    }

    .dropdown-modal-footer {
        border-top: 1px solid #F4F4F4;
        padding: 10px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }
</style>
<div class="dropdown-modal">
    <button class="gap-2 btn btn-bg-purple color-light" type="button" id="dropdownButton">
        <x-general.icon icon="hugeicons:filter" color="" size="15" class="" />
        {{ $title }}
    </button>
    <div class="dropdown-modal-content" id="dropdownContent">
        <form id="dropdownFormClear" method="GET" action="{{ $link ?? '#' }}">
            <div class="dropdown-modal-header">
                <h5 class="color-black"> {{ $title }}</h5>
                {{ $content_clear ?? '' }}
            </div>
        </form>
        <form class="ajax-dropdown-form" id="dropdownForm" method="GET" action="{{ $link ?? '#' }}">
            <div class="dropdown-modal-body">
                {{ $content ?? '' }}
            </div>
            <div class="dropdown-modal-footer">
                <button type="submit" class="btn btn-bg-purple color-light">Submit</button>
            </div>
        </form>
    </div>
</div>

{{-- @push('script')
    <script>
        $(document).ready(function() {
            var dropdownContent = $('#dropdownContent');

            $('#dropdownButton').click(function() {
                dropdownContent.toggle();
            });

            // Handle form submission for filter
            $('#dropdownForm').on('submit', function(event) {
                event.preventDefault();
                fetchItems(1); // Start from the first page when a new filter is applied
            });

            // Handle pagination link click with filters
            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetchItems(page); // Pass the page number to fetchItems
            });

            // Function to fetch items with filters and pagination
            function fetchItems(page = 1) {
                let formData = $('#dropdownForm').serialize(); // Serialize the filter form data
                formData += `&page=${page}`; // Append the page number to the form data

                ajaxRequest(
                    'GET',
                    '{{ $target }}', {
                        class: '{{ $class }}',
                        function: '{{ $function }}',
                        data: formData
                    },
                    function(response) {
                        // Update the target container with the new HTML content
                        $('{{ $target }}').html(response.original.html);
                        dropdownContent.hide();
                    }
                );
            }

            // Hide dropdown if clicked outside
            $(document).click(function(event) {
                if (!$(event.target).closest('.dropdown-modal').length &&
                    !$(event.target).closest('.air-datepicker-global-container').length &&
                    $(event.target).closest('.dropdown-modal-body button.select2-selection__choice__remove')
                    .length) {
                    dropdownContent.hide();
                }
            });
        });
    </script>
@endpush --}}
