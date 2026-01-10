@props([
    'id' => uniqid(),
    'class' => '',
    'data',
    'width',
    'id_tbody' => '',
    'striped' => false,
    'bordered' => false,
    'theadSticky' => false,
    'autoscroll' => false,
    'scrollInterval' => '10000',
    'responsive' => false,
    'height' => '',
    'containTable' => true,
    'stickyY' => false,
    'stickyX' => false,
    'accordion' => false,
])

<style>
    .table {
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0px;
    }

    .table th {
        background: #F6F6F6;
        color: var(--color-black-400);
    }

    .table tbody tr:hover td {
        background-color: var(--color-grey-500);
    }

    .table thead tr th {
        border-top: 1px solid var(--color-grey-600);
        border-bottom: 1px solid var(--color-grey-600);
    }

    .table tbody tr:last-child {
        border-bottom: 1px solid transparent;
    }

    .table th:first-child {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        border-left: 1px solid var(--color-grey-600);
    }

    .table th:last-child {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        border-right: 1px solid var(--color-grey-600);
    }

    .table tbody td {
        vertical-align: middle;
        color: var(--color-black-500);
        word-wrap: break-word;
        white-space: normal;
    }

    /* --------------- TABLE STRIPED -------------- */
    .striped tbody tr:nth-child(even) td {
        background-color: var(--color-grey-400);
    }

    /* ------------ END TABLE STRIPED ------------- */



    /* --------------- TABLE BORDERED ------------- */
    .containt-table.bordered table tr:last-child td:first-child {
        border-bottom-left-radius: 9px;
        /* atau nilai radius sesuai keinginan */
    }

    .containt-table.bordered table tr:last-child td:last-child {
        border-bottom-right-radius: 8px;
        /* atau nilai radius sesuai keinginan */
    }

    .containt-table.bordered {
        border-radius: 10px;
        border: 1px solid var(--color-grey-600);
    }

    .table.table-bordered thead tr {
        border-top: 0px;
    }

    .table.table-bordered thead th {
        border-top: 0px;
    }

    .table.table-bordered tbody tr:last-child {
        border-bottom: 0px !important;
    }

    .table.table-bordered tbody tr:last-child td {
        border-bottom: 0px;
    }

    .table.table-bordered th:first-child {
        border-left: 0px;
        border-bottom-left-radius: 0px;
    }

    .table.table-bordered td:first-child {
        border-left: 0px;
    }

    .table.table-bordered th:last-child {
        border-right: 0px;
        border-bottom-right-radius: 0px;
    }

    .table.table-bordered td:last-child {
        border-right: 0px;
    }

    .table.table-bordered {
        border-collapse: collapse;
        border-color: var(--color-grey-600);
    }

    .table. {
        border-collapse: collapse;
        border-color: var(--color-grey-600);
    }

    .table.table-bordered tbody tr:last-child {
        border-bottom: 1px solid var(--color-grey-600);
    }

    /* ------------ END TABLE BORDERED ----------- */
</style>

@if ($theadSticky || $stickyY)
    <style>
        /* --------------- TABLE STICKY ------------- */
        .sticky-y table thead th {
            position: sticky;
            top: 0;
            z-index: 3;
            background: #FAFCFC;
        }

        .containt-table.sticky-y {
            max-height: {{ $height }};
            overflow-y: auto !important;
        }



        /* ------------ END TABLE STICKY ----------- */
    </style>
@endif

@if ($stickyX)
    <style>
        .sticky-x table {
            width: fit-content;
            white-space: nowrap;
            border-collapse: separate !important;
            min-width: 100%;
            z-index: 3;
        }

        .sticky-x .table-bordered td {
            border-left: 0px !important;
            border-bottom: 1px solid var(--color-grey-600);
        }

        .sticky-x .table-bordered th {
            border-left: 0px !important;
        }
    </style>
@endif


<div class="{{ $stickyX ? 'sticky-x' : '' }} {{ $stickyY ? 'sticky-y' : '' }} {{ $containTable ? 'containt-table' : '' }} w-100 {{ $bordered ? 'bordered' : '' }} {{ $class }} {{ $responsive ? 'table-responsive' : '' }}"
    {{ $attributes }}>
    <table id="{{ $id }}"
        class="table  {{ $striped ? 'striped' : '' }}  table-{{ $bordered ? 'bordered' : '' }}">
        <thead>
            @if (isset($custom_thead))
                {{ $custom_thead }}
            @else
                <tr>
                    {{ $thead }}
                </tr>
            @endif
        </thead>

        @php
            $tbodyContent = trim($tbody);
            $isMultiple = str_contains($tbodyContent, '<tbody');
        @endphp

        @if ($isMultiple) {!! $tbodyContent !!}
        @else
            <tbody id="{{ $id_tbody }}" class="{{ $autoscroll ? 'table-wrapper' : '' }}">
                {{ $tbody }}
            </tbody> @endif

    </table>
</div>

@if ($autoscroll)
    @push('script')
        <script>
            (function($) {
                $.fn.tableRowScroller = function(options) {
                    const settings = $.extend({
                        step: 1,
                        autoScroll: true,
                        scrollInterval: {{ $scrollInterval }},
                        showButtons: true,
                        resetDelay: 2000
                    }, options);

                    return this.each(function() {
                        const $container = $(this);
                        const $wrapper = $container.find('.table-wrapper');
                        let $rows = $wrapper.find('tr');
                        let currentIndex = 0;
                        let timer;
                        const originalRowCount = $rows.length;

                        if (settings.showButtons) {
                            $container.find('.table-button').show();
                        }

                        function updateTable(instant = false) {
                            const offset = -$rows.slice(0, currentIndex).toArray().reduce((acc, row) => acc + $(
                                row).outerHeight(true), 0);
                            $wrapper.css('transition', instant ? 'none' : 'transform 0.5s ease');
                            $wrapper.css('transform', `translateY(${offset}px)`);
                        }

                        function moveNext() {
                            currentIndex += settings.step;
                            if (currentIndex >= originalRowCount) {
                                currentIndex = 0;
                                updateTable(true);
                                clearInterval(timer);
                                setTimeout(() => {
                                    $wrapper.css('transition', 'transform 0.5s ease');
                                    timer = setInterval(moveNext, settings.scrollInterval);
                                }, settings.resetDelay);
                                return;
                            }
                            updateTable();
                        }

                        function movePrev() {
                            currentIndex -= settings.step;
                            if (currentIndex < 0) {
                                currentIndex = originalRowCount - 1;
                                updateTable(true);
                                clearInterval(timer);
                                setTimeout(() => {
                                    $wrapper.css('transition', 'transform 0.5s ease');
                                    timer = setInterval(movePrev, settings.scrollInterval);
                                }, settings.resetDelay);
                                return;
                            }
                            updateTable();
                        }

                        if (settings.showButtons) {
                            $container.on('click', '.table-button.next', moveNext);
                            $container.on('click', '.table-button.prev', movePrev);
                        }

                        if (settings.autoScroll) {
                            timer = setInterval(moveNext, settings.scrollInterval);
                            $container.hover(
                                () => clearInterval(timer),
                                () => timer = setInterval(moveNext, settings.scrollInterval)
                            );
                        }

                        updateTable(true);
                    });
                };
            })(jQuery);

            $('#{{ $id }}').tableRowScroller({
                step: 1,
                autoScroll: true,
                scrollInterval: {{ $scrollInterval }},
                showButtons: true,
                resetDelay: 2000
            });
        </script>
    @endpush
@endif

@push('script')
    <script>
        $(document).ready(function() {
            $(window).on("load resize scroll", updateStickyColumns);
            updateStickyColumns();
        });
    </script>
@endpush

@if ($accordion)
    @push('script')
        <script>
            $(document).ready(function() {
                // Ambil baris pertama dari thead
                let firstRow = $("table thead tr:first");

                // Cek apakah ada th dengan rowspan
                let hasTh = firstRow.find("th").length > 0;

                // Jika ada th dengan rowspan, tambahkan rowspan juga ke th baru
                if (hasTh) {
                    let secondTh = firstRow.find("th:eq(1)");

                    // Cek apakah th kedua punya atribut rowspan
                    let rowspan = secondTh.attr("rowspan");

                    if (rowspan) {
                        // Copy rowspan ke th pertama
                        firstRow.find("th:first").attr("rowspan", rowspan);
                    }

                    // Opsional: kalau kamu juga ingin tetap set colspan jadi 2
                }

                firstRow.find("th:first").attr("colspan", 2);

                // Iterasi setiap baris di tbody
                $("tbody tr").each(function() {
                    let secondTd = $(this).find("td:eq(1)");

                    // Cek apakah td kedua punya atribut rowspan
                    let rowspan = secondTd.attr('rowspan');

                    if (rowspan) {
                        // Copy rowspan ke td pertama
                        $(this).find("td:first").attr('rowspan', rowspan);
                    }
                });

            });
        </script>
    @endpush
@endif
