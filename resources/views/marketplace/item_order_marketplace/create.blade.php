<x-layouts.master-layout title="Crear Órden de Artículo" cardTitle="Crear Órden de Artículo">
    <div class="form_padding">
        <!--begin::Form-->
        <form id="staffAdd" class="form d-flex flex-column flex-lg-row" action="{{ route('marketplace.item_order_marketplace.create') }}" method="POST">
            @csrf

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
                                    <label class="required form-label">Artículo</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="item_id" id="item_id" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                                        @foreach ($data['items'] as $item)
                                            <option value="{{ $item->id }}" @selected($item->id == old('item_id'))>
                                                {{ $item->barcode }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Órden</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="order_marketplace_id" id="order_marketplace_id" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                                        @foreach ($data['orderMarketplace'] as $order)
                                            <option value="{{ $order->id }}" @selected($order->id == old('order_marketplace_id'))>
                                                {{ $order->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Notas Adicionales</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <textarea class="form-control" name="additional_notes" id="additional_notes" cols="20" rows="5" placeholder="Notas Adicionales">{{ old('additional_notes') }}</textarea>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Precio de los Ajustes</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInput" typeofInput="number" name="fitting_price" id="fitting_price" placeholder="Precio de los Ajustes" min="0" value="{{ old('fitting_price') }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Notas de Ajustes</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <textarea class="form-control" name="fitting_notes" id="fitting_notes" cols="20" rows="5" placeholder="Notas de Ajustes">{{ old('fitting_notes') }}</textarea>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Tipo de Venta</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <select name="sale_type" id="sale_type" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                                            <option value="{{ OrderSaleTypes::RENT->value }}" @selected(OrderSaleTypes::RENT->value == old('sale_type'))>Renta</option>
                                            <option value="{{ OrderSaleTypes::SALE->value }}" @selected(OrderSaleTypes::SALE->value == old('sale_type'))>Venta</option>
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Estatus</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <select name="status" id="status" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                                            <option value="1" @selected(1 == old('status'))>Activo</option>
                                            <option value="0" @selected(0 == old('status'))>Inactivo</option>
                                        </select>
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
                    <div class="d-flex justify-content-end">
                        <!--begin::Button-->
                        <a href="{{ route('marketplace.item_order_marketplace.index') }}" id="kt_ecommerce_add_product_cancel" class="btn btn-light me-5">Cancelar</a>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Guardar</span>
                        </button>
                        <!--end::Button-->
                    </div>
                </div>
                <!--end::Main column-->
            </div>
        </form>
    </div>
    {{-- code for the validations fields, is necesary use the id of the form in the GeneralForm.init('staffAdd') --}}
    <x-slot name="js">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script src="{{ asset('js/general-form.js') }}"></script>

        <script>
            let validations = [
                'item_id',
                'order_marketplace_id',
                'code',
                'sale_type',
                'status',
            ].reduce((acc, field) => (acc[field] = {
                validators: {
                    notEmpty: {
                        message: '{{ __('validation.required', ['attribute' => ':attr']) }}'.replace(':attr', field)
                    }
                }
            }, acc), {});

            window.addEventListener('load', () => {
                GeneralForm.init('staffAdd', validations,
                    'Error en la validación de los campos, por favor verifique los datos e intente de nuevo.')
            });
        </script>
    </x-slot>
</x-layouts.master-layout>
