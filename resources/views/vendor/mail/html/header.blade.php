@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === config('app.name'))
<img src="{{ asset('media/logos/cmLogo.png') }}" class="logo" alt="{{ config('app.name') }} Logo">
@else
{{ $slot }}
@endif

<p class="header-text">{{ config('app.name') }}</p>
</a>
</td>
</tr>
