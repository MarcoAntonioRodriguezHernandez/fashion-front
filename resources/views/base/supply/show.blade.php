<x-layouts.master-layout title="Mostrar Distribución" cardTitle="Mostrar Distribución">
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
                                <label class="form-label">Remitente</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <x-layouts.inputs typeInput="justInputdisabled" name="sender_id" id="sender_id"
                                    placeholder="Remitente" value="{{ $data->sender->full_name }}" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-10 fv-row">
                                <!--begin::Label-->
                                <label class="form-label">Código</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <div class="d-flex gap-3">
                                    <x-layouts.inputs typeInput="justInputdisabled" typeofInput="code" name="code"
                                        id="code" placeholder="Valor" min="0" value="{{ $data->code }}" />
                                </div>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-10 fv-row">
                                <!--begin::Label-->
                                <label class="form-label">Fecha de Creación</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <div class="d-flex gap-3">
                                    <x-layouts.inputs typeInput="justInputdisabled" typeofInput="shipping_date"
                                        name="shipping_date" id="shipping_date" placeholder="Valor" min="0"
                                        value="{{ $data->shipping_date }}" />
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
                                    <x-layouts.inputs typeInput="justInputdisabled" typeofInput="text" name="status"
                                        id="status" placeholder="Status" min="0"
                                        value="{{ $data->status_name }}" />
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
                <x-btn-cancel-save :module="ModuleAliases::SUPPLY" routeCancel="{{ route('base.supply.index') }}" isShow="true" routeEdit="{{ route('base.supply.edit.view', $data->id) }}">
                    @if ($data->supplyTransfers()->count() > 0)
                        <!--begin::Button-->
                        <a href="{{ route('base.supply.report.print', $data->id) }}" class="btn btn-info me-5" target="_blank">
                            Generar Reporte
                        </a>
                        <!--end::Button-->
                    @else
                        <!--begin::Span-->
                        <span class="btn btn-info me-5 disabled">
                            Sin Transferencias
                        </span>
                        <!--end::Span-->
                    @endif
                </x-btn-cancel-save>
            </div>
            <!--end::Main column-->
        </div>
    </div>

    <div class="form_padding">
        <!--begin::Aside column-->
        <div class="form d-flex flex-column flex-lg-row"></div>
        <!--end::Aside column-->
        <!--begin::Main column-->
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <!--end:::Tabs-->
            <div class="d-flex flex-column gap-7 gap-lg-10">
                <!--begin::Inventory-->
                @forelse ($data->supplyTransfers as $supplyTransfer)
                    <x-supply.transfer :user="$supplyTransfer->recipient" :origin="$supplyTransfer->origin" :destination="$supplyTransfer->destination" :receptionDate="$supplyTransfer->reception_date" :items="$supplyTransfer->suppliedItems" :url="route('base.supply_transfer.edit.view', $supplyTransfer->id)" />
                @empty
                    <div class="card">
                        <div class="card-body text-center">
                            Sin transferencias
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
        <!--end::Main column-->
    </div>
    <!--end::Main column-->
    </div>

    <x-slot name="js">
        <script>
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        </script>
    </x-slot>

</x-layouts.master-layout>
