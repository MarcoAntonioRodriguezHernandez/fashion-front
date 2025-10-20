<x-layouts.master-layout title="Mostrar tag de producto" cardTitle="Tag de producto {{$data->id}}">

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
                                    <label class="form-label">Producto</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="product_id" id="product_id" placeholder="Producto" value="{{ $data->product->full_name }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Tag</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="tag_id" id="tag_id" placeholder="Tag" value="{{ $data->tag->name }}" disabled />
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
                    routeCancel="{{ route('base.product_tag.index') }}"
                    isShow="true"
                    routeEdit="{{ route('base.product_tag.edit.view', $data->id) }}"
                    />
                </div>
                <!--end::Main column-->
            </div>
        </div>
</x-layouts.master-layout>
