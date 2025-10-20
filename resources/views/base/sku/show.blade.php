<x-layouts.master-layout title="Mostrar Sku" :cardTitle="$data->name">

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
                                    <label class="form-label">Código de referencia</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    {{-- <input type="text" name="sku" class="form-control mb-2" placeholder="SKU Number" value="" /> --}}
                                    <x-layouts.inputs typeInput="justInputdisabled" name="Sku" id="sku" placeholder="Código de referencia" value="{{ $data->sku }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Nombre</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    {{-- <input type="text" name="sku" class="form-control mb-2" placeholder="SKU Number" value="" /> --}}
                                    <x-layouts.inputs typeInput="justInputdisabled" name="name" id="name" placeholder="Nombre" value="{{ $data->name }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Descripción</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <textarea class="form-control" name="description" id="description" cols="20" rows="5" disabled>{{ $data->description }}</textarea>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Duración</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputdisabled" typeofInput="number" name="duration" id="duration" placeholder="Duración" min="0" value="{{ $data->duration }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Precio</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputdisabled" typeofInput="number" name="price" id="price" placeholder="Precio" min="0" value="{{ $data->price }}" />
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
                    :module="ModuleAliases::SKU"
                    routeCancel="{{ route('base.sku.index') }}"
                    isShow="true"
                    routeEdit="{{ route('base.sku.edit.view', $data->id) }}"
                    />
                </div>
                <!--end::Main column-->
            </div>
        </div>
</x-layouts.master-layout>