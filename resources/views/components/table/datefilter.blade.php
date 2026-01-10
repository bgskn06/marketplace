@props([
    'url' => '#', // Default URL
    'nameStart' => 'datefilter_start',
    'nameFinish' => 'datefilter_finish',
    'useForm' => true, // Determines whether to wrap in a form
    'formId' => 'dateFilterForm', // Identifies the form or a container if useForm is false,
    'dateStart' => now()->startOfMonth()->toDateString(),
    'dateFinish' => now()->endOfMonth()->toDateString(),
    'activeFinish' => true
])

@if ($useForm)
    <form id="{{ $formId }}" method="GET" action="{{ $url }}">
@endif

<div class="overflow-hidden border align-items-center d-flex rounded-3"
    @if (!$useForm) id="{{ $formId }}" @endif>
    <x-forms.datepicker title="" id="datepickerStart" name="{{ $nameStart }}"
        value="{{ request()->get($nameStart) ?? $dateStart }}" style="width: 9vw !important;" />

    @if($activeFinish)
        <span
            style="text-decoration: underline;
                -webkit-text-decoration-color: grey; /* Safari */
                text-decoration-color: grey;
                text-underline-offset: 2px;"
            class="mx-2">To</span>
        <x-forms.datepicker title="" id="datepickerFinish" name="{{ $nameFinish }}"
            value="{{ request()->get($nameFinish) ?? $dateFinish }}" style="width: 9vw !important;" />
    @endif
</div>

<style>
    div[style*="overflow-x: auto"]::-webkit-scrollbar {
        display: none;
        /* Hide scrollbar for Chrome, Safari, and Opera */
    }
</style>

@if ($useForm)
    @foreach (request()->except([$nameStart, $nameFinish]) as $key => $value)
        @if (is_array($value))
            @foreach ($value as $val)
                <input type="hidden" name="{{ $key }}[]" value="{{ $val }}">
            @endforeach
        @else
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endif
    @endforeach
    </form>
@endif

@push('scripts')
    <script>
        $(document).ready(function() {
            function convertToISODate(dateString) {
                const months = {
                    'Jan': '01',
                    'Feb': '02',
                    'Mar': '03',
                    'Apr': '04',
                    'May': '05',
                    'Jun': '06',
                    'Jul': '07',
                    'Aug': '08',
                    'Sep': '09',
                    'Oct': '10',
                    'Nov': '11',
                    'Dec': '12'
                };

                const regex = /^\s*(\d{1,2})[-\s]?([A-Za-z]{3})[-\s]?(\d{4})\s*$/;
                const match = dateString.match(regex);

                if (match) {
                    const day = match[1].padStart(2, '0');
                    const month = months[match[2]];
                    const year = match[3];
                    return `${year}-${month}-${day}`;
                }

                const parsedDate = new Date(dateString);
                if (!isNaN(parsedDate)) {
                    return parsedDate.toISOString().split('T')[0];
                }
                return '';
            }

            @if($activeFinish)
                function handleDateChange() {
                    const startDate = $('#datepickerStart').val();
                    const finishDate = $('#datepickerFinish').val();

                    $('#datepickerStart, #datepickerFinish').removeClass('is-invalid');

                    if (startDate && finishDate) {
                        const formattedStart = convertToISODate(startDate);
                        const formattedFinish = convertToISODate(finishDate);

                        $('#datepickerStart').val(formattedStart);
                        $('#datepickerFinish').val(formattedFinish);

                        const start = new Date(formattedStart);
                        const finish = new Date(formattedFinish);

                        if (finish < start) {
                            $('#datepickerFinish').addClass('is-invalid');
                        } else {
                            const formId = "{{ $formId }}";
                            @if ($useForm)
                                $(`#${formId}`).submit();
                            @else
                                console.log("Start Date: " + formattedStart + ", Finish Date: " + formattedFinish);
                            @endif
                        }
                    } else {
                        if (!startDate) $('#datepickerStart').addClass('is-invalid');
                        if (!finishDate) $('#datepickerFinish').addClass('is-invalid');
                    }
                }
            @else
            function handleDateChange() {
                    const startDate = $('#datepickerStart').val();

                    $('#datepickerStart').removeClass('is-invalid');

                    if (startDate) {
                        const formattedStart = convertToISODate(startDate);

                        $('#datepickerStart').val(formattedStart);

                        const start = new Date(formattedStart);

                        const formId = "{{ $formId }}";
                        @if ($useForm)
                            $(`#${formId}`).submit();
                        @else
                            console.log("Start Date: " + formattedStart);
                        @endif
                    } else {
                        if (!startDate) $('#datepickerStart').addClass('is-invalid');
                    }
                }
            @endif

            $('#datepickerStart, #datepickerFinish').on('change', handleDateChange);
        });
    </script>
@endpush
