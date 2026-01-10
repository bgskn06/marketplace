@props(['placeholder' => 'Search Company', 'id' => 'search-input', 'target', 'class', 'function'])

<style>
    .search-input-table>input.form-control::placeholder {
        font-size: 12px;
        color: #b1b5c3;
        font-weight: 100;
    }

    .search-input-table>input.form-control {
        font-size: 12px;
    }
</style>

<div class="input-group search-input-table">
    <input
        id="{{ $id }}"
        {{ $attributes }}
        type="text"
        class="form-control form-control-sasi form-input border-left-0 rounded-end-4"
        name="search"
        placeholder="{{ $placeholder }}"
        aria-label="Search"
        style="border-left: unset !important;"
    >
</div>

@push('script')
    <script>
        $(document).ready(function() {
            // AJAX request triggered on typing in the search input
            $('#{{ $id }}').on('input', function() {
                let searchQuery = $(this).val();
                let additionalParams = $('#dropdownForm').serialize(); // Optional: Serialize other form parameters if needed

                // Compile all data to send with AJAX
                let formData = `search=${searchQuery}&${additionalParams}`;

                ajaxRequest(
                    'GET',
                    '', // Leave URL empty, use ajaxRequest to directly call the function
                    {
                        class: '{{ $class }}', // Pass controller class if needed
                        function: '{{ $function }}', // Pass controller function if needed
                        data: formData
                    },
                    function(response) {
                        const htmlContent = response.original.html;
                        $('{{ $target }}').html(htmlContent); // Update the target container
                    }
                );
            });
        });
    </script>
@endpush
