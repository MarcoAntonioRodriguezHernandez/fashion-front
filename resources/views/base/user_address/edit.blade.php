<x-layouts.master-layout title="Editar Dirección" cardTitle="Editar {{ $data->street }} de {{ $data->user->full_name }}">

    <div class="form_padding">
        <!--begin::Form-->
        <form id="staffAdd" class="form d-flex flex-column flex-lg-row" action="{{ route('base.user_addresses.edit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="id" value="{{ $data->id }}">
            <input type="hidden" name="user_id" value="{{ $data->user_id }}">

            <!--begin::Main column-->
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <!--end:::Tabs-->
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <!--begin::Card header-->
                    <div class="card card-flush py-4">
                        <!--begin::Inventory-->
                        <div class="card-header">
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Usuario</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" value="{{ $data->user->full_name }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Calle</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInput" name="street" placeholder="Calle" value="{{ old('street', $data->street) }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <div class="row row-cols-1 row-cols-md-2">
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <label class="required form-label">Número Interior</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <x-layouts.inputs typeInput="justInputNumber" typeofInput="number" name="interior_number" placeholder="Número Interior" value="{{ old('interior_number', $data->interior_number) }}" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <label class="required form-label">Número Exterior</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <x-layouts.inputs typeInput="justInputNumber" typeofInput="number" name="external_number" placeholder="Número Exterior" value="{{ old('external_number', $data->external_number) }}" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                </div>

                                <div class="row row-cols-1 row-cols-md-2">
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <label class="required form-label">Colonia</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <x-layouts.inputs typeInput="justInput" name="colony" placeholder="Colonia" value="{{ old('colony', $data->colony) }}" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <label class="required form-label">Ciudad</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <x-layouts.inputs typeInput="justInput" name="city" placeholder="Ciudad" value="{{ old('city', $data->city) }}" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                </div>

                                <div class="row row-cols-1 row-cols-md-2">
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <label class="required form-label">Estado</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <x-layouts.inputs typeInput="justInput" name="state" placeholder="Estado" value="{{ old('state', $data->state) }}" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <label class="required form-label">Código Postal</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <x-layouts.inputs typeInput="justInputNumber" typeofInput="number" name="zip_code" placeholder="Código Postal" value="{{ old('zip_code', $data->zip_code) }}" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                </div>
                            </div>
                            <!--end::Card header-->
                        </div>
                        <!--end::Inventory-->
                    </div>
                    <!--end::Tab content-->
                    <x-btn-cancel-save routeCancel="{{ route('base.user.addresses', $data->user_id) }}" />
                </div>
            </div>
            <!--end::Main column-->
        </form>
    </div>
    {{-- code for the validations fields, is necesary use the id of the form in the GeneralForm.init('staffAdd') --}}
    <x-slot name="js">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script src="{{ asset('js/general-form.js') }}"></script>

        <script>
            let validations = [
                'name',
                'contact',
                'email',
                'phone',
                'url',
                'country_id',
                'designers[]'

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
        </script>
    </x-slot>
</x-layouts.master-layout>
