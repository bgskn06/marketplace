@props(['data', 'width'])

<table class="table table-sasi">
    <thead>
        <tr>
            {{ $thead }}
        </tr>
    </thead>
    <tbody>
        @if (iseet($data))
            @foreach ($data as $val)
                {{ $val }}
            @endforeach
        @endif
    </tbody>
</table>
