<x-layouts.master-layout title="Crear tienda" cardTitle="Crear tienda">

    <div class="form_padding">
        <!--begin::Form-->
        <form id="staffAdd" class="form d-flex flex-column flex-lg-row" action="{{ route('base.store.create') }}" method="POST" enctype="multipart/form-data">
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
                                    <label class="required form-label">Nombre</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    {{-- <input type="text" name="sku" class="form-control mb-2"
                                        placeholder="SKU Number" value="" /> --}}
                                    <x-layouts.inputs typeInput="justInput" name="name" id="name" placeholder="Nombre" value="{{ old('name') }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Código</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    {{-- <input type="text" name="sku" class="form-control mb-2"
                                        placeholder="SKU Number" value="" /> --}}
                                    <x-layouts.inputs typeInput="justInputNumber" name="code" id="code" placeholder="W2nkRs" value="{{ old('code') }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <div class="mb-10 fv-row">
                                    <label class="required form-label">Marketplace</label>
                                    <select name="marketplace_id" id="marketplace_id" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                                        @foreach ($data['marketplaces'] as $marketplace)
                                            <option value="{{ $marketplace->id }}">
                                                {{ $marketplace->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Latitud</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputNumberCoords" typeofInput="number" name="lat" id="lat" placeholder="-77.6542" min="0" value="{{ old('lat') }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Longitud</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputNumberCoords" typeofInput="number" name="long" id="long" placeholder="34.7122" min="0" value="{{ old('long') }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Código postal</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    {{-- <input type="text" name="sku" class="form-control mb-2"
                                        placeholder="SKU Number" value="" /> --}}
                                    <x-layouts.inputs typeInput="justInputNumber" name="cp" id="cp" placeholder="75700" value="{{ old('cp') }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Dirección</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    {{-- <input type="text" name="sku" class="form-control mb-2"
                                        placeholder="SKU Number" value="" /> --}}
                                    <x-layouts.inputs typeInput="justInput" name="address" id="address" placeholder="REFORMA NORTE NO. 217 10, TEHUACAN CENTRO, 75700" value="{{ old('address') }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Municipio</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    {{-- <input type="text" name="sku" class="form-control mb-2"
                                        placeholder="SKU Number" value="" /> --}}
                                    <x-layouts.inputs typeInput="justInput" name="municipality" id="municipality" placeholder="Tehuacan" value="{{ old('municipality') }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Tipo tienda</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <select name="store_type" id="store_type" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                                            <option value="{{ StoreTypes::SHOP->value }}" @selected(StoreTypes::SHOP->value == old('status'))>Tienda</option>
                                            <option value="{{ StoreTypes::STORE->value }}" @selected(StoreTypes::STORE->value == old('status'))>Almacén</option>
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Estatus</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <select name="status" id="status" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                                            @foreach (StoreStatuses::getAllNames() as $value => $name)
                                                <option value="{{ $value }}" @if ($value == old('status')) selected @endif>{{ $name }}</option>
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
                    <!--end::Tab content-->
                    <div class="d-flex justify-content-end">
                        <!--begin::Button-->
                        <x-btn-create-cancel routeCancel="{{ route('base.store.index') }}" :module="ModuleAliases::STORE" />
                        <!--end::Button-->
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
                'code',
                'marketplace_id',
                'address',
                'lat',
                'long',
                'cp',
                'municipality',
                'store_type',
                'status'

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
