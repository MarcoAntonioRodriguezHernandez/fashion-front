@props(['title', 'cardTitle' => '', 'typeOfPage' => ''])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title inertia>Conspiración Moda | {{ $title }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:type" content="article" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Global Stylesheets Bundle-->
    {{-- <link rel="stylesheet" href="{{ asset('build/assets/style-8b55483a.css') }}" /> --}}
    @vite(['resources/sass/style.scss', 'resources/css/plugins.bundle.css'])
    <script>
        // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }
    </script>

    {{ $metas ?? '' }}
</head>
<!--end::Head-->
<!--begin::Body-->
<style>
    .form_padding {
        padding: 5em;
        padding-top: 0;
    }

    @media screen and (max-width: 767px) {
        .form_padding {
            padding: 0;
        }
    }
</style>

<body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default" data-kt-app-sidebar-minimize="{{ session('aside-active', 'off') }}">
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <!--end::Theme mode setup on page load-->
    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            <x-layouts.header />
            <!--begin::Wrapper-->
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                <x-layouts.aside-bar />
                <!--begin::Main-->
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <!--begin::Content wrapper-->
                    <div class="d-flex flex-column flex-column-fluid">
                        <!--begin::Toolbar-->
                        <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10">
                            <!--begin::Toolbar container-->
                            <div id="kt_app_toolbar_container"
                                class="app-container container-fluid d-flex align-items-stretch">
                                <!--begin::Toolbar wrapper-->
                                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                                    <!--begin::Page title-->
                                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                                        <!--begin::Title-->
                                        <h1 id="layout-card-title"
                                            class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0"
                                            style="font-size: 2.35em !important">{{ $cardTitle }}</h1>
                                        <!--end::Title-->
                                        <x-layouts.breadcrumb />
                                    </div>
                                    <!--end::Page title-->
                                </div>
                                <!--end::Toolbar wrapper-->
                            </div>
                            <!--end::Toolbar container-->
                        </div>
                        <!--end::Toolbar-->
                        <!--begin::Content-->
                        <!--begin::Content-->
                        <div id="kt_app_content" class="app-content flex-column-fluid">
                            <!--begin::Content container-->
                            <div id="kt_app_content_container" class="app-container container-fluid">
                                {{ $slot }}
                            </div>
                            <!--end::Content container-->
                        </div>
                        <!--end::Content-->
                        <!--end::Content-->
                    </div>
                    <!--end::Content wrapper-->
                    <x-layouts.footer />
                </div>
                <!--end:::Main-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::App-->
    {{-- <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <i class="ki-outline ki-arrow-up"></i>
    </div> --}}
    <!--end::Scrolltop-->
    <!--begin::Modals-->
    <!--begin::Javascript-->


    <script>
        var hostUrl = "assets/";
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('js/scripts.bundle.js') }}"></script>
    <script src="{{ asset('custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('js/custom/apps/ecommerce/catalog/products.js') }}"></script>
    <script src="{{ asset('js/widgets.bundle.js') }}"></script>
    <script src="{{ asset('js/custom/widgets.js') }}"></script>
    <script src="{{ asset('js/custom/apps/chat/chat.js') }}"></script>
    <script src="{{ asset('js/custom/utilities/modals/upgrade-plan.js') }}"></script>
    <script src="{{ asset('js/custom/utilities/modals/create-campaign.js') }}"></script>
    <script src="{{ asset('js/custom/utilities/modals/users-search.js') }}"></script>
    <script>
        window.sessionPutRoute = "{{ route('session.put') }}";

        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    position: 'top-end',
                    toast: true,
                    showConfirmButton: false,
                    timerProgressBar: true,
                    title: "{!! implode('', $errors->all('<div>:message</div>')) !!}",
                    timer: 4000,
                    background: localStorage.getItem("data-bs-theme") == 'dark' ? '#1e1e2d' : '#ffffff',
                });
            });
        @endif

        @if (session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    toast: true,
                    showConfirmButton: false,
                    timerProgressBar: true,
                    position: 'top-end',
                    title: '{{ session('success') }}',
                    timer: 4000,
                    background: localStorage.getItem("data-bs-theme") == 'dark' ? '#1e1e2d' : '#ffffff',
                });
            });
        @endif

        function createAlert(title, message, icon, showLoader = false, extraOptions = {}) {
            if (showLoader) {
                return Swal.fire({
                    title: title,
                    text: message,
                    icon: icon,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    ...extraOptions,
                });
            } else {
                return Swal.fire({
                    title: title,
                    text: message,
                    icon: icon,
                    confirmButtonText: 'Ok',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    ...extraOptions,
                });
            }
        }


        function createToast(title, message, icon) {
            Swal.fire({
                position: 'top-end',
                icon: icon,
                title: title,
                html: message.replaceAll('\n', '<br>'),
                showConfirmButton: false,
                timerProgressBar: true,
                timer: 3000,
                toast: true,
                background: localStorage.getItem("data-bs-theme") == 'dark' ? '#1e1e2d' : '#ffffff',
                didOpen: (toast) => {
                    toast.addEventListener('click', () => Swal.close())
                },
            });
        }

        function updateTime() {
            const currentTime = new Date();
            let hours = currentTime.getHours();
            let minutes = currentTime.getMinutes();
            const ampm = hours >= 12 ? 'PM' : 'AM';

            hours = (hours % 12) || 12;
            minutes = minutes.toString().padStart(2, '0');

            document.getElementById('time').textContent = `${hours}:${minutes} ${ampm}`;

            const day = currentTime.getDate();
            const month = (currentTime.getMonth() + 1).toString().padStart(2, '0');
            const year = currentTime.getFullYear();

            document.getElementById('date').textContent = `${day}/${month}/${year}`;
        }

        setTimeout(() => {
            setInterval(updateTime, 60000);

            updateTime();
        }, (60 - (new Date()).getSeconds()) * 1000);

        updateTime();
    </script>
    @if ($typeOfPage == 'create')
        <script src="{{ asset('js/custom/apps/customers/create-customer.js') }}"></script>
    @endif

    {{ $js ?? '' }}

    @yield('js')
</body>
<!--end::Body-->

</html>
