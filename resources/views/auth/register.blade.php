<x-layouts.auth-layout authpage="signUp" title="Registro">
    <form class="form w-100" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf
        <!--begin::Body-->
        <div class="card-body">
            <!--begin::Heading-->
            <div class="text-start mb-10">
                <!--begin::Title-->
                <h1 class="text-gray-900 mb-3 fs-3x" data-kt-translate="sign-in-title">
                    Regístrate
                </h1>
                <!--end::Title-->
                <!--begin::Text-->
                <div class="text-gray-500 fw-semibold fs-6" data-kt-translate="general-desc">
                    Registrate para poder acceder a Conspiración Moda
                </div>
                <!--end::Link-->
            </div>
            <!--begin::Heading-->
            <!--begin::Input group=-->
            <div class="row fv-row mb-7">
                <div class="mb-8">
                    <div style="text-align: center">
                        <div class="image-input image-input-outline" data-kt-image-input="true">
                            <div class="image-input-wrapper w-200px h-200px"
                                style="background-image: url({{ 'media/auth/icon.jpg' }})"></div>
                            <label for="icono" style="text-align: center; display: block; margin-top: 15px;">Agregar
                                Imagen</label>
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Seleccionar imagen">
                                <i class="bi bi-camera"> </i>
                                <input type="file" name="photo" id="photo" accept="image/*" />
                                @error('photo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </label>
                        </div>
                    </div>
                </div>
                <!--begin::Email-->
                <div class="col-xl-6 mb-8 mb-xl-0">
                    <input type="text" name="name" autocomplete="off" placeholder="Nombre"
                        data-kt-translate="sign-in-input-email" class="form-control form-control-solid"
                        value="{{ old('name') }}" />
                    @error('name')
                        <span class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"
                            role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <!--end::Email-->
                </div>
                <!--end::Input group=-->
                <div class="col-xl-6">
                    <!--begin::Email-->
                    <input type="text" name="last_name" autocomplete="off" placeholder="Apellido"
                        data-kt-translate="sign-in-input-email" class="form-control form-control-solid"
                        value="{{ old('last_name') }}" />
                    @error('last_name')
                        <span class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"
                            role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <!--end::Email-->
                </div>
            </div>
            <!--end::Input group=-->
            <!--begin::Input group=-->
            <div class="fv-row mb-8">
                <!--begin::Email-->
                <input type="email" name="email" autocomplete="off" value="{{ old('email', $email) }}"
                    placeholder="Correo" disabled data-kt-translate="sign-in-input-email"
                    class="form-control form-control-solid" />
                @error('email')
                    <span class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"
                        role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <!--end::Email-->
            </div>
            <!--end::Input group=-->
            <!--begin::Input group=-->
            <div class="fv-row mb-8">
                <!--begin::Email-->
                <input type="text" name="phone" autocomplete="off" placeholder="Teléfono"
                    data-kt-translate="sign-in-input-email" class="form-control form-control-solid"
                    value="{{ old('phone') }}" maxlength="10" minlength="10" pattern="\d{10}" inputmode="numeric"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required />

                @error('phone')
                    <span class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"
                        role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <!--end::Email-->
            </div>
            <!--end::Input group=-->
            <!--begin::Input group=-->
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
            <!--end::Input group=-->
            <!--end::Input group=-->
            <div class="fv-row mb-7">
                <!--begin::Password-->
                <input type="password" name="password_confirmation" autocomplete="off"
                    placeholder="Confirmar Contraseña" data-kt-translate="sign-in-input-password"
                    class="form-control form-control-solid" />
                @error('password_confirmation')
                    <span class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"
                        role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <!--end::Password-->
            </div>
            <!--end::Input group=-->

            <!--begin::Wrapper-->
            <input type="hidden" name="invitation" value="{{ $invitationToken }}">
            <!--end::Wrapper-->

            <!--begin::Actions-->
            <div class="d-flex flex-stack ">
                <!--begin::Submit-->
                <button type="submit" class="btn btn-primary" disabled>
                    Registrarse
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
