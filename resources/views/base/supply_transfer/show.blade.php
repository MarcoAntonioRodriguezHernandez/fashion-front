<x-layouts.master-layout title="Mostrar transferencia de Distribución" cardTitle="Mostrar transferencia de Distribución">
    <div class="form_padding">
        <!--begin::Form-->
        <div class="form d-flex flex-column flex-lg-row">
        </div>
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
                                <label class="form-label">Código de la distribución</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <div class="d-flex gap-3">
                                    <x-layouts.inputs typeInput="justInputdisabled" typeofInput="code" placeholder="Valor" min="0" value="{{ $data->supply->code }}" />
                                </div>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-10 fv-row">
                                <!--begin::Label-->
                                <label class="form-label">Fecha de Recepción</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <div class="d-flex gap-3">
                                    <x-layouts.inputs typeInput="justInputdisabled" typeofInput="shipping_date" placeholder="Valor" min="0" value="{{ $data->reception_date ?? 'No Recibido' }}" />
                                </div>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-10 fv-row">
                                <!--begin::Label-->
                                <label class="form-label">Receptor</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <x-layouts.inputs typeInput="justInputdisabled" placeholder="Remitente" value="{{ $data->recipient?->full_name ?? 'Ninguna persona ha recibido' }}" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-10 fv-row">
                                <!--begin::Label-->
                                <label class="form-label">Origen</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <x-layouts.inputs typeInput="justInputdisabled" placeholder="Remitente" value="{{ $data->origin->name }}" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-10 fv-row">
                                <!--begin::Label-->
                                <label class="form-label">Destino</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <x-layouts.inputs typeInput="justInputdisabled" placeholder="Remitente" value="{{ $data->destination->name }}" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--end::Card header-->
                    </div>
                    <!--end::Inventory-->
                </div>
                <!--end::Tab content-->
                <x-btn-cancel-save routeCancel="{{ route('base.supply_transfer.index') }}" isShow="true" routeEdit="{{ route('base.supply_transfer.edit.view', $data->id) }}">
                    <!--begin::Button-->
                    <a href="{{ route('base.supply.show', $data->supply_id) }}" class="btn btn-info me-5">Ver distribución</a>
                    <!--end::Button-->
                </x-btn-cancel-save>
            </div>
            <!--end::Main column-->
        </div>
    </div>
   
    <div class="form_padding">
        <x-supply.transfer :user="$data->recipient" :origin="$data->origin" :destination="$data->destination" :receptionDate="$data->reception_date" :items="$data->suppliedItems" />
    </div>

</x-layouts.master-layout>
