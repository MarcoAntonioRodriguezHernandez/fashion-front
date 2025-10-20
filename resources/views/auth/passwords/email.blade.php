<x-layouts.auth-layout authpage="email" title="Iniciar Sesión">
    <form class="form w-100" method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="card-body">
            <div class="text-start mb-10">
                <!--begin::Title-->
                <h1 class="text-gray-900 mb-3 fs-3x" data-kt-translate="sign-in-title">Recuperación de contraseña</h1>
                <!--end::Title-->
                <!--begin::Text-->
                <div class="text-gray-500 fw-semibold fs-6" data-kt-translate="general-desc">Ingrese su correo para recuperar su contraseña, recibira un correo para restablecerla</div>
                <!--end::Link-->
            </div>
            <div class="fv-row mb-8">
                <input type="email" placeholder="Correo" name="email" autocomplete="off"
                    data-kt-translate="sign-in-input-email" class="form-control form-control-solid" />
                @error('email')
                    <span class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            
            <div class="row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        Enviar enlace
                    </button>
                </div>
            </div>
        </div>
    </form>
</x-layouts.auth-layout>
