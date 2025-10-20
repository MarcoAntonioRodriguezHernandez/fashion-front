<x-layouts.master-layout title="Crear producto" cardTitle="Crear producto">
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

        .center-content {
            text-align: center;
            vertical-align: middle;
        }
    </style>

    <!--begin::Main column-->
    <div class="form_padding">
        <form id="staffAdd" action="{{ route('base.product.create') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <!--end:::Tabs-->
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <!--begin::Inventory-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card body-->
                            <div class="card-body pt-0 mt-5 row">
                                <!--begin::Input group-->
                                <div class="mb-10 col-md-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Nombre</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInput" name="name" id="name" placeholder="Nombre" value="{{ old('name') }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 col-md-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Título</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInput" name="title" id="title" placeholder="Título" value="{{ old('title') }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Marca / Diseñador</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <select name="designer_id" id="designer_id" class="form-select form-control" data-control="select2" data-hide-search="false" data-placeholder="">
                                            <option selected hidden disabled>-- Elige una opción --</option>
                                            @foreach ($data['designers'] as $designer)
                                                <option value="{{ $designer->id }}">{{ $designer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Descripción</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <textarea class="form-control" placeholder="Descripción" name="description" id="description" cols="20" rows="5">{{ old('description') }}</textarea>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 col-md-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Código de Origen</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInput" typeofInput="text" name="origin_code" id="origin_code" placeholder="Código de Origen" value="{{ old('origin_code') }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 col-md-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Código CM</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInput" typeofInput="text" name="internal_code" id="internal_code" placeholder="Código CM" value="{{ old('internal_code') }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 col-md-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Precio de venta completo</label>
                                    <!--end::Label-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInput" typeofInput="number" name="full_price" id="full_price" placeholder="Precio venta sugerido" value="{{ old('full_price') }}" />
                                    </div>
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 col-md-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Esquema de precios (final) </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select class="form-select form-control" name="pricing_scheme_id" id="pricing_scheme_id" data-control="select2" data-hide-search="true" data-placeholder="">
                                        <option selected hidden disabled>-- Elige una opción --</option>
                                        @foreach ($data['pricingSchemes'] as $pricingScheme)
                                            <option value="{{ $pricingScheme->id }}">{{ $pricingScheme->sku_4->price . ' - ' . $pricingScheme->msrp }}</option>
                                        @endforeach
                                    </select>
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
                                            <option selected hidden disabled>-- Elige una opción --</option>
                                            @foreach (ProductSaleTypes::getAllNames() as $value => $name)
                                                <option value="{{ $value }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 col-md-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Categoría</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <select name="category_id" id="category_id" class="form-select form-control" data-control="select2" data-hide-search="false" data-placeholder="">
                                            <option selected hidden disabled>-- Elige una opción --</option>
                                            @foreach ($data['categories'] as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 col-md-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Deseado</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <select name="desired" id="desired" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                                            <option selected disabled hidden>-- Elige una opción --</option>
                                            <option value="1">Deseado</option>
                                            <option value="0">No Deseado</option>
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">SKU</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    {{-- <input type="text" name="sku" class="form-control mb-2"
                                        placeholder="SKU Number" value="" /> --}}
                                    <x-layouts.inputs typeInput="justInput" name="sku" id="sku" placeholder="Sku" value="{{ old('sku') }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Etiquetas</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <select class="form-control select2" data-control="select2" data-tags="true" multiple name="tags[]" id="tags">
                                            <option label="Label"></option>
                                            @foreach ($data['tags'] as $tag)
                                                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Proveedores</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <select class="form-control select2" data-control="select2" multiple name="providers[]" id="providers">
                                            <option label="Label"></option>
                                            @foreach ($data['providers'] as $provider)
                                                <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                            </div>
                        </div>
                    </div>
                    <!--end::Input group-->

                    <h2>Características del producto</h2>

                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card body-->
                            <div class="card-body pt-0 mt-5">
                                <x-product.characteristic-selection :characteristics="$data['characteristics']" />
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card header-->
            </div>
            <!--begin::Form-->

            <h2 class="mt-10 mb-10">Imágenes del producto</h2>

            <!--begin::Image column-->
            <x-product.image-product :colors="$data['colors']" />
            <!--end::Image column-->

            <!--end::Tab content-->
            <x-btn-create-cancel routeCancel="{{ route('base.product.index') }}" :module="ModuleAliases::PRODUCT" />
        </form>
    </div>
    <!--end::Main column-->

    {{-- code for the validations fields, is necesary use the id of the form in the GeneralForm.init('staffAdd') --}}
    <x-slot name="js">
        <script src="{{ asset('js/custom/base/products/imageProduct.js') }}"></script>

        <script src="{{ asset('js/general-form.js') }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                initImageHandling();
            });
        </script>

        <script>
            let validations = [
                'name',
                'description',
                'category_id',
                'desired',
                'sku',
                'tags',
                'color_id_variant',
                'size_id_variant',
                'code_variant',
                'status',
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
