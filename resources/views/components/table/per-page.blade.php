@props(['link'])

<form method="GET" action="{{ $link }}" class="mb-4">
    <div class="col-sm-1 p-0">
        <select name="per_page" class="form-select" onchange="applyPerPage(this)">
            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
        </select>
    </div>
    @foreach(request()->all() as $key => $value)
        @if(!in_array($key, ['per_page']))
            @if (is_array($value))
                @foreach ($value as $val)
                <input type="hidden" name="{{ $key }}[]" value="{{ $val }}">
                @endforeach
            @else
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
        @endif
    @endforeach
    <button id="apply-per-page" style="display: none" type="submit">OK</button>
</form>
<script>
    function applyPerPage(elm) {
        $('#apply-per-page').trigger('click');
    }
</script>