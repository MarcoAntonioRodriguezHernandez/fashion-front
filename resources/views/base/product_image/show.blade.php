<x-layouts.master-layout title="Mostrar imagen de producto" cardTitle="imagen de producto {{$data->id}}">

    <div class="form_padding">
        <!--begin::Form-->
        <div class="form d-flex flex-column flex-lg-row">
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
                        <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3" data-kt-image-input="true">
                            <!--begin::Preview existing avatar-->
                            <div class="image-input-wrapper w-250px h-250px" style="background-image: url('{{ asset($data->src_image) }}')"></div>
                            <!--end::Preview existing avatar-->
                        </div>
                        <!--end::Image input-->
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
                                    <label class="form-label">Producto</label>
                                    <!--end::Label-->

                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="category_id" id="category_id"
                                        placeholder="Nombre" value="{{ $data->product->full_name }}" disabled />
                                    <!--end::Input-->
                                </div>

                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Color</label>
                                    <!--end::Label-->

                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="category_id" id="category_id"
                                        placeholder="Nombre" value="{{ $data->color->name }}" disabled />
                                    <!--end::Input-->
                                </div>

                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Orden</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="category_id" id="category_id"
                                        placeholder="Nombre" value="{{ $data->order }}" disabled />
                                    <!--end::Input-->
                                </div>

                                <!--end::Input group--> <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Perspectiva de Fotografia</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="category_id" id="category_id"
                                        placeholder="Nombre" value="{{ $data->camera_perspective }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Card header-->
                        </div>
                        <!--end::Inventory-->
                    </div>
                    <!--end::Tab content-->
                    <x-btn-cancel-save 
                    routeCancel="{{ route('base.product_image.index') }}"
                    isShow="true"
                    routeEdit="{{ route('base.product_image.edit.view', $data->id) }}"
                    />
                </div>
                <!--end::Main column-->
            </div>
        </div>
</x-layouts.master-layout>