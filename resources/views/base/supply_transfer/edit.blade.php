<x-layouts.master-layout title="Editar Transferencia" cardTitle="Editar Transferencia">
    <div class="form_padding">
        <!--begin::Form-->
        <div class="form d-flex flex-column flex-lg-row">
        </div>
        <!--end::Aside column-->
        <!--begin::Main column-->
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <!--end:::Tabs-->
            <div class="d-flex flex-column gap-7 gap-lg-10">
                <!--begin::Tab content-->
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
                                    <x-layouts.inputs typeInput="justInputdisabled" typeofInput="shipping_date"
                                        placeholder="Valor" min="0"
                                        value="{{ $data->reception_date ?? 'No Recibido' }}" />
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
            </div>
            <!--end::Main column-->
        </div>
    </div>

    <form class="form_padding" action="{{ route('base.supply_transfer.edit') }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="id" value="{{ $data->id }}">

        <div class="card card-flush py-4 mb-10">
            <h2 class="m-6">Articulos</h2>
            <!--begin::Card header-->
            <div class="card-header">
                <!--begin::Card body-->
                <div class="card-body col-12 p-0">
                    <div class="row row-cols-2">
                        @forelse ($data->suppliedItems as $suppliedItem)
                            <div class="col p-3">
                                @php
                                    $locked = (bool) ($suppliedItem->is_locked ?? false);
                                    $toSupplyId = $suppliedItem->redirected_to_supply_id ?? null;
                                @endphp
                                <x-supply.item-edit :suppliedItem="$suppliedItem" :locked="$locked" />
                            </div>
                        @empty
                            <div class="card">
                                <div class="card-body text-center">
                                    Sin Artículos
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
                <!--end::Main column-->
            </div>
            <!--end::Main column-->
        </div>

        <x-btn-cancel-save routeCancel="{{ route('base.supply_transfer.index') }}" />
    </form>

    <x-slot name="js">
        @vite(['resources/js/supply/item-edit.js'])

        <script type="module">
            document.querySelectorAll('.status-container').forEach((container) => {
                toggleChildVisibility(container, '.error-fields', (container.querySelector('select').value == {{ SupplyStatuses::ERROR->value }}));
            });
        </script>

        <script>
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        </script>
    </x-slot>
</x-layouts.master-layout>
