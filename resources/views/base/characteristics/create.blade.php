<x-layouts.master-layout title="Crear Características" cardTitle="Crear Características">

    <div class="form_padding">
        <!--begin::Form-->
        <form id="staffAdd" class="form d-flex flex-column flex-lg-row" action="{{ route('base.characteristics.create') }}"
            method="POST">
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
                                    <x-layouts.inputs typeInput="justInput" name="name" id="name"
                                        placeholder="Nombre" value="{{ old('name') }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Card header-->
                        </div>
                        <div class="card-header">
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Input group-->
                                <div class="col-12 mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Agregar filtros</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="row" id="inputContainer">
                                    </div>
                                </div>
                                <!--end::Input group-->
                                <div class="d-flex justify-content-end">
                                    <!--begin::Button-->
                                    <button onclick="createNewInput()" type="button" class="btn btn-success" id="add">Agregar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Inventory-->
                </div>
                <!--end::Tab content-->
                <x-btn-create-cancel 
                routeCancel="{{ route('base.characteristics.index') }}" 
                :module="ModuleAliases::CHARACTERISTIC"
                />
            </div>
            <!--end::Main column-->
        </form>
    </div>

    <div class="d-none">
        <div class="col-md-4 mb-4 d-flex" id="characteristic" >
            <x-layouts.inputs typeInput="justInput" name="children[]"
                placeholder="Características" value="{{ old('characteristic') }}" />
        </div>
    </div>

    {{-- code for the validations fields, is necesary use the id of the form in the GeneralForm.init('staffAdd') --}}
    <x-slot name="js">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
        <script src = "{{ asset('js/general-form.js') }}" ></script>

        <x-characteristics.add-input containerId="inputContainer" inputFactoryId="characteristic" />

        <script>
            @foreach(old('children', ['']) as $name)
            createNewInput('{{ $name }}');
            @endforeach
        </script>

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
        <!-- Add this inside the <script> tag or in a separate JS file -->
    </x-slot>
</x-layouts.master-layout>
