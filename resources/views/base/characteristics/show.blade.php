<x-layouts.master-layout title="Mostrar Detalles" :cardTitle="$data->name">

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
                                    <x-layouts.inputs typeInput="justInputdisabled" name="name" id="name"
                                        placeholder="Nombre" value="{{ $data->name }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <div class="col md-4-12">
                                    <h6 class="fs-4 fw-semibold text-gray-500 mb-7">Filtros</h6>
                                    <div class="col d-flex flex-wrap gap-3">
                                        @forelse($data->children as $char)
                                            <span
                                                class="badge py-3 px-4 fs-7 badge-light-dark bg-gray-300">{{ $char->name }}</span>
                                        @empty
                                            <p class="fw-bold w-100 text-center">No hay filtros en este apartado</p>
                                        @endforelse
                                    </div>
                                </div>
                                <!--end::Input group-->
                            </div>

                            <!--end::Card header-->
                        </div>
                        <!--end::Inventory-->
                    </div>
                    <!--end::Tab content-->
                    <x-btn-cancel-save 
                    :module="ModuleAliases::CHARACTERISTIC"
                    routeCancel="{{ route('base.characteristics.index') }}" isShow="true"
                    routeEdit="{{ route('base.characteristics.edit.view', $data->id) }}" />
                </div>
                <!--end::Main column-->
            </div>
        </div>
</x-layouts.master-layout>
