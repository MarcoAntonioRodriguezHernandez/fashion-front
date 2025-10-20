@props(['backgroundColor'])
@php
    $bgColor = $backgroundColor ?? '#ccc';
    $hex = str_replace('#', '', $bgColor);  
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;  
    $textColor = $luminance > 0.5 ? '#000000' : '#ffffff';
@endphp
<div class="badge fw-bold" style="background-color: {{ $bgColor }}; color: {{ $textColor }}">
    {{ $slot }}
</div>