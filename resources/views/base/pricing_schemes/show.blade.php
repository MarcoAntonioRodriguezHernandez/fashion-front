<x-layouts.master-layout title="Mostrar Esquema de precios" cardTitle="Esquemas de precios {{ $data->id }}">

    <div class="form_padding">
        <!--begin::Form-->
        <div class="form d-flex flex-column flex-lg-row">
            <!--begin::Aside column-->
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
                                    <label class="form-label">SKU 4</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    {{-- <input type="text" name="sku_4" class="form-control mb-2" placeholder="SKU 4" value="" /> --}}
                                    <x-layouts.inputs typeInput="justInputdisabled" name="sku_4" id="sku_4" placeholder="SKU 4" value="{{ $data->sku_4->sku }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">SKU 8</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="sku_8" id="sku_8" placeholder="SKU 8" value="{{ $data->sku_8->sku }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Categoría</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="category_id" id="category_id" placeholder="Categoría" value="{{ $data->category->name }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">MSRP</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="msrp" id="msrp" placeholder="MSRP" value="{{ $data->msrp }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Incremento</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="increase" id="increase" placeholder="Incremento" value="{{ $data->increase }}" disabled />
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
                    :module="ModuleAliases::PRICING_SCHEME"
                    routeCancel="{{ route('base.pricing_schemes.index') }}"
                    isShow="true"
                    routeEdit="{{ route('base.pricing_schemes.edit.view', $data->id) }}"
                    />
                </div>
                <!--end::Main column-->
            </div>
        </div>
    </div>
</x-layouts.master-layout>