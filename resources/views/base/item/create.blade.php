<x-layouts.master-layout title="Crear Artículo" cardTitle="Crear Artículo">
    <div class="form_padding">
        <!--begin::Form-->
        <form id="staffAdd" class="form d-flex flex-column flex-lg-row" action="{{ route('base.item.create') }}" method="POST">
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
                                    <label class="required form-label">Producto</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="product_id" id="product_id" class="form-select form-control" data-control="select2" data-placeholder="Producto">
                                        @foreach ($data['products'] as $product)
                                            <option value="{{ $product->id }}" @selected($product->id == old('product_id'))>
                                                {{ $product->full_name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Variante</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="variant_id" id="variant_id" class="form-select form-control" data-control="select2" data-placeholder="Variante">
                                        @foreach ($data['variants'] as $variant)
                                            <option value="{{ $variant->id }}" @selected($variant->id == old('variant_id'))>
                                                {{ $variant->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Tienda</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="store_id" id="store_id" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="Tienda">
                                        @foreach ($data['stores'] as $store)
                                            <option value="{{ $store->id }}" @selected($store->id == old('store_id'))>
                                                {{ $store->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Código de barras</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputNumber" typeofInput="number" name="barcode" id="barcode" placeholder="Código de barras" min="0" value="{{ old('barcode') }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Precio venta</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputNumber" typeofInput="number" name="price_sale" id="price_sale" placeholder="Precio venta" min="0" value="{{ old('price_sale') }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Precio compra</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputNumber" typeofInput="number" name="price_purchase" id="price_purchase" placeholder="Precio compra" min="0" value="{{ old('price_purchase') }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Factura</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <select name="invoice_id" id="invoice_id" class="form-select form-control" data-control="select2" data-hide-search="false" data-placeholder="">
                                            <option selected hidden disabled>-- Elige una opción --</option>
                                            @foreach ($data['invoices'] as $invoice)
                                                <option value="{{ $invoice->id }}" @selected($invoice->id == old('invoice_id'))>
                                                    {{ $invoice->invoice_number }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Condición</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <select name="condition" id="condition" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                                            @foreach (ItemConditions::getAllNames() as $value => $name)
                                                <option value="{{ $value }}" @selected($value == old('condition'))>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Estado</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <select name="status" id="status" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                                            @foreach(ItemStatuses::getAllNames() as $value => $name)
                                                <option value="{{ $value }}" @selected($value == old('status'))>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Integridad</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <select name="integrity" id="integrity" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                                            @foreach(ItemIntegrities::getAllNames() as $value => $name)
                                                <option value="{{ $value }}" @selected($value == old('integrity'))>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Detalles</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <textarea class="form-control" name="details" id="details" cols="20" rows="5" placeholder="Detalles">{{ old('details') }}</textarea>
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
                    <x-btn-create-cancel routeCancel="{{ route('base.item.index') }}"
                    :module="ModuleAliases::ITEM"
                    />
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
                'product_id',
                'variant_id',
                'store_id',
                'price_sale',
                'price_purchase',
                'sale_type',
                'status',
                'integrity',
                'details',
            ].reduce((acc, field) => (acc[field] = {
                validators: {
                    notEmpty: {
                        message: '{{ __('validation.required', ['attribute' => ':attr']) }}'.replace(':attr',
                            field)
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
