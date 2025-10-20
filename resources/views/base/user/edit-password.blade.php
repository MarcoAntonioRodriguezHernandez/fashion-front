<x-layouts.master-layout title="Establecer nueva contraseña" cardTitle="Establecer nueva contraseña">
    <div class="form_padding">
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <!--end:::Tabs-->
            <div class="d-flex flex-column gap-7 gap-lg-10">
                <!--begin::Inventory-->
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <!--begin::Card body-->
                        <div class="card-body pt-0 mt-4">
                            <form id="staffAdd" method="POST" action="{{ route('base.user.update_password', $data->id) }}">
                                @csrf
                                @method('PUT')

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Contraseña actual</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInput" typeofInput="password" name="current_password" placeholder="Contraseña actual" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <div class="mb-10 fv-row" data-kt-password-meter="true">
                                    <label class="required fw-bold fs-6 mb-2">Nueva contraseña</label>
                                    <div class="position-relative mb-3">
                                        <input autocomplete="off" data-kt-translate="sign-up-input-password" class="form-control" type="password" name="new_password" placeholder="Nueva contraseña" required>
                                        <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                                            <i class="ki-outline ki-eye-slash fs-2"></i>
                                            <i class="ki-outline ki-eye fs-2 d-none"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center mt-3" data-kt-password-meter-control="highlight">
                                        <div class="flex-grow-1 bg-secondary bg-active-primary rounded h-5px me-2">
                                        </div>
                                        <div class="flex-grow-1 bg-secondary bg-active-primary rounded h-5px me-2">
                                        </div>
                                        <div class="flex-grow-1 bg-secondary bg-active-primary rounded h-5px me-2">
                                        </div>
                                        <div class="flex-grow-1 bg-secondary bg-active-primary rounded h-5px"></div>
                                    </div>

                                    <div class="text-muted mt-2" data-kt-translate="sign-up-hint">
                                        Usa 8 o más caracteres con una combinación de letras, números y símbolos.
                                    </div>
                                </div>

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Confirmar nueva contraseña</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInput" typeofInput="password" name="confirm_password" placeholder="Confirmar nueva contraseña" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Actions-->
                                <div class="text-end">
                                    <a href="{{ route('base.user.show', $data->id) }}" class="btn btn-light mx-4">
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary" disabled>
                                        <span class="indicator-label">Guardar</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="js">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script src="{{ asset('js/general-form.js') }}"></script>

        <script>
            let validations = [
                'new_password',
                'confirm_password',
            ].reduce((acc, field) => (acc[field] = {
                validators: {
                    notEmpty: {
                        message: '{{ __('validation.required', ['attribute' => ':attr']) }}'.replace(':attr',
                            field)
                    }
                }
            }, acc), {});

            window.addEventListener('load', () => {
                GeneralForm.init('staffAdd', validations,
                    'Error en la validación de los campos, por favor verifique los datos e intente de nuevo.')
            });

            var passwordMeter = new KTPasswordMeter(document.getElementById("passwordMeterElement"), {
                /* options */
            });
        </script>
    </x-slot>
</x-layouts.master-layout>
