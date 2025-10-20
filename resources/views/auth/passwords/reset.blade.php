<x-layouts.auth-layout authpage="reset" title="Iniciar Sesión">
    <form class="form w-100" method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="card-body">
            <div class="text-start mb-10">
                <!--begin::Title-->
                <h1 class="text-gray-900 mb-3 fs-3x" data-kt-translate="sign-in-title">Reestablezca su contraseña</h1>
                <!--end::Title-->
                <!--begin::Text-->
                <div class="text-gray-500 fw-semibold fs-6" data-kt-translate="general-desc">Ingrese su nueva contraseña para reestablecerla</div>
                <!--end::Link-->
            </div>
            <div class="fv-row mb-8">
                <!--begin::Email-->
                <input type="email" name="email" autocomplete="off" value="{{ $email ?? old('email') }}" placeholder="Correo"
                    data-kt-translate="sign-in-input-email" class="form-control form-control-solid" />
                @error('email')
                    <span class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"
                        role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <!--end::Email-->
            </div>
            <div class="fv-row mb-10" data-kt-password-meter="true">
                <!--begin::Wrapper-->
                <div class="mb-1">
                    <!--begin::Input wrapper-->
                    <div class="position-relative mb-3">
                        <input class="form-control form-control-lg form-control-solid" type="password"
                            placeholder="Contraseña" name="password" autocomplete="off"
                            data-kt-translate="sign-up-input-password" />
                        @error('password')
                            <span
                                class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"
                                role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                            data-kt-password-meter-control="visibility">
                            <i class="ki-outline ki-eye-slash fs-2"></i>
                            <i class="ki-outline ki-eye fs-2 d-none"></i>
                        </span>
                    </div>
                    <!--end::Input wrapper-->
                    <!--begin::Meter-->
                    <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                        <div class="flex-grow-1 bg-secondary bg-active-primary rounded h-5px me-2"></div>
                        <div class="flex-grow-1 bg-secondary bg-active-primary rounded h-5px me-2"></div>
                        <div class="flex-grow-1 bg-secondary bg-active-primary rounded h-5px me-2"></div>
                        <div class="flex-grow-1 bg-secondary bg-active-primary rounded h-5px"></div>
                    </div>
                    <!--end::Meter-->
                </div>
                <!--end::Wrapper-->
                <!--begin::Hint-->
                <div class="text-muted" data-kt-translate="sign-up-hint">
                    Usa 8 o más caracteres con una combinación de letras, números y símbolos.
                </div>
                <!--end::Hint-->
            </div>
            <div class="fv-row mb-8">
                <!--begin::Email-->
                <input type="password" name="password_confirmation" autocomplete="off" value="{{ old('password_confirmation') }}" placeholder="Confirme su contraseña"
                    data-kt-translate="sign-in-input-password_confirmation" class="form-control form-control-solid" />
                @error('password_confirmation')
                    <span class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"
                        role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <!--end::Email-->
            </div>
            <div class="d-flex flex-stack justify-content-end">
                <!--begin::Submit-->
                <button type="submit" class="btn btn-primary">
                    Reestablecer Contraseña
                </button>
                <!--end::Submit-->
                <!--begin::Social-->
                <div class="d-flex align-items-center">
                </div>
                <!--end::Social-->
            </div>
        </div>
    </form>
</x-layouts.auth-layout>