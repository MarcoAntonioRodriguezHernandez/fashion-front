<x-layouts.master-layout title="Crear Distribuciones de Articulo" cardTitle="Crear Distribuciones de Artículo">

    <div class="form_padding">
        <!--begin::Form-->
        <form id="staffAdd" class="form d-flex flex-column flex-lg-row" action="{{ route('base.supply_item.create') }}" method="POST">
            @csrf
    </div>
    <!--end::Aside column-->
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
                            <!--begin::Input-->
                            <div class="mb-10 fv-row">
                                <!--begin::Label-->
                                <label class="required form-label">Id de la distribución de transferencia</label>
                                <!--end::Label-->
                                <select name="supply_transfer_id" id="supply_transfer_id" class="form-select form-control" data-control="select2" data-hide-search="false" data-placeholder="">
                                    <option selected hidden disabled>-- Elige una opción --</option>
                                    @foreach ($data['supply_transfers'] as $transfer)
                                        <option value="{{ $transfer->id }}" @selected($transfer->id == old('supply_transfer_id'))>
                                            {{ $transfer->id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Label-->
                            <label class="required form-label">Artículo</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select name="item_id" id="item_id" class="form-select form-control" data-control="select2" data-hide-search="false" data-placeholder="">
                                <option selected hidden disabled>-- Elige una opción --</option>
                                @foreach ($data['items'] as $item)
                                    <option value="{{ $item->id }}" @selected($item->id == old('item_id'))>
                                        {{ $item->serial_number }}
                                    </option>
                                @endforeach
                            </select>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input-->
                        <div class="mb-10 fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Entregado</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <div class="d-flex gap-3">
                                <select name="delivered" id="delivered" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                                    <option selected hidden disabled>-- Elige una opción --</option>
                                    <option value="1" @selected('1' == old('delivered'))>Si</option>
                                    <option value="0" @selected('0' == old('delivered'))>No</option>
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
                                    @foreach (SupplyStatuses::getAllNames() as $value => $name)
                                        <option value="{{ $value }}" @if ($value == old('status')) selected @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-10 fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Condición</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <div class="d-flex gap-3">
                                <select name="integrity" id="integrity" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                                    @foreach (ItemIntegrities::getAllNames() as $value => $name)
                                        <option value="{{ $value }}" @selected($value == old('integrity'))>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--end::Input group-->
                        <div class="mb-10 fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Detalles</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <textarea class="form-control" name="details" id="details" cols="20" rows="5">{{ old('details') }}</textarea>
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
                <a href="{{ route('base.supply_item.index') }}" id="kt_ecommerce_add_product_cancel" class="btn btn-light me-5">Cancelar</a>
                <!--end::Button-->
                <!--begin::Button-->
                <button type="submit" class="btn btn-primary">
                    <span class="indicator-label">Guardar</span>
                </button>
                <!--end::Button-->
            </div>
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
                'item_id',
                'delivered',
                'status',
                'details',
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
