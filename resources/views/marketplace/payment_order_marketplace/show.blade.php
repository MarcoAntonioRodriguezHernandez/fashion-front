<x-layouts.master-layout title="Orden de: {{ $data->ordermarketplace->user->email }}" cardTitle="Orden de: {{ $data->ordermarketplace->user->email }}">

    <div class="form_padding">
        <!--begin::Form-->
        <form id="staffAdd" class="form d-flex flex-column flex-lg-row"
            action="{{ route('payment_order_marketplace.edit') }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
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
                                    <label class="form-label">Orden de pago</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                    <x-layouts.inputs typeInput="justInputdisabled" name="name" id="name" placeholder="Nombre" 
                                    value="{{ $data->ordermarketplace->user->email }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Total</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="total" id="total" typeofInput="number" min="0"
                                        placeholder="Total" value="{{ $data->total }}" /> 
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Pago</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputdisabled" typeofInput="number"
                                            name="payment" id="payment" placeholder="Pago" min="0"
                                            value="{{ $data->payment }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Tipo de pago</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    {{-- <select name="payment_type_id" id="payment_type_id" class="form-select form-control"
                                        data-control="select2" data-hide-search="true" data-placeholder="">
                                        @foreach ($paymentTypes as $paymentType)
                                            <option value="{{ $paymentType->id }}" @if ($paymentType->id == $data->paymentTypeId->id) selected @endif>
                                                {{ $paymentType->name }}
                                            </option>
                                        @endforeach
                                    </select> --}}
                                    <x-layouts.inputs typeInput="justInputdisabled" name="name" id="name" placeholder="Nombre" 
                                    value="{{ $data->paymentTypeId->name }}" disabled />
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
                                        <x-layouts.inputs typeInput="justInputdisabled" typeofInput="text" name="status" value="{{ $data->status == 1 ? 'Activo' : 'Inactivo' }}" />
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
                    routeCancel="{{ route('marketplace.payment_order_marketplace.index') }}"
                    isShow="true"
                    routeEdit="{{ route('payment_order_marketplace.edit.view', $data->id) }}"
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
                'order_marketplace_id',
                'total',
                'payment',
                'payment_type_id',
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
