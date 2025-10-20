<x-layouts.master-layout title="Crear variante" cardTitle="Crear variante">

    <div class="form_padding">
        <!--begin::Form-->
        <form id="staffAdd" class="form d-flex flex-column flex-lg-row" action="{{ route('base.variant.create') }}"
            method="POST" enctype="multipart/form-data">
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
                                    <label class="required form-label">Talla</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="size_id" id="size_id" class="form-select form-control"
                                        data-control="select2" data-hide-search="true" data-placeholder="">
                                        <option selected hidden disabled>-- Elige una opción --</option>
                                        @foreach ($data['sizes'] as $size)
                                            <option value="{{ $size->id }}" data-slug="{{ $size->slug }}">
                                                {{ $size->full_name }}
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
                                    <div style="position: relative; display: flex; align-items: center;">
                                        <select name="color_id" id="color_id" class="form-select form-control"
                                            data-control="select2" data-hide-search="true" data-placeholder=""
                                            style="width: 70px;" onchange="updateColor()">
                                            <option selected hidden disabled>-- Elige una opción --</option>
                                            @foreach ($data['colors'] as $color)
                                                <option value="{{ $color->id }}" data-hex="{{ $color->hexadecimal }}"
                                                    data-slug="{{ $color->slug }}">
                                                    {{ $color->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div id="color_hexadecimal"
                                            style="width: 45px; height: 45px; border: 0.5px solid #F1F1F1; margin-left: 1em; border-radius: 0.475rem;">
                                        </div>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Código</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInput" typeofInput="text" name="code"
                                            id="code" placeholder="Código" min="0"
                                            value="{{ old('code') }}" />
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
                                        <select name="status" id="status" class="form-select form-control"
                                            data-control="select2" data-hide-search="true" data-placeholder="">
                                            <option value="1">Activo</option>
                                            <option value="0">Inactivo</option>
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
                    <x-btn-create-cancel routeCancel="{{ route('base.variant.index') }}" :module="ModuleAliases::VARIANT" />
                </div>
                <!--end::Main column-->
        </form>
    </div>
    {{-- code for the validations fields, is necesary use the id of the form in the GeneralForm.init('staffAdd') --}}
    <x-slot name="js">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script src="{{ asset('js/general-form.js') }}"></script>

        <script>
            $(document).ready(function() {

                function updateCodeInput() {
                    var sizeOption = $('#size_id option:selected');
                    var colorOption = $('#color_id option:selected');

                    var sizeSlug = sizeOption.data('slug');
                    var colorSlug = colorOption.data('slug');

                    if (sizeSlug && colorSlug) {
                        var defaultCode = sizeSlug.trim() + '-' + colorSlug.trim();
                        $('#code').val(defaultCode);
                    } else {
                        $('#code').val('');
                    }
                }

                updateCodeInput();

                $('#size_id, #color_id').change(function() {
                    updateCodeInput();
                });
            });
        </script>

        <script>
            function updateColor() {
                const selectColor = document.getElementById('color_id');
                const colorHexadecimal = document.getElementById('color_hexadecimal');

                const selectedOption = selectColor.options[selectColor.selectedIndex];
                const colorHex = selectedOption.getAttribute('data-hex');

                colorHexadecimal.style.backgroundColor = colorHex;
            }

            updateColor();
        </script>

        <script>
            let validations = [
                'size_id',
                'color_id',
                'code',
                'status',

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
