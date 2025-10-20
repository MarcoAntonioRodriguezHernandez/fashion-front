<x-layouts.master-layout title="Mostrar mercado de pedido" :cardTitle="'Orden: ' . $data->code">

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
                                    <label class="form-label">Cliente</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    {{-- <input type="text" name="sku" class="form-control mb-2" placeholder="SKU Number" value="" /> --}}
                                    <x-layouts.inputs typeInput="justInputdisabled" name="client_id" id="client_id"
                                        placeholder="Nombre del cliente" value="{{ $data->client->name }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <div class="card-body pt-0">
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <input type="hidden" name="id" value="{{ $data->id }}">
                                        <label class="form-label">Empleado</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <x-layouts.inputs typeInput="justInputdisabled" name="employee_id" id="employee_id"
                                            placeholder="Nombre del empleado" value="{{ $data->employee->name ?? 'No asignado' }}" disabled />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Codigo</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="code" id="code"
                                        placeholder="Codigo" value="{{ $data->code }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                    <label class="form-label">Marketplace</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    {{-- <input type="text" name="sku" class="form-control mb-2" placeholder="SKU Number" value="" /> --}}
                                    <x-layouts.inputs typeInput="justInputdisabled" name="marketplace_id"
                                        id="marketplace_id" placeholder="Nombre de marketplace"
                                        value="{{ $data->marketplace->name }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Cantidad</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="amount" id="amount"
                                        placeholder="Cantidad" value="{{ $data->amount }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Descuento</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="discount" id="discount"
                                        placeholder="Descuento" value="{{ $data->discount }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Cantidad total</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="amount_total"
                                        id="amount_total" placeholder="Cantidad total" value="{{ $data->amount_total }}"
                                        disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Tienda</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputdisabled" typeofInput="text"
                                            name="store_id" id="store_id" placeholder="Tienda" min="0"
                                            value="{{ $data->store->name }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Número de productos</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputdisabled" typeofInput="number"
                                            name="number_products" id="number_products"
                                            placeholder="Numero de productos" min="0"
                                            value="{{ $data->number_products }}" />
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
                                        <x-layouts.inputs typeInput="justInputdisabled" typeofInput="text"
                                            name="status" id="status" placeholder="Valor" min="0"
                                            value="{{ $data->status == 1 ? 'Vigente' : 'Cancelado' }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Card header-->
                        </div>
                        <!--end::Inventory-->
                        {{--@if ($data->status == 1)
                            <form method="GET" action="{{ route('base.notifications.cancel', ['orderId' => $data->id]) }}">
                                @csrf
                                <div class="mt-3 d-flex justify-content-end mx-4">
                                    <button type="submit" class="btn btn-secondary" data-kt-notification>Cancelar
                                        Orden</button>
                                </div>
                            </form>
                        @endif--}}
                    </div>
                    <!--end::Tab content-->
                    <x-btn-cancel-save routeCancel="{{ route('marketplace.order_marketplace.index') }}"
                        isShow="true"
                        routeEdit="{{ route('marketplace.order_marketplace.edit.view', $data->id) }}" />
                </div>
            </div>
</x-layouts.master-layout>
