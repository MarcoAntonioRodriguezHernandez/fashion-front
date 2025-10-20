<x-layouts.master-layout title="Editar mercado de pedido" cardTitle="Editar Orden: {{ $data->code }}">

    <div class="form_padding">
        <!--begin::Form-->
        <form id="staffAdd" class="form d-flex flex-column flex-lg-row" action="{{ route('marketplace.order_marketplace.edit') }}" method="POST" enctype="multipart/form-data">
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
                                    <label class="required form-label">Cliente</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="client_id" id="client_id" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" @if ($user->id == $data->user->id) selected @endif>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Empleado</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" name="employee_id" id="employee_id" class="form-control" value="{{ $data->employee->name }}" readonly>
                                    <!--end::Input-->
                                </div>
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                    <label class="required form-label">Marketplace</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="marketplace_id" id="marketplace_id" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                                        @foreach ($marketplaces as $marketplace)
                                            <option value="{{ $marketplace->id }}" @if ($marketplace->id == $data->marketplace->id) selected @endif>
                                                {{ $marketplace->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Cantidad</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputNumber" typeofInput="number" name="amount" id="amount" placeholder="Valor" min="0" value="{{ $data->amount }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Descuento</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputNumber" typeofInput="number" name="discount" id="discount" placeholder="Valor" min="0" value="{{ $data->discount }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Recargos</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputNumber" typeofInput="number" name="surcharge" id="surcharge" placeholder="Valor" min="0" value="{{ $data->surcharge }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Tienda</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="store_id" id="store_id" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                                        @foreach ($stores as $store)
                                            <option value="{{ $store->id }}" @if ($store->id == $data->store->id) selected @endif>
                                                {{ $store->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Número de productos</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputNumber" typeofInput="number" name="number_products" id="number_products" placeholder="Valor" min="0" value="{{ $data->number_products }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Estatus</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <select name="status" id="status" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                                            <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>
                                                Vigente</option>
                                            <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>
                                                Cancelado</option>
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
                    <x-btn-cancel-save routeCancel="{{ route('marketplace.order_marketplace.index') }}" />
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
                'client_id',
                'amount',
                'discount',
                'surcharge',
                'store_id',
                'number_products',
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
