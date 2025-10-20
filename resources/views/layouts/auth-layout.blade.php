@props(['title' => '', 'authpage' => null])
<!DOCTYPE html>
<html lang="en">

<head>
    <base href="../../../" />
    <title>CM | {{ $title }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    @vite(['resources/sass/style.scss'])
    @vite('resources/css/plugins.bundle.css')
</head>

<body id="kt_body" class="app-blank">
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
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <!--begin::Logo-->
            <a href="index.html" class="d-block d-lg-none mx-auto pt-12 pb-10">
                <img alt="Logo" src="{{ asset('media/logos/cmLogo.png') }}" class="theme-light-show h-85px" />
                <img alt="Logo" src="{{ asset('media/logos/cmLogoDark.png') }}" class="theme-dark-show h-85px" />
            </a>
            <!--end::Logo-->
            <!--begin::Aside-->
            <div class="d-flex flex-column flex-column-fluid flex-center w-lg-50 p-10 formData">
                <!--begin::Wrapper-->
                <div class="d-flex justify-content-between flex-column-fluid flex-column w-100 mw-450px">
                    <!--begin::Header-->
                    @if ($authpage == 'signUp')
                        <div class="d-flex flex-stack py-2">
                        @elseif ($authpage == 'signIn')
                            <div class="d-flex flex-stack py-5 justify-content-center">
                            @elseif ($authpage == 'verify')
                                <div class="d-flex flex-stack py-5 justify-content-center">
                    @elseif ($authpage == 'reset')
                        <div class="d-flex flex-stack py-5 justify-content-center">
                    @elseif ($authpage == 'email')
                        <div class="d-flex flex-stack py-5 justify-content-center">
                    @endif
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="py-20">
                    <!--begin::Form-->
                    {{ $slot }}
                    <!--end::Form-->
                </div>
                <!--end::Body-->
                <!--begin::Footer-->
                <div class="m-0">
                </div>
                <!--end::Footer-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Aside-->
        <!--begin::Body-->
        @if ($authpage == 'signIn')
            <div class="d-none d-lg-flex flex-lg-row-fluid w-50 bgi-size-cover bgi-position-y-center bgi-position-x-start bgi-no-repeat image-container"
                style="background-image: url({{ asset('media/auth/login_aside.jpeg') }})"></div>
        @elseif ($authpage == 'signUp')
            <div class="d-none d-lg-flex flex-lg-row-fluid w-50 bgi-size-cover bgi-position-y-center bgi-position-x-start bgi-no-repeat image-container"
                style="background-image: url({{ asset('media/auth/signup_aside.jpg') }})"></div>
        @elseif ($authpage == 'verify')
            <div class="d-none d-lg-flex flex-lg-row-fluid w-50 bgi-size-cover bgi-position-y-center bgi-position-x-start bgi-no-repeat image-container"
                style="background-image: url({{ asset('media/auth/verify_aside.png') }})"></div>
        @endif

        @if ($authpage == 'reset')
            <div class="d-none d-lg-flex flex-lg-row-fluid w-50 bgi-size-cover bgi-position-y-center bgi-position-x-start bgi-no-repeat image-container"
                style="background-image: url({{ asset('media/auth/reset_aside.png') }})"></div>
        @elseif ($authpage == 'email')
            <div class="d-none d-lg-flex flex-lg-row-fluid w-50 bgi-size-cover bgi-position-y-center bgi-position-x-start bgi-no-repeat image-container"
                style="background-image: url({{ asset('media/auth/email_aside.png') }})"></div>
        @endif
        <!--begin::Body-->
    </div>
    <!--end::Authentication - Sign-in-->
    </div>
    <!--end::Root-->
    <!--begin::Javascript-->
    <script>
        var hostUrl = "assets/";
    </script>
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset('global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Custom Javascript(used for this page only)-->
    <script src="{{ asset('js/custom/authentication/sign-in/general.js') }}"></script>
    @if ($authpage == 'signUp')
        <script src="{{ asset('js/custom/authentication/sign-up/general.js') }}"></script>
    @endif
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
</body>

</html>
