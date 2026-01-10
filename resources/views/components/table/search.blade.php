@props(['url', 'placeholder' => 'Search...', 'id' => '', 'name' => 'search'])

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

<form action="{{ $url }}" method="GET">
    <div class="border input-group search rounded-3">
        <span class="input-group-text input-group-label rounded-start-3">
            <x-general.icon color="grey " icon="iconamoon:search" size="15" />
        </span>
        <input {{ $attributes }} aria-describedby="addon-wrapping" aria-label="Username"
            class="form-control form-control-sasi form-input rounded-end-3" id="{{ $id }}"
            name="{{ $name }}" placeholder="{{ $placeholder }}" {{ $attributes }}
            style="border-left: unset !important;" type="text" value="{{ request($name) }}">
    </div>
    @foreach (request()->except('search') as $key => $value)
        @if (is_array($value))
            @foreach ($value as $val)
                <input name="{{ $key }}[]" type="hidden" value="{{ $val }}">
            @endforeach
        @else
            <input name="{{ $key }}" type="hidden" value="{{ $value }}">
        @endif
    @endforeach
</form>
