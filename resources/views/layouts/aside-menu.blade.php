<!--begin:Menu item-->
<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
    @if (gettype($content) == 'string')
        <!--begin:Menu link-->
        <a class="menu-link" href="{{ $content }}">
            <span class="menu-bullet">
                <span class="bullet bullet-dot"></span>
            </span>
            <span class="menu-title">{{ $mainTitle }}</span>
        </a>
        <!--end:Menu link-->
    @elseif(gettype($content) == 'array')
        <!--begin:Menu link-->
        <span class="menu-link">
            @if ($isChild)
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
            @else
                <span class="menu-icon">
                    <i class="ki-outline {{ $icon }} fs-2"></i>
                </span>
            @endif
            <span class="menu-title">{{ $mainTitle }}</span>
            <span class="menu-arrow"></span>
        </span>
        <!--end:Menu link-->
        <!--begin:Menu sub-->
        <div class="menu-sub menu-sub-accordion">
            @foreach ($content as $key => $item)
                <x-layouts.aside-menu :mainTitle="$key" :content="$item" :isChild="true" />
            @endforeach
        </div>
        <!--end:Menu sub-->
    @endif
</div>
<!--end:Menu item-->
