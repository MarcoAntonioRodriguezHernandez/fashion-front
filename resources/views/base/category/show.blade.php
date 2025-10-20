<x-layouts.master-layout title="Mostrar categoría" cardTitle="Categoría {{$data->name}}">

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
                                    {{-- <input type="text" name="sku" class="form-control mb-2"
                                        placeholder="SKU Number" value="" /> --}}
                                    <x-layouts.inputs typeInput="justInputdisabled" name="name" id="name"
                                        placeholder="Nombre" value="{{ $data->name }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Categoría Padre</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputdisabled" typeofInput="text"
                                            value="{{ $data->parentCategory?->name ?? 'No definido' }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Estatus</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputdisabled" typeofInput="text" name="status"
                                            id="status" placeholder="Status" min="0"
                                            value="{{ $data->status_name }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Características habilitadas</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="col d-flex flex-wrap gap-3">
                                        @forelse ($data->characteristics as $characteristic)
                                            <span class="badge py-3 px-4 fs-7 badge-light-dark bg-gray-300">{{ $characteristic->name }}</span>
                                        @empty
                                            <span class="badge py-3 px-4 fs-7 badge-light-dark bg-light-success">Todas las características están habilitadas</span>
                                        @endforelse
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
                    <x-btn-cancel-save :module="ModuleAliases::CATEGORY" routeCancel="{{ route('base.category.index') }}" isShow="true"
                        routeEdit="{{ route('base.category.edit.view', $data->id) }}" />
                </div>
                <!--end::Main column-->
            </div>
        </div>
</x-layouts.master-layout>