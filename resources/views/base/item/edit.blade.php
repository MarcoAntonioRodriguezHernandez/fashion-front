<x-layouts.master-layout title="Artículo {{ $data->serial_number }}" cardTitle="Artículo {{ $data->serial_number }}">
    <div class="form_padding">
        <!--begin::Form-->
        <form id="staffAdd" class="form d-flex flex-column flex-lg-row" action="{{ route('base.item.edit') }}"
            method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="id" value="{{ $data->id }}">

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
                                    <select name="product_id" id="product_id" class="form-select form-control"
                                        data-control="select2" data-placeholder="Producto">
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" @selected($product->id == old('product_id', $data->productVariant->product_id))>
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
                                    <div class="row row-cols-1 row-cols-md-2">
                                        <div class="col">

                                            <label class="required form-label">Color</label>
                                            <select name="color_id" id="color_id" class="form-select form-control"
                                                data-control="select2" data-placeholder="Color">
                                                @foreach ($colors as $color)
                                                    <option value="{{ $color->id }}"
                                                        @if ($color->id == $colorId) selected @endif>
                                                        {{ $color->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="required form-label">Talla</label>
                                            <select name="size_id" id="size_id" class="form-select form-control"
                                                data-control="select2" data-placeholder="Talla">
                                                @foreach ($sizes as $size)
                                                    <option value="{{ $size->id }}"
                                                        @if ($size->id == $sizeId) selected @endif>
                                                        {{ $size->full_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Tienda</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="store_id" id="store_id" class="form-select form-control"
                                        data-control="select2" data-hide-search="true" data-placeholder="Tienda"
                                        disabled>
                                        @foreach ($stores as $store)
                                            <option value="{{ $store->id }}" @selected($store->id == old('store_id', $data->store_id))>
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
                                        <x-layouts.inputs typeInput="justInputNumber" typeofInput="number"
                                            name="barcode" id="barcode" placeholder="Código de barras" min="0"
                                            value="{{ old('barcode', $data->barcode) }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Número de serie</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputdisabled" typeofInput="text"
                                            placeholder="Número de serie" value="{{ $data->serial_number }}" />
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
                                        <x-layouts.inputs typeInput="justInputNumber" typeofInput="number"
                                            name="price_sale" id="price_sale" placeholder="Precio venta" min="0"
                                            value="{{ old('price_sale', $data->price_sale) }}" />
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
                                        <x-layouts.inputs typeInput="justInputNumber" typeofInput="number"
                                            name="price_purchase" id="price_purchase" placeholder="Precio compra"
                                            min="0"
                                            value="{{ old('price_purchase', $data->price_purchase) }}" />
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
                                        <select name="invoice_id" id="invoice_id" class="form-select form-control"
                                            data-control="select2" data-hide-search="false" data-placeholder="">
                                            <option selected hidden disabled>-- Elige una opción --</option>
                                            @foreach ($invoices as $invoice)
                                                <option value="{{ $invoice->id }}" @selected($invoice->id == old('invoice_id', $data->invoice_id))>
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
                                        <select name="condition" id="condition" class="form-select form-control"
                                            data-control="select2" data-hide-search="true" data-placeholder="">
                                            @foreach (ItemConditions::getAllNames() as $value => $name)
                                                <option
                                                    new-price="{{ ItemConditions::getPriceFunction($value)($data->product, $data) }}"
                                                    value="{{ $value }}" @selected($value == old('condition', $data->condition))>
                                                    {{ $name }}</option>
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
                                        <select name="status" id="status" class="form-select form-control"
                                            data-control="select2" data-hide-search="true" data-placeholder="">
                                            @foreach (ItemStatuses::getAllNames() as $value => $name)
                                                <option value="{{ $value }}" @selected($value == old('status', $data->status))>
                                                    {{ $name }}</option>
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
                                        <select name="integrity" id="integrity" class="form-select form-control"
                                            data-control="select2" data-hide-search="true" data-placeholder="">
                                            @foreach (ItemIntegrities::getAllNames() as $value => $name)
                                                <option value="{{ $value }}" @selected($value == old('integrity', $data->integrity))>
                                                    {{ $name }}</option>
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
                                        <textarea class="form-control" name="details" id="details" cols="20" rows="5" placeholder="Detalles">{{ old('details', $data->details) }}</textarea>
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
                    <x-btn-cancel-save routeCancel="{{ route('base.item.index') }}" :module="ModuleAliases::ITEM" />
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
            const salePriceElement = document.getElementById('price_sale');

            $('#condition').on('select2:select', (e) => {
                salePriceElement.value = e.target.querySelector('option:checked').getAttribute('new-price');

                // Color the view
                salePriceElement.animate([{
                    backgroundColor: "#F2B84B"
                }], {
                    duration: 1000,
                    fill: 'forwards',
                });

                setTimeout(() => {
                    // Go back to transparent
                    salePriceElement.animate({
                        backgroundColor: "#00000000"
                    }, {
                        duration: 1000,
                        fill: 'forwards',
                    });
                }, 1500);
            });
        </script>

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
