<!--begin::Breadcrumb-->
<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
    @foreach ($breadcrumb as $value => $link)
        <!--begin::Item-->
        <li class="breadcrumb-item text-muted">
            @if ($link == null)
                {{ $value }}
            @else
                <a href="{{ $link }}" class="text-muted">{{ $value }}</a>
            @endif
        </li>
        <!--end::Item-->

        <!--begin::Item-->
        @if (!$loop->last)
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-500 w-5px h-2px"></span>
            </li>
        @endif
        <!--end::Item-->
    @endforeach
</ul>
<!--end::Breadcrumb-->
