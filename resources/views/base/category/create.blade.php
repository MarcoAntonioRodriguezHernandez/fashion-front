<x-layouts.master-layout title="Crear Categoría" cardTitle="Crear Categoría">

    <div class="form_padding">
        <!--begin::Form-->
        <form id="staffAdd" class="form d-flex flex-column flex-lg-row" action="{{ route('base.category.create') }}" method="POST" enctype="multipart/form-data">
            @csrf

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
                                    {{-- <input type="text" name="sku" class="form-control mb-2"
                                        placeholder="SKU Number" value="" /> --}}
                                    <x-layouts.inputs typeInput="justInput" name="name" id="name" placeholder="Nombre" value="{{ old('name') }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <label class="form-label">
                                        Categoría Padre
                                        <span class="ms-1" data-bs-toggle="tooltip" title="La categoría padre es la categoría superior en una estructura jerárquica. Se utiliza para organizar las categorías en niveles. Si esta categoría tiene una categoría padre, será una subcategoría de esa categoría.">
                                            <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                        </span></label>
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <select name="parent_category_id" id="parent_category_id" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                                            <option selected hidden disabled>-- Elige una opción --</option>
                                            @foreach ($data['categories'] as $category)
                                                <option value="{{ $category->id }}" @selected($category->id == old('category_id'))>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Estatus</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <select name="status" id="status" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                                            <option selected hidden disabled>-- Elige una opción --</option>
                                            @foreach (CategoryStatuses::getAllNames() as $value => $name)
                                                <option value="{{ $value }}" @if ($value == old('status')) selected @endif>
                                                    {{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <label class="form-label">
                                        Características habilitadas
                                        <span class="ms-1" data-bs-toggle="tooltip" title="Las características que son elegibles para esta categoría. Deje vacío para habilitar todas las características.">
                                            <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                        </span>
                                    </label>
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <select name="characteristics[]" id="characteristics" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="Habilitar todas" multiple>
                                            @foreach ($data['characteristics'] as $characteristic)
                                                <option value="{{ $characteristic->id }}" @selected(in_array($characteristic->id, old('characteristics', [])))>{{ $characteristic->name }}</option>
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
                    <x-btn-create-cancel routeCancel="{{ route('base.category.index') }}" :module="ModuleAliases::CATEGORY" />
                </div>
            </div><!--end::Main column-->
        </form>
    </div>
    {{-- code for the validations fields, is necesary use the id of the form in the GeneralForm.init('staffAdd') --}}
    <x-slot name="js">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script src="{{ asset('js/general-form.js') }}"></script>

        <script>
            let validations = [
                'name',

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
