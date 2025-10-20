<x-layouts.auth-layout authpage="signIn" title="Iniciar Sesión">
    <form class="form w-100" method="POST" action="{{ route('login') }}">
        @csrf
        <!--begin::Body-->
        <div class="card-body">
            <!--begin::Heading-->
            <div class="text-start mb-10">
                <!--begin::Title-->
                <h1 class="text-gray-900 mb-3 fs-3x" data-kt-translate="sign-in-title">Iniciar Sesión</h1>
                <!--end::Title-->
                <!--begin::Text-->
                <div class="text-gray-500 fw-semibold fs-6" data-kt-translate="general-desc">Inicie sesión para entrar
                    al sistema</div>
                <!--end::Link-->
            </div>
            <!--begin::Heading-->
            <!--begin::Input group=-->
            <div class="fv-row mb-8">
                <!--begin::Email-->
                <input type="text" placeholder="Correo" name="email" autocomplete="off"
                    data-kt-translate="sign-in-input-email" class="form-control form-control-solid" value="{{ old('email') }}" />
                @error('email')
                    <span class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"
                        role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <!--end::Email-->
            </div>
            <!--end::Input group=-->
            <div class="fv-row mb-7">
                <!--begin::Password-->
                <input type="password" placeholder="Contraseña" name="password" autocomplete="off"
                    data-kt-translate="sign-in-input-password" class="form-control form-control-solid" />
                @error('password')
                    <span class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"
                        role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <!--end::Password-->
            </div>
            <!--end::Input group=-->
            <!--begin::Input group=-->
            <div class="fv-row mb-7">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label class="form-check-label" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>
            </div>
            <!--end::Input group=-->
            <!--begin::Wrapper-->
            <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-10">
                <div></div>
                <!--begin::Link-->
                <a href="{{ route('password.request') }}" class="link-primary"
                    data-kt-translate="sign-in-forgot-password">¿Olvidaste tu
                    contraseña?</a>
                <!--end::Link-->
            </div>
            <!--end::Wrapper-->
            <!--begin::Actions-->
            
                <div class="d-flex flex-stack ">
                    <!--begin::Submit-->
                    <button type="submit" class="btn btn-primary">
                        Iniciar sesión
                    </button>
                    <!--end::Submit-->
                    <!--begin::Social-->
                    <div class="d-flex align-items-center">
                    </div>
                    <!--end::Social-->
                </div>
            
            <!--end::Actions-->
        </div>
    </form>
</x-layouts.auth-layout>
