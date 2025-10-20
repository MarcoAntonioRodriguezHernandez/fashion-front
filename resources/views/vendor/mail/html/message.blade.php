<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="config('app.url')">
<!--begin:Logo-->
<img alt="{{ config('app.name') }}" src="{{ asset('media/logos/cmLogo.png') }}" class="logo" />
<!--end:Logo-->
</x-mail::header>
</x-slot:header>

{{-- Body --}}
{{ $slot }}

<!--begin:Media-->
<div style="width: 100%;">
<img alt="{{ config('app.name') }}" style="width: 100%;" src="{{ asset('media/email/email.png') }}" />
</div>
<!--end:Media-->

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{{ $subcopy }}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
© {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
