<x-layouts.master-layout title="Mostrar marketplace" :cardTitle="$data->name">

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
                                    <label class="form-label">Nombre</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    {{-- <input type="text" name="sku" class="form-control mb-2" placeholder="SKU Number" value="" /> --}}
                                    <x-layouts.inputs typeInput="justInputdisabled" name="name" id="name" placeholder="Nombre" value="{{ $data->name }}" disabled />
                                    <!--end::Input-->
                                    <!--begin::Label-->
                                    <label class="form-label">Slug</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    {{-- <input type="text" name="sku" class="form-control mb-2" placeholder="SKU Number" value="" /> --}}
                                    <x-layouts.inputs typeInput="justInputdisabled" name="slug" id="slug" placeholder="Slug" value="{{ $data->slug }}" disabled />
                                    <!--end::Input-->
                                    <!--begin::Label-->
                                    <label class="form-label">URL</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    {{-- <x-layouts.inputs typeInput="justInputdisabled" name="url" id="url" placeholder="URL" value="{{ $data->url }}" disabled class="clickable-url" data-url="{{ $data->url }}" style="border: 1px solid #ccc; padding: 5px; width: auto;"/> --}}
                                    <a class="btn btn-secondary form-control mb-2 d-block" href="{{ $data->url }}" style="border: 1px solid #ccc; padding: 10px; width: auto; text-align: left; background-color: #f2f2f2; color:rgba(108, 117, 125, 0.6); font-weight: 600;">
                                        {{ $data->url }}
                                    </a>                                                                                                                                                                             
                                    
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
                    :module="ModuleAliases::MARKETPLACE"
                    routeCancel="{{ route('base.marketplace.index') }}"
                    isShow="true"
                    routeEdit="{{ route('base.marketplace.edit.view', $data->id) }}"
                    />
                </div>
                <!--end::Main column-->
            </div>
        </div>
        
</x-layouts.master-layout>