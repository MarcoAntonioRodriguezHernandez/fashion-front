<x-layouts.master-layout title="Crear proveedor" cardTitle="Crear proveedor">

    <div class="form_padding">
        <!--begin::Form-->
        <form id="staffAdd" class="form d-flex flex-column flex-lg-row" action="{{ route('base.provider.create') }}" method="POST" enctype="multipart/form-data">
            @csrf

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
                                    <label class="required form-label">Nombre</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInput" name="name" id="name" placeholder="Nombre" value="{{ old('name') }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Contacto</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInput" name="contact" id="contact" placeholder="Contacto" value="{{ old('contact') }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Correo Electrónico</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInput" name="email" id="email" placeholder="Correo Electrónico" value="{{ old('email') }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Telefóno</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInput" name="phone" id="phone" placeholder="Telefóno" value="{{ old('phone') }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">URL</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInput" name="url" id="url" placeholder="URL" value="{{ old('url') }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">País</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <select name="country_id" id="country_id" class="form-select form-control" data-control="select2" data-hide-search="false" data-placeholder="">
                                            <option selected hidden disabled>-- Elige una opción --</option>
                                            @foreach ($data['countries'] as $country)
                                                <option value="{{ $country->id }}" @selected($country->id == old('country_id'))>
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Marcas / Diseñadores</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <select class="form-control select2" data-control="select2" data-tags="true" multiple name="designers[]" id="designers">
                                            <option label="Label"></option>
                                            @foreach ($data['designers'] as $designer)
                                                <option value="{{ $designer->id }}" @selected(in_array($designer->id, old('designers', [])))>
                                                    {{ $designer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Card header-->
                        </div>
                        <!--end::Inventory-->
                    </div>
                    <!--end::Card header-->
                    <!--end::Tab content-->
                    <x-btn-create-cancel 
                    routeCancel="{{ route('base.provider.index') }}"
                    :module="ModuleAliases::PROVIDER"
                    />
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
