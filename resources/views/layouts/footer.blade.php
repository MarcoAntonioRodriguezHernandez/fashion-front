<!--begin::Footer-->
<div id="kt_app_footer" class="app-footer">
    <!--begin::Footer container-->
    <div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
        <!--begin::Copyright-->
        <div class="text-gray-900 order-2 order-md-1">
            <span class="text-muted fw-semibold me-1">{{ date('Y') }} &copy;</span>
            <a href="{{ route('dashboard') }}" class="text-gray-800 text-hover-primary">{{ config('app.name') }}</a>
        </div>
        <!--end::Copyright-->
        <!--begin::Menu-->
        <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
            <li class="menu-item">
                <a href="{{ route('dashboard') }}" class="menu-link px-2">Inicio</a>
            </li>
            <li class="menu-item align-items-center d-flex">
                <span class="text-muted fw-semibold me-1">v{{ config('app.version') }}</span>
            </li>
        </ul>
        <!--end::Menu-->
    </div>
    <!--end::Footer container-->
</div>
<!--end::Footer-->
