<x-layouts.auth-layout authpage="verify" title="Verificacíon">
    <div class="form w-100 mb-10">
        <div class="text-center mb-10">
            <img alt="Logo" class="theme-light-show mh-125px" src="{{ asset('media/svg/misc/smartphone-2.svg') }}" />
            <img alt="Logo" class="theme-dark-show mh-125px"
                src="{{ asset('media/svg/misc/smartphone-2-dark.svg') }}" />
        </div>
        <!--end::Icon-->
        <!--begin::Heading-->
        <div class="text-center mb-10">
            <!--begin::Title-->
            <h1 class="text-gray-900 mb-3" data-kt-translate="two-step-title">Verifique su correo</h1>
            <!--end::Title-->
            <!--begin::Sub-title-->
            <div class="text-muted fw-semibold fs-5 mb-5" data-kt-translate="two-step-deck">Entre a su correo
                electronico para confirmar su cuenta</div>
            <!--end::Sub-title-->
            <!--begin::Mobile no-->
            <div class="fw-bold text-gray-900 fs-3">
                <span class="me-2">Correo:</span>
                <span class="text-primary">{{ str_repeat('*', 8) . substr(Auth::user()->email, 8) }}</span>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                
                    <form class="mt-8" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">Reenviar correo</button>
                    </form>
                
                    {{-- sign out --}}
                    <form class="mt-8" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            Cerrar sesión
                        </button>
                    </form>
                
            </div>
            <!--end::Mobile no-->
        </div>
    </div>

</x-layouts.auth-layout>
