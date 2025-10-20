<x-layouts.master-layout title="Crear Sku" cardTitle="Crear Sku">

    <div class="form_padding">
        <!--begin::Form-->
        <form id="staffAdd" class="form d-flex flex-column flex-lg-row" action="{{ route('base.sku.create') }}" method="POST">
            @csrf
            <!--begin::Main column-->
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <!--end:::Tabs-->
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <!--begin::Inventory-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Código de referencia</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    {{-- <input type="text" name="sku" class="form-control mb-2" placeholder="SKU" value="" /> --}}
                                    <x-layouts.inputs typeInput="justInput" name="sku" id="sku"
                                        placeholder="Código de referencia" value="{{ old('sku') }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Nombre</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    {{-- <input type="text" name="sku" class="form-control mb-2" placeholder="SKU Number" value="" /> --}}
                                    <x-layouts.inputs typeInput="justInput" name="name" id="name"
                                        placeholder="Nombre" value="{{ old('name') }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Descripción</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <textarea class="form-control" name="description" id="description" cols="20" rows="5">{{ old('description') }}</textarea>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Duración</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputNumber" typeofInput="number" name="duration" id="duration" placeholder="Duración" min="0" value="{{ old('duration') }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Precio</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputNumber" typeofInput="number" name="price" id="price" placeholder="Precio" min="0" value="{{ old('price') }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Card header-->
                        </div>
                        <!--end::Inventory-->
                    </div>
                    <!--end::Tab content-->
                    <x-btn-create-cancel 
                    routeCancel="{{ route('base.sku.index') }}"
                    :module="ModuleAliases::SKU"
                    />
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
                'sku',
                'name',
                'description',
                'duration',
                'price',

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