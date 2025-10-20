<x-layouts.master-layout title="Mostrar proveedor" cardTitle="{{ $data->street }} de {{ $data->user->full_name }}">
    <div class="form_padding">
        <!--begin::Form-->
        <div class="form d-flex flex-column flex-lg-row">
            <!--begin::Main column-->
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <!--end:::Tabs-->
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <!--begin::Inventory-->
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
                                    <x-layouts.inputs typeInput="justInputdisabled" value="{{ $data->street }}" />
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
                                        <x-layouts.inputs typeInput="justInputdisabled" value="{{ $data->interior_number ?? 'S/N' }}" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <label class="required form-label">Número Exterior</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <x-layouts.inputs typeInput="justInputdisabled" value="{{ $data->external_number ?? 'S/N' }}" />
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
                                        <x-layouts.inputs typeInput="justInputdisabled" value="{{ $data->colony }}" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <label class="required form-label">Ciudad</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <x-layouts.inputs typeInput="justInputdisabled" value="{{ $data->city }}" />
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
                                        <x-layouts.inputs typeInput="justInputdisabled" value="{{ $data->state }}" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <label class="required form-label">Código Postal</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <x-layouts.inputs typeInput="justInputdisabled" value="{{ $data->zip_code }}" />
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

                    <x-btn-cancel-save routeCancel="{{ route('base.user.addresses', $data->user_id) }}" isShow="true" routeEdit="{{ route('base.user_addresses.edit.view', $data->id) }}" />
                </div>
                <!--end::Main column-->
            </div>
        </div>
    </div>
</x-layouts.master-layout>
