@props(['title', 'icon', 'link'])
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
@php
    $uniqid = uniqid();
@endphp
<div class="dropdown-modal">
    <button class="btn btn-bg-purple gap-2 color-light" type="button" id="dropdownButton-{{ $uniqid }}">
        <x-general.icon icon="hugeicons:filter" color="" size="15" class="" />
        {{ $title }}
    </button>
    <div class="dropdown-modal-content" id="dropdownContent-{{ $uniqid }}">
        <form class="" id="dropdownFormClear" method="GET" action="{{ $link ?? '#' }}">
            <div class="dropdown-modal-header">
                <h5 class="color-black"> {{ $title }}</h5>
                {{ $content_clear ?? '' }}
                <button type="submit" value="clear-filter" class="btn btn-xs btn-red">clear</button>
            </div>
        </form>
        <form class="" id="dropdownForm" method="GET" action="{{ $link ?? '#' }}">
            <div class="dropdown-modal-body">
                {{ $content ?? '' }}
            </div>
            <div class="dropdown-modal-footer">
                <button type="submit" class="btn btn-bg-purple color-light">Submit</button>
            </div>
        </form>
    </div>
</div>

@push('script')
    <script>
        $(document).ready(function() {
            var dropdownContent = $('#dropdownContent-{{ $uniqid }}');

            $('#dropdownButton-{{ $uniqid }}').click(function() {
                dropdownContent.toggle();
            });

            // $('#dropdownForm').submit(function(event) {
            //     event.preventDefault();
            //     // You can add form submission logic here
            //     dropdownContent.hide();
            // });

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
@endpush
