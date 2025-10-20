<x-layouts.master-layout title="Crear Transferencia de Distribuciones" cardTitle="Crear Transferencia de Distribuciones">

    <div class="form_padding">
        <!--begin::Form-->
        <form id="staffAdd" class="form d-flex flex-column flex-lg-row"
            action="{{ route('base.supply_transfer.create') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!--begin::Aside column-->
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
                                    <label class="required form-label">Receptor</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="recipient_id" id="recipient_id" class="form-select form-control"
                                        data-control="select2" data-hide-search="false" data-placeholder="">
                                        <option selected hidden disabled>-- Elige una opción --</option>
                                        @foreach ($data['users'] as $user)
                                            <option value="{{ $user->id }}" @selected($user->id == old('recipient_id'))>
                                                {{ $user->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Origen</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="origin_id" id="origin_id" class="form-select form-control"
                                        data-control="select2" data-hide-search="false" data-placeholder="">
                                        <option selected hidden disabled>-- Elige una opción --</option>
                                        @foreach ($data['origin'] as $origin)
                                            <option value="{{ $origin->id }}" @selected($origin->id == old('origin_id'))>
                                                {{ $origin->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Código de la distribución </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="supply_id" id="supply_id" class="form-select form-control"
                                        data-control="select2" data-hide-search="false" data-placeholder="">
                                        <option selected hidden disabled>-- Elige una opción --</option>
                                        @foreach ($data['supplies'] as $supply)
                                            <option value="{{ $supply->id }}" @selected($supply->id == old('supply_id'))>
                                                {{ $supply->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Fecha de Recepción</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <input type="datetime-local" class="form-control" name="reception_date"
                                            id="reception_date" value="{{ old('reception_date') }}">
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Destino</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="destination_id" id="destination_id" class="form-select form-control"
                                        data-control="select2" data-hide-search="false" data-placeholder="">
                                        <option selected hidden disabled>-- Elige una opción --</option>
                                        @foreach ($data['destination'] as $destination)
                                            <option value="{{ $destination->id }}" @selected($destination->id == old('destination_id'))>
                                                {{ $destination->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Card header-->
                        </div>
                        <!--end::Inventory-->
                    </div>
                    <!--end::Tab content-->
                    <div class="d-flex justify-content-end">
                        <!--begin::Button-->
                        <a href="{{ route('base.supply_transfer.index') }}" id="kt_ecommerce_add_product_cancel"
                            class="btn btn-light me-5">Cancelar</a>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Guardar</span>
                        </button>
                        <!--end::Button-->
                    </div>
                </div>
                <!--end::Main column-->
            </div>
        </form>
    </div>
    {{-- code for the validations fields, is necesary use the id of the form in the GeneralForm.init('staffAdd') --}}
    <x-slot name="js">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script src="{{ asset('js/general-form.js') }}"></script>

        <script>
            let validations = [
                'recipient_id',
                'origin_id',
                'supply_id',
                'reception_date',
                'destination_id',
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
