<x-layouts.master-layout title="Editar esquema de precios" cardTitle="Editar esquema de precios {{ $data->id }}">

    <div class="form_padding">
        <!--begin::Form-->
        <form id="staffAdd" class="form d-flex flex-column flex-lg-row" action="{{ route('base.pricing_schemes.edit') }}" method="POST">
            @csrf
            @method('PUT')
            <!--end::Aside column-->
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
                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                    <label class="required form-label">SKU 4</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="sku_4_id" id="sku_4_id"
                                        class="form-select form-control" data-control="select2" data-hide-search="false"
                                        data-placeholder="">
                                        <option selected hidden disabled>-- Elige una opción --</option>
                                        @foreach ($skus as $sku)
                                            <option value="{{ $sku->id }}" @selected($sku->id == old('sku_4_id', $data->sku_4_id))>
                                                {{ $sku->sku }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">SKU 8</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="sku_8_id" id="sku_8_id"
                                        class="form-select form-control" data-control="select2" data-hide-search="false"
                                        data-placeholder="">
                                        <option selected hidden disabled>-- Elige una opción --</option>
                                        @foreach ($skus as $sku)
                                            <option value="{{ $sku->id }}" @selected($sku->id == old('sku_8_id', $data->sku_8_id))>
                                                {{ $sku->sku }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Categoría</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="category_id" id="category_id"
                                        class="form-select form-control" data-control="select2" data-hide-search="false"
                                        data-placeholder="">
                                        <option selected hidden disabled>-- Elige una opción --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @selected($category->id == old('category', $data->category_id))>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">MSRP</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputNumber" typeofInput="number" name="msrp" id="msrp" placeholder="MSRP" min="0" value="{{ $data->msrp }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Incremento</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputNumber" typeofInput="number" name="increase" id="increase" placeholder="Incremento" min="0" value="{{ $data->increase }}" />
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
                    <x-btn-cancel-save 
                    routeCancel="{{ route('base.pricing_schemes.index') }}"
                    :module="ModuleAliases::PRICING_SCHEME"
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
                'sku_4_id',
                'sku_8_id',
                'msrp',
                'increase',

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