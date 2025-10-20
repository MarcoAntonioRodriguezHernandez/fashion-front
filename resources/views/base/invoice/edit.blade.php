<x-layouts.master-layout title="Editar factura" cardTitle="Editar factura: {{ $data->invoice_number }}">

    <div class="form_padding">
        <!--begin::Form-->
        <form id="staffAdd" class="form d-flex flex-column flex-lg-row" action="{{ route('base.invoice.edit') }}"
            method="POST" enctype="multipart/form-data">
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
                                    <label class="required form-label">Cliente</label>
                                    <!--end::Label-->
                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                    <!--begin::Input-->
                                    <select name="buyer" id="buyer" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ ($user->id == old('buyer', $data->buyer)) ? 'selected' : '' }}>
                                                {{ $user->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Número de factura</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputNumber" typeofInput="text"
                                            name="invoice_number" id="invoice_number" placeholder="Número de factura"
                                            value="{{ $data->invoice_number }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Estado de pago</label>
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <select name="payment_status" id="payment_status"
                                            class="form-select form-control" data-control="select2"
                                            data-hide-search="true" data-placeholder="">
                                            @foreach (PaymentStatuses::getAllNames() as $value => $name)
                                                <option value="{{ $value }}"
                                                    @if ($value == old('payment_status', $data->payment_status)) selected @endif>
                                                    {{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Fecha de emisión</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <input type="date" class="form-control" name="issuance_date"
                                            id="issuance_date" value="{{ $data->issuance_date }}"
                                            max="{{ date('Y-m-d') }}">
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Método de pago</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="payment_type_id" id="payment_type_id" class="form-select form-control"
                                        data-control="select2" data-hide-search="true" data-placeholder="">
                                        @foreach ($paymentMethods as $paymentType)
                                            <option value="{{ $paymentType->id }}"
                                                @if ($paymentType->id == $data->paymentType->id) selected @endif>
                                                {{ $paymentType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Tipo de cambio</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputNumber" typeofInput="number"
                                            name="exchange_rate" id="exchange_rate" placeholder="Tipo de cambio"
                                            min="0" value="{{ $data->exchange_rate }}" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Archivo de factura</label>
                                    <label class="form-label">Archivo actual:
                                        {{ $data->invoiceFile->file }}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        @if ($data->invoiceFile)
                                        <input type="file" class="form-control" name="file" id="file"/>
                                        <input type="hidden" class="form-control" name="invoice_file" id="invoice_file"
                                            value="1" />
                                        @else
                                        <input type="file" class="form-control" name="file" id="file" required/>
                                        <input type="hidden" class="form-control" name="invoice_file" id="invoice_file"
                                            value="0" />
                                        @endif
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
                    routeCancel="{{ route('base.invoice.index') }}"
                    :module="ModuleAliases::INVOICE"
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
                'buyer',
                'invoice_number',
                'payment_status',
                'issuance_date',
                'payment_type_id',
                'exchange_rate',
                'invoice_file',

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
