<x-layouts.master-layout title="Mostrar proveedor" :cardTitle="$data->name">

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
                                    <label class="required form-label">Nombre</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" value="{{ $data->name }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Contacto</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" value="{{ $data->contact }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Correo Electrónico</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" value="{{ $data->email }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Telefóno</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" value="{{ $data->phone }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">URL</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" value="{{ $data->url }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">País</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" value="{{ $data->country->name }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Marcas / Diseñadores</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="col d-flex flex-wrap gap-3">
                                        @foreach ($data->designers as $designer)
                                            <span class="badge py-3 px-4 fs-7 badge-light-dark bg-gray-300">{{ $designer->name }}</span>
                                        @endforeach
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
                        :module="ModuleAliases::PROVIDER"
                        routeCancel="{{ route('base.provider.index') }}"
                        isShow="true"
                        routeEdit="{{ route('base.provider.edit.view', $data->id) }}" />
                </div>
                <!--end::Main column-->
            </div>
        </div>
    </div>
</x-layouts.master-layout>
