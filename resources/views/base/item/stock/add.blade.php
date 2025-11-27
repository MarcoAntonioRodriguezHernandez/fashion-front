<x-layouts.master-layout title="Agregar Inventario" cardTitle="Agregar Inventario">
    <div class="form_padding">
        <!--begin::Form-->
        <form id="staffAdd" class="form d-flex flex-column flex-lg-row" action="{{ route('base.stock.add') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            <!--begin::Main column-->
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <!--end:::Tabs-->
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <!--begin::Inventory-->
                    <div class="card card-flush py-4 mt-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card body-->
                            <div class="card-body">
                                <div class="col row row-cols-1 row-cols-lg-2 row-cols-xl-2">
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <label class="required form-label">Producto</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select name="product_id" class="form-select form-control"
                                            data-control="select2" data-placeholder="Producto"
                                            onchange="updateProductPrices(this)">
                                            <option selected hidden disabled>-- Elige un producto --</option>
                                            @foreach ($data['products'] as $product)
                                                <option value="{{ $product->id }}" @selected($product->id == old('product_id'))
                                                    data-price-purchase="{{ $product->items()->latest()->first()?->price_purchase ?? 0 }}"
                                                    data-price-sale="{{ $product->full_price }}">
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
                                        <label class="required form-label">Precio compra</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <div class="d-flex gap-3">
                                            <x-layouts.inputs typeInput="justInputNumber" typeofInput="number"
                                                name="price_purchase" id="price_purchase" placeholder="Precio compra"
                                                min="0" step="0.01" value="{{ old('price_purchase') }}" />
                                        </div>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                </div>
                            </div>
                        </div>
                        <!--end::Card header-->
                    </div>
                    <!--end::Inventory-->

                    <h2>Datos de Facturación</h2>
                    <x-product.invoice-selection :users="$data['users']" />

                    <!--begin::Items-->
                    <div class="mt-8">
                        <h2>Agregar Modelos</h2>
                    </div>

                    <div class="card card-flush py-4 mt-10">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card body-->
                            <div class="card-body pt-0 mt-5">
                                @php
                                    $itemStatuses = \App\Enums\ItemStatuses::getAllNames();
                                @endphp

                                <x-product.item-selection :colors="$data['colors']" :sizes="$data['sizes']" :statuses="$itemStatuses" />
                            </div>
                        </div>
                    </div>
                    <!--end::Items-->

                    <!--begin::Inventory Container-->
                    <div id="inventory-title" class="col-12 d-none">
                        <h2 class="my-3">Inventario Agregado</h2>
                        <hr>
                    </div>
                    <!--end::Inventory Container-->

                    <div id="inventory-container" class="col-12">
                    </div>

                    <!--begin::Action buttons-->
                    <div class="d-flex justify-content-end" id="save-button-container">
                        <!--begin::Button-->
                        <a href="{{ route('base.item.index') }}" id="kt_ecommerce_add_product_cancel"
                            class="btn btn-light me-5">Cancelar</a>

                        <button type="submit" class="btn btn-primary me-5">Agregar</button>
                        <!--end::Button-->
                    </div>
                    <!--end::Action buttons-->
                </div>
                <!--end::Main column-->
            </div>
        </form>
    </div>

    {{-- code for the validations fields, is necesary use the id of the form in the GeneralForm.init('staffAdd') --}}
    <x-slot name="js">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{ Vite::asset('resources/js/product/item-selection.js') }}"></script>
        <script src="{{ Vite::asset('resources/js/product/invoice-selection.js') }}"></script>
        <script src="{{ asset('js/general-form.js') }}"></script>

        <script>
            let validations = [
                'product_id',
                'price_purchase',
            ].reduce((acc, field) => (acc[field] = {
                validators: {
                    notEmpty: {
                        message: '{{ __('validation.required', ['attribute' => ':attr']) }}'.replace(':attr', field)
                    }
                }
            }, acc), {});

            window.addEventListener('load', () => {
                GeneralForm.init('staffAdd', validations,
                    'Error en la validación de los campos, por favor verifique los datos e intente de nuevo.'
                );
            });

            const allColors = @json($data['colors']);

            let productColors = [];

            async function updateProductPrices(selectElement) {
                const selectedOption = selectElement.options[selectElement.selectedIndex];
                const productId = selectedOption.value;

                document.querySelector('input[name="price_purchase"]').value = selectedOption.dataset.pricePurchase;
                document.querySelector('input[id="price_sale"]').value = selectedOption.dataset.priceSale;

                try {
                    const response = await fetch(`/stock/product/${productId}/colors`);
                    const data = await response.json();

                    if (data.success) {
                        productColors = data.colors;
                    } else {
                        productColors = [];
                    }
                    updateColorDropdown(productColors);
                } catch (error) {
                    console.error('Error al obtener colores:', error);
                    updateColorDropdown([]);
                }
            }

            function updateColorDropdown(colors) {
                const colorSelect = document.getElementById('color_id');
                if (!colorSelect) return;

                colorSelect.innerHTML = '<option selected hidden disabled>-- Elige un color --</option>';

                colors
                    .filter(color => color.parent_color_id !== null) 
                    .forEach(color => {
                        const option = document.createElement('option');
                        option.value = color.id;
                        option.textContent = color.name;
                        option.dataset.hasImage = color.has_image;
                        colorSelect.appendChild(option);
                    });
            }

            function handleColorSelection(selectElement) {
                const selectedOption = selectElement.options[selectElement.selectedIndex];
                const hasImage = selectedOption.dataset.hasImage === 'true';

                if (!hasImage) {
                    // Obtenemos el producto seleccionado
                    const productSelect = document.querySelector('select[name="product_id"]');
                    const productId = productSelect.value;

                    Swal.fire({
                        title: 'No hay ninguna imagen asociada con el color seleccionado',
                        html: 'Si deseas agregarla, ve a la edición de productos.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Editar Producto',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirige a la edición del producto
                            window.location.href = `/product/${productId}/edit`;
                        } else {
                            selectElement.value = '';
                        }
                    });
                }
            }

            function openAddVariantModal() {
                Swal.fire({
                    title: 'Agregar nueva variante',
                    html: `
                        <input type="text" id="newColorName" class="swal2-input" placeholder="Nombre del color">
                        <input type="text" id="newColorSlug" class="swal2-input" placeholder="Slug del color">
                    `,
                    confirmButtonText: 'Guardar',
                    focusConfirm: false,
                    preConfirm: () => {
                        const name = document.getElementById('newColorName').value;
                        const slug = document.getElementById('newColorSlug').value;
                        if (!name || !slug) Swal.showValidationMessage('Por favor completa ambos campos');
                        return { name, slug };
                    }
                }).then(result => {
                    if (result.isConfirmed) {
                        Swal.fire('Guardado', 'El color ha sido agregado (simulado).', 'success');
                    }
                });
            }
        </script>
    </x-slot>
</x-layouts.master-layout>
