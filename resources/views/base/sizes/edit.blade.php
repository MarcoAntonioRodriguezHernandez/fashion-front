<x-layouts.master-layout title="Editar talla" cardTitle="Editar {{ $data->full_name }}">

    <div class="form_padding">
        <!--begin::Form-->
        <form id="staffAdd" class="form d-flex flex-column flex-lg-row" action="{{ route('base.sizes.edit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="id" value="{{ $data->id }}">

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
                                    <label class="required form-label">Nombre</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInput" name="name" id="name"
                                        placeholder="Nombre" value="{{ old('name', $data->name) }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Medida</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInput" name="number" id="number"
                                        placeholder="Medida" value="{{ old('number', $data->number) }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Color</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="color" class="form-control form-control-solid" id="hex_color" value="{{ old('hex_color', $data->hex_color) }}" name="hex_color" />
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Status</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <select name="status" id="status" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                                            <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Visible</option>
                                            <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>No Visible</option>
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <label class="form-label">
                                        Exclusiva en características
                                        <span class="ms-1" data-bs-toggle="tooltip" title="Si elige alguna característica, cuando se seleccione, esta talla será la única disponible. Deje vacío para habilitar en todas las características.">
                                            <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                        </span>
                                    </label>
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <select name="characteristics[]" id="characteristics" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="Habilitar en todas" multiple>
                                            @foreach ($characteristics as $parent)
                                                <optgroup label="{{ $parent->name }}">
                                                    @foreach ($parent->children as $child)
                                                        <option value="{{ $child->id }}" @selected(in_array($child->id, old('characteristics', $data->characteristics()->pluck('characteristics.id')->toArray())))>{{ $child->name }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
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
                    <x-btn-cancel-save 
                    routeCancel="{{ route('base.sizes.index') }}"
                    :module="ModuleAliases::SIZE"
                    />
                </div>
                <!--end::Main column-->
        </form>
    </div>
    {{-- code for the validations fields, is necesary use the id of the form in the GeneralForm.init('staffAdd') --}}
    <x-slot name="js">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script src="{{ asset('js/general-form.js') }}"></script>

        <script>
            let validations = [
                'name',
                'slug',

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
