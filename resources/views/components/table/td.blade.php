@props(['txtalign', 'class' => '', 'rowspan' => '-', 'colspan' => '-'])


<td class="{{ $class }}" rowspan="{{ $rowspan }}" colspan="{{ $colspan }}"
    style="text-align:{{ $txtalign ?? 'left' }}">
    {{ $slot }}
</td>
