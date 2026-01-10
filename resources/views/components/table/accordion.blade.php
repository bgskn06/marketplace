@props([
    'accordion' => true,
    'data' => 0,
    'controller' => '',
    'function' => '',
    'bclass' => '', // tambahkan props class
    'hide' => '',
    'callback' => '',
    'rowspan' => 1,
    'colspan' => 1,
])

<tr parent-data="{{ $data }}" controller="{{ $controller }}" func="{{ $function }}" data-rowspan="{{ $rowspan }}" {{ $attributes }}>
    <td class="sticky-x border-x-0 border-y-xs border-black-50" rowspan="{{ $rowspan }}"
        colspan="{{ $colspan }}">
        <div class="d-flex align-items-center justify-content-center">
            @if ($accordion)
                <button type="button"
                    class="btn-accordion rounded-3 bg-light border-xs border-grey-600 d-flex align-items-center justify-content-center {{ $hide ? 'd-none' : '' }}"
                    style="width: 16px;height:16px;" onclick="openAccordion(this, event)">
                    <x-general.icon icon="iconamoon:arrow-down-2" color="black-200" size="14" />
                </button>
            @endif
        </div>
    </td>
    {{ $slot }}
</tr>




@push('script')
    <script>
        var element = '';
        var parentId = 0;

        function removeChildrenRecursively(parentId) {
            let children = $(`tr[child-from="${parentId}"]`);
            children.each(function() {
                let thisRow = $(this);
                let thisId = thisRow.attr('parent-data');

                // Hindari rekursi ke parentId yang sama (loop tak berujung)
                if (thisId && thisId !== parentId) {
                    removeChildrenRecursively(thisId);
                }

                thisRow.remove();
            });
        }


        function openAccordion(btn, event) {
            event.preventDefault();

            let parent = $(btn).closest('tr');
            let controller = parent.attr('controller');
            let func = parent.attr('func');
            let rawData = parent.attr('parent-data');
            let data = `data=${encodeURIComponent(rawData)}`;
            element = btn;
            parentId = rawData;

            let isOpen = $(btn).hasClass('active');

            if (isOpen) {
                // Tutup parent dan hapus semua anaknya secara rekursif
                removeChildrenRecursively(rawData);
                $(btn).removeClass('active');
                $(btn).removeClass('rotate');
                updateStickyColumns();
            } else {
                ajaxDynamic(
                    'GET',
                    controller,
                    func,
                    data,
                    'domOpenAccordion',
                    parent
                );
            }
        }

        function domOpenAccordion(response,parent) {
            const callbackMethod = '{{ $callback }}';
            const $btn = $(element);
            const $row = parent ?? $(`tr[parent-data="${parentId}"]`);

            if (!$row.length) return; // Cegah error jika parent tidak ditemukan

            let $responseHtml = $(response);

            if (!$responseHtml.length) {
                if (typeof callbackMethod === "string" && callbackMethod !== "html" && window[callbackMethod]) {
                    window[callbackMethod](response, parentId);
                }

                return false;
            };

            // Ambil level dari parent, default ke 0 jika tidak ada
            let parentLevel = parseInt($row.attr('data-level') || '0', 10);
            let childLevel = parentLevel + 1;

            // Tambahkan atribut dan padding berdasarkan level
            $responseHtml.filter('tr').each(function() {
                $(this).attr('child-from', parentId);
                $(this).attr('data-level', childLevel); // simpan level untuk anak ini
                // $(this).find('td:first').css('padding-left', childLevel * 20 + 'px'); // indentasi
            });

            let $parent = $row; // ini tr.parent
            let rowspan = parseInt($parent.data('rowspan') || 1);
            let $insertAfter = $parent;

            // Iterasi ke bawah sebanyak rowspan - 1
            for (let i = 0; i < rowspan - 1; i++) {
                $insertAfter = $insertAfter.next('tr');
            }

            // Sisipkan setelah baris terakhir dari rowspan
            $insertAfter.after($responseHtml);

            // Sisipkan setelah parent
            // $row.after($responseHtml);
            $btn.addClass('active');
            $btn.addClass('rotate');


            // Salin class dari td kedua ke td pertama jika ada
            $responseHtml.find("td:first").each(function() {
                let classValue = $(this).siblings("td:eq(0)").attr('class');
                if (classValue) {
                    $(this).attr('class', classValue);
                }
            });

            if (typeof callbackMethod === "string" && callbackMethod !== "html" && window[callbackMethod]) {
                window[callbackMethod](response, parentId);
            }

            setTimeout(updateStickyColumns, 500);
        }
    </script>
@endpush
