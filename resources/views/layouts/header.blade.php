<!--begin::Header-->
<div id="kt_app_header" class="app-header">
    <!--begin::Header container-->
    <div class="app-container container-fluid d-flex align-items-stretch flex-stack" id="kt_app_header_container">
        <!--begin::Sidebar toggle-->
        <div class="d-flex align-items-center d-block d-lg-none ms-n3" title="Show sidebar menu">
            <div class="btn btn-icon btn-active-color-primary w-35px h-35px me-2" id="kt_app_sidebar_mobile_toggle">
                <i class="ki-outline ki-abstract-14 fs-2"></i>
            </div>
            <!--begin::Logo image-->
            <a href="{{ route('example.index') }}">
                <img alt="Logo" src="{{ asset('media/logos/cmLogo-small.png') }}" class="h-50px theme-dark-show" />

                <img alt="Logo" src="{{ asset('media/logos/cmLogoDark-small.png') }}"
                    class="h-50px theme-light-show" />
            </a>
            <!--end::Logo image-->
        </div>
        <!--end::Sidebar toggle-->
        <!--begin::Navbar-->
        <div class="app-navbar flex-lg-grow-1" id="kt_app_header_navbar">
            <div class="app-navbar-item d-flex align-items-stretch flex-lg-grow-1">
                <!--begin::Clock-->
                <div class="d-flex align-items-center ms-1 ms-lg-3 mx-2">
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="d-flex flex-column " id="kt_header_clock">
                                <span class="text-uppercase fs-2 fw-bolder" id="time"></span>
                                <span class="fw-bold fs-5" id="date"></span>
                            </div>
                            <div class="vr ms-3 fw-bolder"></div>
                            @auth
                                <div class="d-flex flex-column align-items-start ms-3">
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold fs-6 text-gray-600">{{ Auth::user()->full_name }}</span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold fs-6 text-gray-600">
                                            {{ Auth::user()->employeeDetail?->store->name ?? 'Sin tienda asociada' }}
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold fs-6 text-gray-600">
                                            {{ Auth::user()->employeeDetail?->store?->marketplace?->name }}
                                        </span>
                                    </div>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
                <!--end::Clock-->
            </div>

            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center align-self-stretch flex-wrap mw-300px overflow-hidden me-3">
                    @foreach (Auth::user()->roles as $role)
                        <span class="badge badge-light-primary badge-sm fs-8 mx-2 my-1 p-2">
                            {{ $role->name }}
                        </span>
                    @endforeach
                </div>

                <!--NOTIFICACIONES-->
                <div class="d-flex align-items-center">
                    <a href="#" class="btn btn-sm btn-icon btn-icon-muted btn-active-icon-primary"
                        data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end"
                        data-kt-menu-flip="bottom">
                        <i id="notificationIcon" class="ki-duotone ki-notification" style="font-size: 24px;">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <i id="notificationOnIcon" class="ki-duotone ki-notification-on text-danger"
                            style="font-size: 24px;">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                        </i>
                    </a>
                    <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true">
                        <div class="d-flex flex-column bgi-no-repeat rounded-top"
                            style="background-image: url('{{ asset('media/logos/not.jpg') }}'); background-size: cover;">
                            <div class="d-flex flex-column bgi-no-repeat rounded-top">
                                <h3 class="text-black fw-bold px-9 mt-10 mb-2">Notificaciones</h3>
                                {{-- si se inicio sesion --}}
                                @auth
                                    <span class="fs-8 opacity-75 ps-10 mb-6">
                                        {{ $last24Notifications->count() }} notificaciones en las últimas 24 horas
                                    </span>
                                @endauth
                            </div>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="kt_topbar_notifications_1" role="tabpanel">
                                <div class="scroll-y mh-325px my-5 px-6">
                                    @auth
                                        {{-- ordenarlas por mas recientes segun el created_at --}}
                                        @forelse ($notifications as $notification)
                                            <div class="d-flex flex-stack py-4">
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-duotone ki-sms text-primary" style="font-size: 24px;">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    <div class="mb-0 me-2 mx-2">
                                                        <a href="{{ route('base.notifications.show', $notification->id) }}"
                                                            class="fs-6 text-gray-800 text-hover-primary fw-bolder">
                                                            @if(isset($notification->orderMarketplace))
                                                                Orden #{{ $notification->orderMarketplace->code }}
                                                            @else
                                                                Resumen semanal
                                                            @endif
                                                        </a>
                                                        <div class="text-black-400 fs-6">
                                                            @php
                                                                $text = $notification->text;
                                                                $decoded = null;
                                                                try {
                                                                    $decoded = json_decode($text, true, 512, JSON_THROW_ON_ERROR);
                                                                } catch (\Throwable $e) {
                                                                    $decoded = null;
                                                                }
                                                            @endphp
                                                            @if(is_array($decoded) && isset($decoded['resumen']))
                                                                {{ $decoded['resumen'] }}
                                                            @else
                                                                {{ $text }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="badge badge-light fs-8">hace
                                                    {{ $notification->created_at->diffInMinutes() }} min </span>
                                            </div>
                                        @empty
                                            <div class="text-center">
                                                <p class="text-muted">No hay solicitudes recientes.</p>
                                            </div>
                                        @endforelse
                                    @endauth
                                    <div class="py-3 text-center border-top">
                                        <a href="{{ route('base.notifications.index') }}"
                                            class="btn btn-color-gray-600 btn-active-color-primary">
                                            Ver Todas
                                            <span class="svg-icon svg-icon-5">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.5" x="18" y="13" width="13" height="2"
                                                        rx="1" transform="rotate(-180 18 13)" fill="black">
                                                    </rect>
                                                    <path
                                                        d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z"
                                                        fill="black"></path>
                                                </svg>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!--begin::User menu-->
            <div class="app-navbar-item ms-1 ms-md-3" id="kt_header_user_menu_toggle mb-5">
                <!--begin::Menu wrapper-->
                <div class="cursor-pointer symbol symbol-circle symbol-35px symbol-md-45px"
                    data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
                    data-kt-menu-placement="bottom-end">
                    @if (Auth::check() && Auth::user()->photo)
                        <img src="{{ asset(Auth::user()->photo) }}" alt="user" />
                    @else
                        <img src="{{ asset('src/img/user-image.png') }}" alt="user" />
                    @endif
                </div>
                <!--begin::User account menu-->
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                    data-kt-menu="true">
                    @auth
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <div class="menu-content d-flex align-items-center px-3">
                                <!--begin::Avatar-->
                                <div class="symbol symbol-50px me-5">
                                    @if (Auth::check() && Auth::user()->photo)
                                        <img src="{{ asset(Auth::user()->photo) }}" alt="user" />
                                    @else
                                        <img src="{{ asset('src/img/user-image.png') }}" alt="user" />
                                    @endif
                                </div>
                                <!--end::Avatar-->
                                <!--begin::Username-->
                                <div class="d-flex flex-column">
                                    <div class="fw-bold d-flex align-items-center fs-5">
                                        <a
                                            href="{{ route('base.user.show', Auth::user()->id) }}">{{ Auth::user()->full_name }}</a>
                                        @if (Auth::user()->status == 1)
                                            <div class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">
                                                {{ __('Activo') }}</div>
                                        @else
                                            <div class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">
                                                {{ __('Inactivo') }}</div>
                                        @endif
                                    </div>
                                    <span class="fw-semibold text-muted text-hover-primary fs-7"
                                        style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Auth::user()->email }}</span>
                                </div>
                                <!--end::Username-->
                            </div>
                        </div>
                        <!--end::Menu item-->
                    @endauth
                    <!--begin::Menu separator-->
                    <div class="separator my-2"></div>
                    <!--end::Menu separator-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-5">
                        <a href="{{ route('dashboard') }}" class="menu-link px-5">
                            <span class="menu-title position-relative">Dashboard</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                        data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                        <a href="#" class="menu-link px-5">
                            <span class="menu-title position-relative">Mode
                                <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                                    <i class="ki-outline ki-night-day theme-light-show fs-2"></i>
                                    <i class="ki-outline ki-moon theme-dark-show fs-2"></i>
                                </span></span>
                        </a>
                        <!--begin::Menu-->
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px"
                            data-kt-menu="true" data-kt-element="theme-mode-menu">
                            <!--begin::Menu item-->
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode"
                                    data-kt-value="light">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-outline ki-night-day fs-2"></i>
                                    </span>
                                    <span class="menu-title">Light</span>
                                </a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode"
                                    data-kt-value="dark">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-outline ki-moon fs-2"></i>
                                    </span>
                                    <span class="menu-title">Dark</span>
                                </a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode"
                                    data-kt-value="system">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-outline ki-screen fs-2"></i>
                                    </span>
                                    <span class="menu-title">System</span>
                                </a>
                            </div>
                            <!--end::Menu item-->
                        </div>
                        <!--end::Menu-->
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-5">
                        @auth
                            <a class="menu-link px-5" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <span class="align-middle">{{ __('Logout') }}</span>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        @else
                            <a class="menu-link px-5" href="{{ route('login') }}">Iniciar sesión</a>
                        @endauth
                    </div>
                    <!--end::Menu item-->
                </div>
                <!--end::User account menu-->

                <!--end::Menu wrapper-->
            </div>
            <!--end::User menu-->
        </div>
    </div>
    <!--end::Navbar-->
    <!--begin::Separator-->
    <div class="app-navbar-separator separator d-none d-lg-flex"></div>
    <!--end::Separator-->
    <!--end::Header container-->
    <!--end::Header-->
</div>
<script>
    @auth
    var numNotification = {{ Auth::user()->notifications->count() }};
    @endauth
    var notificationIcon = document.getElementById('notificationIcon');
    var notificationOnIcon = document.getElementById('notificationOnIcon');

    if (numNotification > 0) {
        newNotification('none', 'inline-block');
    } else {
        newNotification('inline-block', 'none');
    }

    function newNotification(notificationOn, notification, event) {
        notificationIcon.style.display = notificationOn;
        notificationOnIcon.style.display = notification;
        if (event) {
            event.preventDefault();
        }
    }
</script>
<!--end::Header container-->
<!--end::Header-->
