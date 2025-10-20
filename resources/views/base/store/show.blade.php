<x-layouts.master-layout title="Mostrar tienda" :cardTitle="$data->name">

    <div class="form_padding">
        <!--begin::Form-->
        <div class="form d-flex flex-column flex-lg-row">
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
                                    <label class="form-label">Nombre</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    {{-- <input type="text" name="sku" class="form-control mb-2"
                                        placeholder="SKU Number" value="" /> --}}
                                    <x-layouts.inputs typeInput="justInputdisabled" name="name" id="name" placeholder="Nombre" value="{{ $data->name }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class=" form-label">Código</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    {{-- <input type="text" name="sku" class="form-control mb-2"
                                        placeholder="SKU Number" value="" /> --}}
                                    <x-layouts.inputs typeInput="justInputdisabled" name="code" id="code" placeholder="W2nkRs" value="{{ $data->code }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class=" form-label">Marketplace</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    {{-- <input type="text" name="sku" class="form-control mb-2"
                                        placeholder="SKU Number" value="" /> --}}
                                    <x-layouts.inputs typeInput="justInputdisabled" name="marketplace" id="marketplace" placeholder="Flagship" value="{{ $data->marketplace->name }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class=" form-label">Latitud</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputdisabled" typeofInput="number" name="lat" id="lat" placeholder="-77.6542" min="0" value="{{ $data->lat }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class=" form-label">Longitud</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputdisabled" typeofInput="number" name="long" id="long" placeholder="34.7122" min="0" value="{{ $data->long }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class=" form-label">Código postal</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    {{-- <input type="text" name="sku" class="form-control mb-2"
                                        placeholder="SKU Number" value="" /> --}}
                                    <x-layouts.inputs typeInput="justInputdisabled" name="cp" id="cp" placeholder="75700" value="{{ $data->cp }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class=" form-label">Dirección</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    {{-- <input type="text" name="sku" class="form-control mb-2"
                                        placeholder="SKU Number" value="" /> --}}
                                    <x-layouts.inputs typeInput="justInputdisabled" name="address" id="address" placeholder="Tehuacan" value="{{ $data->address }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class=" form-label">Municipio</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    {{-- <input type="text" name="sku" class="form-control mb-2"
                                        placeholder="SKU Number" value="" /> --}}
                                    <x-layouts.inputs typeInput="justInputdisabled" name="municipality" id="municipality" placeholder="Tehuacan" value="{{ $data->municipality }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class=" form-label">Tipo tienda</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputdisabled" typeofInput="text" value="{{ $data->storeName }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Estatus</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputdisabled" typeofInput="text" name="status" id="status" placeholder="Status" min="0" value="{{ $data->status_name }}" />
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
                    <x-btn-cancel-save :module="ModuleAliases::STORE" routeCancel="{{ route('base.store.index') }}" isShow="true" routeEdit="{{ route('base.store.edit.view', $data->id) }}" />
                </div>
                <!--end::Main column-->
            </div>
        </div>
</x-layouts.master-layout>
