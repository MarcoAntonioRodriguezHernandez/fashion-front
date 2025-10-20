<x-layouts.master-layout title="Mostrar talla" :cardTitle="$data->full_name">

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
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <label class="required form-label">Nombre</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <div class="d-flex gap-3">
                                            <x-layouts.inputs typeInput="justInputdisabled" typeofInput="text" value="{{ $data->name }}" />
                                        </div>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <label class="required form-label">Slug</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <div class="d-flex gap-3">
                                            <x-layouts.inputs typeInput="justInputdisabled" typeofInput="text" value="{{ $data->slug }}" />
                                        </div>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <label class="required form-label">Medida</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <div class="d-flex gap-3">
                                            <x-layouts.inputs typeInput="justInputdisabled" typeofInput="text" value="{{ $data->number ?? 'No definido' }}" />
                                        </div>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <label class="required form-label">Color</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <div class="d-flex gap-3">
                                            <span class="col-12 form-control h-40px" style="background-color: {{ $data->hex_color }};">{{ $data->hex_color }}</span>
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
                                            <x-layouts.inputs typeInput="justInputdisabled" name="status"
                                                id="status" value="{{ $data->status == 1 ? 'Visible' : 'No Visible' }}" />
                                        </div>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <label class="form-label">Exclusiva en características</label>
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
                                <!--end::Input group-->
                            </div>
                            <!--end::Card header-->
                        </div>
                        <!--end::Inventory-->
                    </div>
                    <!--end::Tab content-->
                    <x-btn-cancel-save 
                        :module="ModuleAliases::SIZE"
                        routeCancel="{{ route('base.sizes.index') }}"
                        isShow="true"
                        routeEdit="{{ route('base.sizes.edit.view', $data->id) }}"
                    />
                </div>
                <!--end::Main column-->
            </div>
        </div>
</x-layouts.master-layout>
