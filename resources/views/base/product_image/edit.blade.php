<x-layouts.master-layout title="Editar imagen de producto" cardTitle="Editar imagen de producto {{ $data->id }}">

    <div class="form_padding">
        <!--begin::Form-->
        <form id="staffAdd" class="form d-flex flex-column flex-lg-row" action="{{ route('base.product_image.edit') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!--begin::Aside column-->
            <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                <!--begin::Thumbnail settings-->
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Imagen</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body text-center pt-0">
                        <!--begin::Image input-->
                        <!--begin::Image input placeholder-->
                        <style>
                            .image-input-placeholder {
                                background-image: url('assets/media/svg/files/blank-image.svg');
                            }

                            [data-bs-theme="dark"] .image-input-placeholder {
                                background-image: url('assets/media/svg/files/blank-image-dark.svg');
                            }
                        </style>
                        <!--end::Image input placeholder-->
                        <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3"
                            data-kt-image-input="true">
                            <!--begin::Preview existing avatar-->
                            <div class="image-input-wrapper w-250px h-250px"
                                style="background-image: url('{{ asset($data->src_image) }}')"></div>
                            <!--end::Preview existing avatar-->
                            <!--begin::Label-->
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Cambiar imagen">
                                <i class="ki-outline ki-pencil fs-7"></i>
                                <!--begin::Inputs-->
                                <input type="file" name="src_image" value="{{ $data->src_image }}"
                                    accept=".png, .jpg, .jpeg" />
                                {{-- <input type="hidden" name="avatar_remove" /> --}}
                                <!--end::Inputs-->
                            </label>
                            <!--end::Label-->
                            <!--begin::Cancel-->
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Quitar imagen">
                                <i class="ki-outline ki-cross fs-2"></i>
                            </span>
                            <!--end::Cancel-->
                            <!--begin::Remove-->
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Quitar imagen">
                                <i class="ki-outline ki-cross fs-2"></i>
                            </span>
                            <!--end::Remove-->
                        </div>
                        <!--end::Image input-->
                        <!--begin::Description-->
                        <div class="text-muted fs-7">Por favor suba su imagen. Se acepta solamente *.png, *.jpg and *.
                        </div>
                        <!--end::Description-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Thumbnail settings-->
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
                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                    <label class="required form-label">Producto</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="product_id" id="product_id" class="form-select form-control"
                                        data-control="select2" data-hide-search="true" data-placeholder="">
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}"
                                                @if ($product->id == $data->product->id) selected @endif>
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
                                    <label class="required form-label">Color</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="color_id" id="color_id" class="form-select form-control"
                                        data-control="select2" data-hide-search="true" data-placeholder="">
                                        @foreach ($colors as $color)
                                            <option value="{{ $color->id }}"
                                                @if ($color->id == $data->color->id) selected @endif>
                                                {{ $color->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Orden</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInput" typeofInput="text" name="order"
                                            id="order" placeholder="Orden" min="0"
                                            value="{{ $data->order }}" />
                                    </div>
                                    <!--end::Input-->
                                    <!--begin::Input group-->

                                </div>
                                <!--end::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Perspectiva de Fotografia</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="camera_perspective" id="camera_perspective"
                                        class="form-select form-control" data-control="select2" data-hide-search="true"
                                        data-placeholder="">
                                        <option value="Frontal">
                                            Frontal
                                        </option>

                                        <option value="Lateral Derecho">
                                            Lateral Derecho
                                        </option>

                                        <option value="Lateral Izquierdo">
                                            Lateral Izquierdo
                                        </option>

                                        <option value="Trasera">
                                            Trasera
                                        </option>
                                    </select>

                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                            </div>
                            <!--end::Card header-->
                        </div>
                        <!--end::Inventory-->
                    </div>
                    <!--end::Tab content-->
                    <x-btn-cancel-save routeCancel="{{ route('base.product_image.index') }}" />
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
                'color_id',
                'order',

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
