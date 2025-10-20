<x-layouts.master-layout title="Mostrar variante" cardTitle="Variante {{ $data->code}}">

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
                                    <label class="form-label">Talla</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="size" id="size" placeholder="Talla" value="{{ $data->size->full_name }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Color</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div style="position: relative; display: flex; align-items: center;roofjroiosoek meido teykfiej
                                        <x-layouts.inputs typeInput="justInputdisabled" name="color" id="color" placeholder="Color" value="{{ $data->color->name }}" disabled  />
                                            <div id="color_hexadecimal" style="width: 45px; height: 45px; border: 0.5px solid #F1F1F1; margin-left: 1em; border-radius: 0.475rem; background-color: {{ $data->color->hexadecimal }}"></div>
                                    </div>
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
                                    <label class="form-label">Estatus</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputdisabled" typeofInput="text" name="value" id="quantity" placeholder="Valor" min="0" value="{{ $data->status == 1 ? 'Activo' : 'Inactivo' }}" />
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
                    <x-btn-cancel-save :module="ModuleAliases::VARIANT"
                    routeCancel="{{ route('base.variant.index') }}"
                    isShow="true"
                    routeEdit="{{ route('base.variant.edit.view', $data->id) }}"
                    />
                </div>
                <!--end::Main column-->
            </div>
        </div>
</x-layouts.master-layout>
