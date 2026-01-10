@props([
    'filterUrl' => '',
    'filterName' => '',
    'sortByName' => 'sortby',
    'filterData' => [],
    'ajax' => false,
    'controller' => '',
    'function' => '',
    'target' => '',
    'instanceId' => 'filter-' . uniqid(),
])

<div id="{{ $instanceId }}" class="filter-component" data-filter-name="{{ $filterName }}"
    data-sort-name="{{ $sortByName }}" data-ajax="{{ $ajax ? 'true' : 'false' }}" data-controller="{{ $controller }}"
    data-function="{{ $function }}" data-target="{{ $target }}">

    <style>
        button.filter-button:focus:not(:focus-visible) {
            border: unset !important;
        }

        .dropdown-menu.show {
            position: fixed !important;
        }

        .filter-search-container {
            position: relative;
            margin-bottom: 10px;
        }

        .filter-search-input {
            padding-left: 30px !important;
        }

        .filter-search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .no-results {
            text-align: center;
            padding: 10px;
            color: #6c757d;
            font-style: italic;
        }
    </style>

    @php
        // Determine active filter states
        $hasActiveFilters = false;

        // Check if any checkboxes are checked
        if (!empty(request($filterName, []))) {
            $hasActiveFilters = true;
        }

        // Check if sorting is applied (and not empty)
        if (!empty(request($sortByName)) && request($sortByName) !== '') {
            $hasActiveFilters = true;
        }

        // Set icon based on active filters
        $icon = $hasActiveFilters ? 'majesticons:filter' : 'majesticons:filter-line';
        $color = 'blue';
    @endphp

    <!-- Tombol toggle filter -->
    <button type="button" class="bg-transparent border-none filter-button" data-bs-toggle="dropdown"
        aria-expanded="false" data-bs-auto-close="outside">
        <x-general.icon icon="{{ $icon }}" color="{{ $color }}" size="13" />
    </button>

    <!-- Dropdown menu -->
    <div class="p-0 dropdown-menu rounded-4 mt-3 shadow" style="width: 249px;">
        <!-- Header dropdown -->
        <div class="px-3 py-3 d-flex border-bottom-light-grey align-items-center justify-content-between">
            <h5 class="inter-bold color-black">Sort & Filter</h5>
            <form action="{{ $filterUrl }}" class="m-0">
                <button type="{{ $ajax ? 'button' : 'submit' }}" value="clear-filter"
                    class="p-0 btn btn-xs btn-red reset-filter-btn">
                    Reset
                </button>

                @php $dataFilter = [$filterName, $sortByName, 'no']; @endphp
                @foreach (request()->except($dataFilter) as $key => $value)
                    @if (is_array($value))
                        @foreach ($value as $val)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $val }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
            </form>
        </div>

        <!-- Form filter -->
        <form action="{{ $filterUrl }}" class="filter-form">
            <div class="container" style="height: 220px;overflow-y: auto;">
                @php $dataFilter = [$filterName, $sortByName, 'no']; @endphp
                @foreach (request()->except($dataFilter) as $key => $value)
                    @if (is_array($value))
                        @foreach ($value as $val)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $val }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach

                <div class="row">
                    @if ($sortByName != 'false')
                        <!-- Sorting options -->
                        <div class="my-2 col-md-12">
                            <div class="p-2 rounded-3 bg-grey-100">
                                <div class="gap-2 d-flex align-items-center">
                                    <x-general.icon icon="mi:sort" color="purple" />
                                    <h6 class="color-purple">Sorting</h6>
                                </div>
                            </div>
                        </div>
                        <div class="my-2 col-md-12">
                            <label class="form-label label-info-sasi">Sort Direction</label>
                            <div class="gap-2 d-flex align-items-center justify-content-between">
                                <div class="gap-1 d-flex align-items-center">
                                    <input class="form-check-input radio-button" type="radio"
                                        name="{{ $sortByName }}" value=""
                                        {{ request("$sortByName") == '' ? 'checked' : '' }}>
                                    <p class="color-black">None</p>
                                </div>
                                <div class="gap-1 d-flex align-items-center">
                                    <input class="form-check-input radio-button" type="radio"
                                        name="{{ $sortByName }}" value="asc"
                                        {{ request("$sortByName") == 'asc' ? 'checked' : '' }}>
                                    <p class="color-black">A-Z</p>
                                </div>
                                <div class="gap-1 d-flex align-items-center">
                                    <input class="form-check-input radio-button" type="radio"
                                        name="{{ $sortByName }}" value="desc"
                                        {{ request("$sortByName") == 'desc' ? 'checked' : '' }}>
                                    <p class="color-black">Z-A</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (!empty($filterData))
                        <!-- Filter options -->
                        <div class="my-2 col-md-12">
                            <div class="p-2 rounded-3 bg-grey-100">
                                <div class="gap-2 d-flex align-items-center justify-content-between">
                                    <div class="gap-2 d-flex align-items-center">
                                        <x-general.icon icon="fluent:list-16-filled" color="purple" />
                                        <h6 class="color-purple">List Data</h6>
                                        <div class="px-3 py-1 rounded-pill bg-light-purple border-light-grey">
                                            <h6 class="color-purple qty-selected">
                                                {{ count(request("$filterName", [])) }}
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="gap-1 d-flex align-items-center">
                                        <button type="button" class="bg-transparent select-all-btn color-blue">
                                            <p>Select All</p>
                                        </button>
                                        <h6 class="color-grey">|</h6>
                                        <button type="button" class="bg-transparent clear-all-btn color-red">
                                            <p>Clear</p>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Search input for filters -->
                        <div class="col-md-12 mb-2 filter-search-container">
                            <input type="text" class="form-control filter-search-input" placeholder="Search...">
                        </div>

                        <div class="gap-2 col-md-12 filter-items-container">
                            @foreach ($filterData as $key => $val)
                                <div class="filter-item rounded-3 p-2 {{ in_array($key, request("$filterName", [])) ? 'bg-light-purple' : 'bg-light' }}"
                                    data-filter-text="{{ strtolower($val) }}">
                                    <div class="gap-2 d-flex align-items-center">
                                        <input class="m-0 form-check-input border-purple filter-checkbox"
                                            type="checkbox" name="{{ $filterName }}[]" value="{{ $key }}"
                                            {{ in_array($key, request("$filterName", [])) ? 'checked' : '' }}>
                                        <h6 style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ $val }}</h6>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="col-md-12 no-results" style="display: none;">
                            No results found
                        </div>
                    @endif
                </div>
            </div>

            <!-- Footer dropdown -->
            <div class="gap-2 p-3 mt-3 d-flex justify-content-end border-top-light-grey">
                <button type="button" class="px-3 btn btn-sm btn-bg-light border-light-grey cancel-btn">
                    Cancel
                </button>
                <button type="{{ $ajax ? 'button' : 'submit' }}"
                    class="px-3 btn btn-sm btn-bg-purple apply-filter-btn">
                    Apply
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        // Fungsi untuk menginisialisasi komponen filter
        function initFilterComponent(instanceId) {
            const component = document.getElementById(instanceId);
            if (!component) return;

            const filterName = component.dataset.filterName || 'filter';
            const sortByName = component.dataset.sortName || 'sortby';
            const ajax = component.dataset.ajax === 'true';
            const controller = component.dataset.controller || '';
            const func = component.dataset.function || '';
            const target = component.dataset.target || '';

            // Function to check if any filters are active
            function hasActiveFilters() {
                // Check checkboxes
                const hasCheckedCheckboxes = component.querySelectorAll('.filter-checkbox:checked').length > 0;

                // Check sorting
                const sortRadio = component.querySelector(`input[name="${sortByName}"]:checked`);
                const hasSorting = sortRadio && sortRadio.value !== '';

                return hasCheckedCheckboxes || hasSorting;
            }

            // Update filter icon based on active filters
            function updateFilterIcon() {
                const currentFilterButton = component.querySelector('.filter-button');
                if (!currentFilterButton) return;

                const currentIcon = currentFilterButton.querySelector('.iconify');
                if (!currentIcon) return;

                const iconName = hasActiveFilters() ?
                    'majesticons:filter' :
                    'majesticons:filter-line';

                // Create new icon element
                const newIcon = document.createElement('span');
                newIcon.className = 'iconify color-blue';
                newIcon.setAttribute('data-icon', iconName);
                newIcon.setAttribute('data-inline', 'false');
                newIcon.style.fontSize = '13px';
                newIcon.style.width = 'fit-content';

                // Replace existing icon
                currentIcon.replaceWith(newIcon);
            }

            // Update jumlah item terpilih
            function updateQtySelected() {
                const selectedCount = component.querySelectorAll(`.filter-checkbox:checked`).length;
                const qtyElements = component.querySelectorAll('.qty-selected');
                qtyElements.forEach(el => el.textContent = selectedCount);
            }

            // Toggle class saat checkbox diubah
            function handleCheckboxChange(checkbox) {
                const listItem = checkbox.closest('.filter-item');
                if (checkbox.checked) {
                    listItem.classList.remove("bg-light");
                    listItem.classList.add("bg-light-purple");
                } else {
                    listItem.classList.remove("bg-light-purple");
                    listItem.classList.add("bg-light");
                }
                updateQtySelected();
            }

            // Select all checkbox
            function selectAllFilter() {
                const checkboxes = component.querySelectorAll(`.filter-checkbox`);
                const filterItems = component.querySelectorAll('.filter-item');

                checkboxes.forEach(checkbox => checkbox.checked = true);
                filterItems.forEach(item => {
                    item.classList.remove("bg-light");
                    item.classList.add("bg-light-purple");
                });

                updateQtySelected();
            }

            // Clear all checkbox
            function clearAllFilter() {
                const checkboxes = component.querySelectorAll(`.filter-checkbox`);
                const filterItems = component.querySelectorAll('.filter-item');

                checkboxes.forEach(checkbox => checkbox.checked = false);
                filterItems.forEach(item => {
                    item.classList.remove("bg-light-purple");
                    item.classList.add("bg-light");
                });

                updateQtySelected();
            }

            // Tutup dropdown
            function closeDropdown() {
                const dropdownMenu = component.querySelector('.dropdown-menu');
                const dropdownToggle = component.querySelector('.filter-button');

                if (dropdownMenu) dropdownMenu.classList.remove('show');
                if (dropdownToggle) {
                    dropdownToggle.setAttribute('aria-expanded', 'false');
                    dropdownToggle.classList.remove('show');
                }
            }

            // Submit filter
            function submitFilter() {
                const form = component.querySelector('.filter-form');
                const formData = new FormData(form);
                const data = {};

                for (let [key, value] of formData.entries()) {
                    if (key.endsWith('[]')) {
                        const cleanKey = key.replace('[]', '');
                        if (!data[cleanKey]) data[cleanKey] = [];
                        data[cleanKey].push(value);
                    } else {
                        data[key] = value;
                    }
                }

                if (ajax) {
                    ajaxDynamic(
                        'POST',
                        controller,
                        func,
                        data,
                        '',
                        '',
                        true,
                        function(response) {
                            // Perbarui target element
                            const targetElement = document.querySelector(target);
                            if (targetElement) {
                                targetElement.innerHTML = response.data;

                                // Re-run scripts
                                targetElement.querySelectorAll('script').forEach(script => {
                                    if (!script.type || script.type.toLowerCase() === "text/javascript") {
                                        eval(script.textContent);
                                    }
                                });
                            }

                            // Reinitialize the filter component
                            initFilterComponent(instanceId);
                            updateFilterIcon();
                            closeDropdown();
                        }
                    );
                } else {
                    form.submit();
                }
            }

            // Reset filter
            function resetFilter() {
                const form = component.querySelector('form[action="{{ $filterUrl }}"]');

                if (ajax) {
                    const resetData = {
                        reset: true
                    };

                    // Include other existing parameters
                    @foreach (request()->except([$filterName, $sortByName, 'no']) as $key => $value)
                        @if (is_array($value))
                            @foreach ($value as $val)
                                resetData['{{ $key }}[]'] = '{{ $val }}';
                            @endforeach
                        @else
                            resetData['{{ $key }}'] = '{{ $value }}';
                        @endif
                    @endforeach

                    ajaxDynamic(
                        'POST',
                        controller,
                        func,
                        resetData,
                        '',
                        '',
                        'true',
                        (response) => {
                            const targetElement = document.querySelector(target);
                            if (targetElement) {
                                targetElement.innerHTML = response.data;

                                // Re-run scripts
                                targetElement.querySelectorAll('script').forEach(script => {
                                    if (!script.type || script.type.toLowerCase() === "text/javascript") {
                                        eval(script.textContent);
                                    }
                                });
                            }

                            // Reinitialize the filter component
                            initFilterComponent(instanceId);
                            closeDropdown();
                        }
                    );
                } else {
                    // Reset form and submit
                    component.querySelectorAll(`.filter-checkbox`).forEach(el => el.checked = false);
                    component.querySelectorAll(`input[name="${sortByName}"]`).forEach(el => {
                        if (el.value === '') el.checked = true;
                    });
                    form.submit();
                }
            }

            // Filter items based on search input
            function filterItems(searchTerm) {
                const filterItems = component.querySelectorAll('.filter-item');
                const noResultsElement = component.querySelector('.no-results');
                let visibleItems = 0;

                searchTerm = searchTerm.toLowerCase().trim();

                filterItems.forEach(item => {
                    const filterText = item.dataset.filterText.toLowerCase();
                    if (filterText.includes(searchTerm)) {
                        item.style.display = 'block';
                        visibleItems++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Show/hide no results message
                if (visibleItems === 0 && searchTerm.length > 0) {
                    noResultsElement.style.display = 'block';
                } else {
                    noResultsElement.style.display = 'none';
                }
            }

            // Pasang event listeners
            function attachEvents() {
                // Checkbox change
                component.querySelectorAll('.filter-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        handleCheckboxChange(this);
                    });
                });

                // Select all
                component.querySelectorAll('.select-all-btn').forEach(btn => {
                    btn.addEventListener('click', selectAllFilter);
                });

                // Clear all
                component.querySelectorAll('.clear-all-btn').forEach(btn => {
                    btn.addEventListener('click', clearAllFilter);
                });

                // Cancel button
                component.querySelectorAll('.cancel-btn').forEach(btn => {
                    btn.addEventListener('click', closeDropdown);
                });

                // Apply filter
                component.querySelectorAll('.apply-filter-btn').forEach(btn => {
                    btn.addEventListener('click', submitFilter);
                });

                // Reset filter
                component.querySelectorAll('.reset-filter-btn').forEach(btn => {
                    btn.addEventListener('click', resetFilter);
                });

                // Search input
                component.querySelectorAll('.filter-search-input').forEach(input => {
                    input.addEventListener('input', function() {
                        filterItems(this.value);
                    });
                });

                // Radio button change
                component.querySelectorAll(`input[name="${sortByName}"]`).forEach(radio => {
                    radio.addEventListener('change', function() {
                        // No action needed, will be handled on submit
                    });
                });
            }

            // Inisialisasi awal
            updateQtySelected();
            attachEvents();

            // Simpan referensi fungsi untuk inisialisasi ulang
            component._filterComponent = {
                reinit: function() {
                    attachEvents();
                    updateQtySelected();
                }
            };
        }

        // Inisialisasi komponen saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            initFilterComponent('{{ $instanceId }}');
        });

        // Fungsi untuk inisialisasi ulang komponen setelah AJAX
        function reinitFilterComponents() {
            document.querySelectorAll('.filter-component').forEach(component => {
                if (component._filterComponent) {
                    component._filterComponent.reinit();
                } else {
                    initFilterComponent(component.id);
                }
            });
        }

        // Ekspos fungsi reinit ke global scope
        window.reinitFilterComponents = reinitFilterComponents;
    </script>
@endpush
