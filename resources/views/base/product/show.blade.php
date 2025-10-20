<x-layouts.master-layout title="Mostrar producto" :cardTitle="$data->name">
    <style>
        .insider-cont {
            display: flex;
            margin: 10px;
            flex-wrap: wrap;
        }

        @media only screen and (max-width: 600px) {
            .insider-cont {
                width: 26em;
            }

            .image-input-wrapper {
                width: 125px !important;
                height: 125px !important;
            }
        }

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
    </style>

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
                            <div class="card-body pt-0 row">
                                <!--begin::Input group-->
                                <div class="mb-10 col-md-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Nombre</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="name" id="name" placeholder="Nombre" value="{{ $data->name }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 col-md-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Título</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="title" id="title" placeholder="Título" value="{{ $data->title }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Código de Origen</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="origin_code" id="origin_code" placeholder="Código de Origen" value="{{ $data->origin_code }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Código CM</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="origin_code" id="origin_code" placeholder="Código CM" value="{{ $data->origin_code }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Marca / Diseñador</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="designer_id" id="designer_id" placeholder="Marca / Diseñador" value="{{ $data->designer->name }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Descripción</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <textarea class="form-control" name="description" id="description" cols="20" rows="5" disabled>{{ $data->description }}</textarea>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Esquema de precios</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="category_id" id="category_id" value="{{ $data->pricingScheme->sku_4->price }} - {{ $data->pricingScheme->msrp }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Precio completo</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="full_price" id="full_price" placeholder="Precio completo" value="$ {{ $data->full_price }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Categoría</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="category_id" id="category_id" placeholder="Nombre" value="{{ $data->category->name }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Deseado</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="desired" id="desired" placeholder="Nombre" value="{{ $data->desired ? 'Deseado' : 'No deseado' }}" disabled />
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
                                        <x-layouts.inputs typeInput="justInputdisabled" typeofInput="text" name="sale_type" id="sale_type" value="{{ $data->sale_type_name }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">SKU</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="sku" id="sku" placeholder="Sku" value="{{ $data->sku }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Etiquetas</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="col d-flex flex-wrap gap-3">
                                        @foreach ($data->tags as $tag)
                                            <span class="badge py-3 px-4 fs-7 badge-light-dark bg-gray-300">{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Proveedores</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="col d-flex flex-wrap gap-3">
                                        @foreach ($data->providers as $provider)
                                            <span class="badge py-3 px-4 fs-7 badge-light-dark bg-gray-300">{{ $provider->name }}</span>
                                        @endforeach
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--end::Input group-->
                            </div>
                            <!--end::Card header-->
                        </div>
                        <!--end::Inventory-->
                    </div>
                    <!--begin::Input group-->
                    <div class="card card-flush py-4">
                        <div class="card-header">
                            <div class="card-body pt-0">
                                <div class="mb-10 fv-row">
                                    <h4 class="card-title mb-8 font-weight-bold text-xl">Características</h4>
                                    <div class="col d-flex flex-column gap-3 mt-8">
                                        @foreach ($data->characteristics->groupBy('parent_characteristic_id') as $parentCharacteristicId => $characteristics)
                                            @if ($parentCharacteristic = $characteristics->first()->parentCharacteristic)
                                                <div class="d-flex flex-column mb-4">
                                                    <h6 class="form-label">
                                                        {{ $parentCharacteristic->name }}</h6>
                                                    <div class="d-flex flex-wrap gap-3">
                                                        @foreach ($characteristics as $characteristic)
                                                            <span class="badge py-3 px-4 fs-7 badge-light-dark bg-gray-300">{{ $characteristic->name }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--end::Input group-->
                    <div class="card-body pt-0 mt-5" style="padding: 0 !important;">
                        <div>
                            <h1>Imágenes del producto</h1>
                        </div>
                    </div>
                    <div class="form d-flex flex-column flex-lg-row mt-6">
                        <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10 col-12 col-md-3">
                            <ul class="nav nav-tabs nav-pills border-0 flex-row flex-md-column me-5 mb-3 mb-md-0 fs-6">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" id="first_view_side" href="#kt_tab_pane_1">Vista Frontal</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_2">Vista derecho</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_3">Vista
                                        izquierdo</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_4">Vista trasera</a>
                                </li>
                            </ul>
                        </div>
                        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                            <!--end:::Tabs-->
                            <div class="d-flex flex-column gap-7 gap-lg-10">
                                <!--begin::Inventory-->
                                <div class="card card-flush py-4">
                                    <!--begin::Card header-->
                                    <div class="card-header">
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
                                                <div class="scroll-container">
                                                    <div class="insider-cont" id="image-container1">
                                                        @forelse ($data->frontImages as $image)
                                                            <div class="image-input image-input-empty m-3" data-kt-image-input="false">
                                                                <div class="image-input-wrapper w-225px h-425px" style="background-image: url('{{ asset($image->src_image) }}')" id="image-preview-thumbnail">
                                                                </div>
                                                                <div class="color-info">
                                                                    <div class="color-circle" style="background-color: {{ $image->color->hexadecimal }}">
                                                                    </div>
                                                                    <div class="color-text">{{ $image->color->name }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <h1 class="mt-5">NO HAY IMÁGENES DISPONIBLES</h1>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
                                                <div class="scroll-container">
                                                    <div class="insider-cont" id="image-container2">
                                                        @forelse ($data->rightImages as $image)
                                                            <div class="image-input image-input-empty m-3" data-kt-image-input="false">
                                                                <div class="image-input-wrapper w-225px h-425px" style="background-image: url('{{ asset($image->src_image) }}')" id="image-preview-thumbnail">
                                                                </div>
                                                                <div class="color-info">
                                                                    <div class="color-circle" style="background-color: {{ $image->color->hexadecimal }}">
                                                                    </div>
                                                                    <div class="color-text">{{ $image->color->name }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <h1 class="mt-5">NO HAY IMÁGENES DISPONIBLES</h1>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="kt_tab_pane_3" role="tabpanel">
                                                <div class="scroll-container">
                                                    <div class="insider-cont" id="image-container3">
                                                        @forelse ($data->leftImages as $image)
                                                            <div class="image-input image-input-empty m-3" data-kt-image-input="false">
                                                                <div class="image-input-wrapper w-225px h-425px" style="background-image: url('{{ asset($image->src_image) }}')" id="image-preview-thumbnail">
                                                                </div>
                                                                <div class="color-info">
                                                                    <div class="color-circle" style="background-color: {{ $image->color->hexadecimal }}">
                                                                    </div>
                                                                    <div class="color-text">{{ $image->color->name }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <h1 class="mt-5">NO HAY IMÁGENES DISPONIBLES</h1>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="kt_tab_pane_4" role="tabpanel">
                                                <div class="scroll-container">
                                                    <div class="insider-cont" id="image-container4">
                                                        @forelse ($data->backImages as $image)
                                                            <div class="image-input image-input-empty m-3" data-kt-image-input="false">
                                                                <div class="image-input-wrapper w-225px h-425px" style="background-image: url('{{ asset($image->src_image) }}')" id="image-preview-thumbnail">
                                                                </div>
                                                                <div class="color-info">
                                                                    <div class="color-circle" style="background-color: {{ $image->color->hexadecimal }}">
                                                                    </div>
                                                                    <div class="color-text">{{ $image->color->name }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <h1 class="mt-5">NO HAY IMÁGENES DISPONIBLES</h1>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Card header-->
                                </div>
                                <!--end::Inventory-->
                            </div>
                        </div>
                    </div>
                    <!--end::Tab content-->
                    <x-btn-cancel-save :module="ModuleAliases::PRODUCT" routeCancel="{{ route('base.product.index') }}" isShow="true" routeEdit="{{ route('base.product.edit.view', $data->id) }}">
                        <!--begin::Button-->
                        <a href="{{ route('base.product.variants.view', $data->id) }}" class="btn btn-success me-5">Ver variantes</a>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <a href="{{ route('base.supply.create.view', $data->id) }}" class="btn btn-info me-5">Ver
                            artículos</a>
                        <!--end::Button-->
                    </x-btn-cancel-save>
                </div>
                <!--end::Main column-->
            </div>
        </div>
</x-layouts.master-layout>
