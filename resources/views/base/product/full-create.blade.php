<x-layouts.master-layout title="Formulario Completo" cardTitle="Crear producto">
    <style>
        .color-info {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-top: 0.5em;
        }

        .color-circle {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 5px;
            border: 1px solid black;
        }

        .color-text {
            font-size: 12px;
        }

        .btn-addImg {
            background-color: #f9ddd2;
            padding: 0.5em;
            border-radius: 1em;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: row;
        }

        .btn-addImg:hover {
            background-color: #c4aea5;
        }

        .scroll-container {
            width: 100%;
            overflow-y: auto;
            white-space: nowrap;
        }

        .insider-cont {
            display: flex;
            margin: 10px;
            flex-wrap: wrap;
            /* background-color: #f0f0f0; */
        }

        @media only screen and (max-width: 600px) {
            .insider-cont {
                width: 26em;
            }
        }

        .file-input-container {
            margin-bottom: 10px;
        }

        .image-input-wrapper {
            width: 150px;
            height: 150px;
            /* border: 1px solid #ccc; */
            /* margin-top: 10px; */
        }

        #image-inputs {
            margin: 1em;
            /* margin-top: 1em; */
            /* box-shadow: 0 0 9px 3px #00000036; */
            animation: scale-up-center 0.2s;
        }

        @keyframes scale-up-center {
            0% {
                transform: scale(.5)
            }

            100% {
                transform: scale(1)
            }
        }

        #image-preview {
            margin: 1em;
        }

        .draggable-item {
            cursor: grab;
            /* Cambia el cursor a una mano de arrastre */
        }
    </style>

    <style lang="scss">
        div.fv-row:has(input[name=sale_type][value='{{ ProductSaleTypes::SALE->value }}']:checked) {
            &~div.fv-row[data-rent-only] {
                display: none;
            }
        }

        .select2-container .select2-results__option.hidden-opt {
            display: none;
        }
    </style>

    <!--begin::Main column-->
    <div class="form_padding">
        <form id="staffAdd" action="{{ route('base.product.full_create') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <!--end:::Tabs-->
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <!--begin::Inventory-->
                    <h2>Descripción general</h2>
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card body-->
                            <div class="card-body pt-0 mt-5">
                                <!--begin::Input group-->
                                <div class="row mb-10">
                                    <!--begin::Input group-->
                                    <div class="col-md-6">
                                        <!-- begin::Label-->
                                        <label class="required form-label">Categoría</label>
                                        <!-- end::Label-->
                                        <!-- begin::Select-->
                                        <select name="category_id" id="category_id" class="form-select form-control"
                                            data-control="select2" data-hide-search="true" data-placeholder="">
                                            <option selected hidden disabled>-- Elige una opción --</option>
                                            @foreach ($data['categories'] as $category)
                                                <option value="{{ $category->id }}" @selected($category->id == old('category_id'))
                                                    data-enabled-characteristics="{{ $category->characteristics()->pluck('characteristics.id') }}">
                                                    {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        <!-- end::Select-->
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="mb-10 col-md-6">
                                        <!-- begin::Label-->
                                        <label class="required form-label">Marca/Diseñador</label>
                                        <!-- end::Label-->
                                        <!-- begin::Select-->
                                        <select name="designer_id" id="designer_id" class="form-select form-control"
                                            data-control="select2" data-hide-search="true" data-placeholder="">
                                            <option selected hidden disabled>-- Elige una opción --</option>
                                            @foreach ($data['designers'] as $designer)
                                                <option value="{{ $designer->id }}" @selected($designer->id == old('designer_id'))>
                                                    {{ $designer->name }}</option>
                                            @endforeach
                                        </select>
                                        <!-- end::Select-->
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="mb-10 col-md-6">
                                        <!--begin::Label-->
                                        <label class="required form-label">Nombre</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <x-layouts.inputs typeInput="justInput" name="name" id="name"
                                            placeholder="Nombre" value="{{ old('name') }}" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="mb-10 col-md-6">
                                        <!--begin::Label-->
                                        <label class="required form-label">Título</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <x-layouts.inputs typeInput="justInput" name="title" id="title"
                                            placeholder="Título" value="{{ old('title') }}" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <h2>Información de origen</h2>
                <x-product.provider-selection :designers="$data['designers']" :providers="$data['providers']" :countries="$data['countries']" />

                <h2>Información del producto</h2>
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <!--begin::Card body-->
                        <div class="card-body pt-0 mt-5">
                            <div class="row">
                                <!--begin::Input group-->
                                <div class="mb-10 col-md-6">
                                    <!--begin::Label-->
                                    <label class="required form-label">Código o nombre de origen</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInput" typeofInput="text" name="origin_code"
                                            id="origin_code" placeholder="Código de Origen"
                                            value="{{ old('origin_code') }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row col-md-6">
                                    <!--begin::Label-->
                                    <label class="required form-label">Tipo de Venta</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex justify-content-start">
                                        <div class="form-check form-check-inline mt-3">
                                            <input class="form-check-input" type="radio" name="sale_type"
                                                id="sale_type_rent" value="{{ ProductSaleTypes::RENT->value }}"
                                                @checked(ProductSaleTypes::RENT->value == old('sale_type'))>
                                            <label class="form-check-label" for="sale_type_rent">
                                                Venta y Renta
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline mt-3">
                                            <input class="form-check-input" type="radio" name="sale_type"
                                                id="sale_type_sale" value="{{ ProductSaleTypes::SALE->value }}"
                                                @checked(ProductSaleTypes::SALE->value == old('sale_type'))>
                                            <label class="form-check-label" for="sale_type_sale">
                                                Exclusivo venta
                                            </label>
                                        </div>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row col-md-6" data-rent-only>
                                    <!--begin::Label-->
                                    <label class="required form-label">Etiquetas / Eventos</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <select class="form-select select2" data-control="select2" data-tags="true"
                                            multiple name="tags[]" id="tags">
                                            <option hidden disabled>-- Elige una opción --</option>
                                            @foreach ($data['tags'] as $tag)
                                                <option value="{{ $tag->id }}" @selected(in_array($tag->id, old('tags', [])))>
                                                    {{ $tag->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <div class="mb-10 fv-row col-md-6" data-rent-only>
                                    <!--begin::Label-->
                                    <label class="required form-label">Esquema de precios (final) </label>
                                    <!--end::Label-->
                                    <select class="form-select form-control" name="pricing_scheme_id"
                                        id="pricing_scheme_id" data-control="select2" data-hide-search="true"
                                        data-placeholder="" onchange="updatePricingSchemeData(this)">
                                        <option selected hidden disabled>-- Elige una opción --</option>
                                        @foreach ($data['pricesScheme'] as $price_scheme)
                                            <option value="{{ $price_scheme->id }}"
                                                data-msrp="{{ $price_scheme->msrp }}"
                                                data-rent-4="{{ $price_scheme->sku_4->price }}"
                                                data-rent-8="{{ $price_scheme->sku_8->price }}">
                                                {{ $price_scheme->sku_4->price . ' - ' . $price_scheme->msrp }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-10 fv-row col-md-6" data-rent-only>
                                    <!--begin::Label-->
                                    <label class="required form-label">Sugerido renta 4 días</label>
                                    <!--end::Label-->
                                    <x-layouts.inputs typeInput="justInputdisabled" typeofInput="text"
                                        name="suggested_rent_4" id="suggested_rent_4"
                                        placeholder="Sugerido renta 4 días" value="{{ old('suggested_rent_4') }}"
                                        readonly />
                                </div>
                                <div class="mb-10 fv-row col-md-6" data-rent-only>
                                    <!--begin::Label-->
                                    <label class="required form-label">Sugerido renta 8 días</label>
                                    <!--end::Label-->
                                    <x-layouts.inputs typeInput="justInputdisabled" typeofInput="text"
                                        name="suggested_rent_8" id="suggested_rent_8"
                                        placeholder="Sugerido renta 8 días" value="{{ old('suggested_rent_8') }}"
                                        readonly />
                                </div>
                                <div class="mb-10 fv-row col-md-6" data-rent-only>
                                    <!--begin::Label-->
                                    <label class="required form-label">Precio de venta sugerido</label>
                                    <!--end::Label-->
                                    <x-layouts.inputs typeInput="justInputdisabled" typeofInput="text"
                                        name="suggested_sale_price" id="suggested_sale_price"
                                        placeholder="Precio venta sugerido" value="{{ old('suggested_sale_price') }}"
                                        readonly />
                                </div>
                                <div class="mb-10 fv-row col-md-6">
                                    <!--begin::Label-->
                                    <label class="required form-label">Precio de venta completo</label>
                                    <!--end::Label-->
                                    <x-layouts.inputs typeInput="justInput" typeofInput="number" name="full_price"
                                        id="full_price" placeholder="Precio venta sugerido"
                                        value="{{ old('full_price') }}" oninput="updateFullPriceData(this)" />
                                </div>
                            </div>
                            <!--end::Input group-->
                        </div>
                    </div>
                </div>

                <h2>Información general del producto</h2>
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <!--begin::Card body-->
                        <div class="card-body pt-0 mt-5">
                            <!--begin::Input group-->
                            <div class="mb-10 fv-row">
                                <!--begin::Label-->
                                <label class="required form-label">Descripción</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <textarea class="form-control" placeholder="Descripción" name="description" id="description" cols="20"
                                    rows="5">{{ old('description') }}</textarea>
                                <!--end::Input-->
                            </div>

                            <!--begin::Characteristics inputs-->
                            <x-product.characteristic-selection :characteristics="$data['characteristics']" />
                            <!--end::Characteristics inputs-->
                        </div>
                    </div>
                </div>
                <!--end::Input group-->
                <!--end::Inventory-->


                <h2>Datos de facturación</h2>
                <x-product.invoice-selection :users="$data['users']" />

                <!--begin::Images-->
                <div class="mt-8">
                    <h2>Inserte imágenes del producto</h2>
                </div>

                <x-product.image-product :colors="$data['colors']" />
                <!--end::Images-->

                <!--begin::Items-->
                <div class="mt-8">
                    <h2>Agregar modelos</h2>
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

                <!--end::Tab content-->
                <div class="d-flex justify-content-end mt-10">
                    <!--begin::Button-->
                    <x-btn-create-cancel routeCancel="{{ route('base.product.index') }}" :module="ModuleAliases::PRODUCT" />
                    <!--end::Button-->
                </div>
        </form>
    </div>
    <!--end::Main column-->

    <x-slot name="js">

        <script src="{{ asset('js/general-form.js') }}"></script>
        <script src="{{ Vite::asset('resources/js/product/item-selection.js') }}"></script>
        <script src="{{ Vite::asset('resources/js/product/provider-selection.js') }}"></script>
        <script src="{{ Vite::asset('resources/js/product/invoice-selection.js') }}"></script>
        <script src="{{ asset('js/custom/base/products/imageProduct.js') }}"></script>

        <script>
            //simular click en el boton id="first_view_side" al cargar la pagina
            window.addEventListener('load', () => {
                document.getElementById('first_view_side').click();
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $('#size_id').select2({
                    templateResult: (data, container) => {
                        if (data.element)
                            $(container).addClass($(data.element).attr('class'));

                        return data.text;
                    }
                });
            });

            const sizeElement = document.getElementById('size_id');

            function updateDisplayedSizes() {
                let selectedCharacteristics = Array.from(document.querySelectorAll('select[name^="characteristics"]')).map(
                    (select) => Array.from(select.selectedOptions).map(option => parseInt(option.value))
                ).flat();

                let displayedSizes = selectedCharacteristics.map(
                        (charId) => sizeElement.querySelectorAll('option[data-characteristics*="|' + charId + '|"]')
                    ).filter((size) => size.length > 0)
                    .map((size) => Array.from(size))
                    .flat();

                if (displayedSizes.length > 0) {
                    sizeElement.querySelectorAll('option').forEach((size) => size.classList.add('hidden-opt'));

                    displayedSizes.forEach((size) => size.classList.remove('hidden-opt'));
                } else
                    sizeElement.querySelectorAll('option').forEach((size) => size.classList.remove('hidden-opt'));
            }
        </script>

        <script>
            $(document).ready(function() {
                $('#category_id').change(function() {
                    let enabledCharacteristics = this.querySelector('option:checked').dataset
                        .enabledCharacteristics;

                    enabledCharacteristics = JSON.parse(enabledCharacteristics);

                    document.querySelectorAll('div[id^=characteristic-selection-]').forEach(function(element) {
                        let elemId = element.id.match(/characteristic-selection-(\d+)/);

                        if (enabledCharacteristics.length == 0 || (elemId && enabledCharacteristics
                                .includes(parseInt(elemId[1]))))
                            element.classList.remove('d-none');
                        else
                            element.classList.add('d-none');
                    });
                });
            });
        </script>

        <script>
            function updatePricingSchemeData(selectElement) {
                let selectedOption = selectElement.options[selectElement.selectedIndex];

                let msrp = selectedOption.dataset['msrp'];

                document.getElementById('suggested_rent_4').value = selectedOption.dataset['rent-4'];
                document.getElementById('suggested_rent_8').value = selectedOption.dataset['rent-8'];
                document.getElementById('suggested_sale_price').value = msrp;
                document.getElementById('full_price').value = msrp;
                document.getElementById('price_sale').value = msrp;
            }

            function updateFullPriceData(inputElement) {
                document.getElementById('price_sale').value = inputElement.value;
            }

            window.addEventListener('load', () => {
                @if (old('provider'))
                    showProviderForm(); // Show new provider form
                @endif
            });
        </script>

        <script>
            let validations = [
                'name',
                'title',
                'sale_type',
                'description',
                'sku',
                'exchange_rate',
                'file',
                'issuance_date',
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
