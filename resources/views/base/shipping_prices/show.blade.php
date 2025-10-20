<x-layouts.master-layout title="Mostrar Precio de Envío" :cardTitle="$data->name">

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
                                    <label class="form-label">Nombre</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="name" id="name" placeholder="Nombre" value="{{ $data->name }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Código</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="code" id="code" placeholder="Código" value="{{ $data->code }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Precio</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="price" id="price" placeholder="Precio" value="{{ $data->price }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Card header-->
                            </div>
                            <!--end::Inventory-->
                        </div>
                        <!--end::Tab content-->
                        <x-btn-cancel-save 
                            :module="ModuleAliases::SHIPPING_PRICE"
                            routeCancel="{{ route('base.shipping_prices.index') }}" isShow="true"
                            routeEdit="{{ route('base.shipping_prices.edit.view', $data->id) }}" />
                    </div>
                    <!--end::Main column-->
                </div>
            </div>
</x-layouts.master-layout>
