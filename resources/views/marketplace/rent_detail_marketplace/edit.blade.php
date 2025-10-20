<x-layouts.master-layout title="Editar mercado de detalles de alquiler"   cardTitle="Editar mercado de detalles de alquiler">

    <div class="form_padding">
        <!--begin::Form-->
        <form id="staffAdd" class="form d-flex flex-column flex-lg-row"
            action="{{ route('marketplace.rent_detail_marketplace.edit') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

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
                                    <label class="required form-label">Órden de Artículo</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="item_order_marketplace_id" id="item_order_marketplace_id"
                                        class="form-select form-control" data-control="select2" data-hide-search="true"
                                        data-placeholder="">
                                        @foreach ($itemOrderMarketplace as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($item->id == $data->itemOrderMarketplace->id) selected @endif>
                                                {{ $item->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Hora de inicio de datos</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <input type="datetime-local" class="form-control" name="date_start"
                                            id="date_start" value="{{ $data->date_start }}">
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Hora final de datos</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <input type="datetime-local" class="form-control" name="date_end"
                                            id="date_end" value="{{ $data->date_end }}">
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Precio del seguro</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInput" typeofInput="number" name="insurance_price" id="insurance_price" placeholder="Precio del seguro" min="0" value="{{ old('insurance_price', $data->insurance_price) }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Descripción</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <textarea class="form-control" name="description" id="description" cols="20" rows="5">{{ $data->description }}</textarea>
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
                                            <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>
                                                Activo</option>
                                            <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>
                                                Inactivo</option>
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
                    <x-btn-cancel-save routeCancel="{{ route('marketplace.rent_detail_marketplace.index') }}" />
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
                'product_order_marketplace_id',
                'date_start',
                'date_end',
                'description',
                'status'

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
