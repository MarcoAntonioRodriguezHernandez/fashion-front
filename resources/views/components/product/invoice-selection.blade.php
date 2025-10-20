<div class="card card-flush py-4">
    <input type="hidden" name="invoice_id" id="invoice_id" disabled>

    <!--begin::Card header-->
    <div class="card-header">
        <!--begin::Card body-->
        <div class="card-body pt-0 mt-5">
            <!--begin::Input group-->
            <div class="row">
                <!--begin::Input group-->
                <div class="mb-2 col-md-12">
                    <!--begin::Label-->
                    <label class="required form-label">Número de factura</label>
                    <div class="d-flex gap-3">
                        <!--begin::Input-->
                        <x-layouts.inputs typeInput="justInput" name="invoice[invoice_number]" id="invoice_number" placeholder="Número de factura" value="{{ old('invoice_number') }}" />
                        <!--end::Input-->
                    </div>
                    <!--end::Input-->
                </div>

                <div class="d-flex justify-content-end mb-10">
                    <button onclick="searchInvoiceByNumber('{{ route('base.invoice.by_number', ':number') }}')" id="btn-hideProvider" class="btn btn-primary" type="button">Buscar Factura Existente</button>
                </div>

                <!--begin::Input group-->
                <div id="invoice-container" class="row mb-10">
                    <!--begin::Input group-->
                    <div class="mb-10 col-md-6">
                        <!-- begin::Label-->
                        <label class="required form-label">Comprador</label>
                        <!-- end::Label-->
                        <!-- begin::Select-->
                        <select name="invoice[buyer_id]" id="invoice_buyer_id" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                            <option selected hidden disabled>-- Elige una opción --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected($user->id == old('buyer_id'))>
                                    {{ $user->name }} {{ $user->last_name }}</option>
                            @endforeach
                        </select>
                        <!-- end::Select-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="mb-10 col-md-6">
                        <!-- begin::Label-->
                        <label class="required form-label">Estado de pago</label>
                        <!-- end::Label-->
                        <!-- begin::Select-->
                        <select name="invoice[payment_status]" id="invoice_payment_status" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                            <option selected hidden disabled>-- Elige una opción --</option>
                            @foreach (PaymentStatuses::getAllNames() as $value => $name)
                                <option value="{{ $value }}" @selected($value == old('payment_status'))>{{ $name }}</option>
                            @endforeach
                        </select>
                        <!-- end::Select-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="mb-10 col-md-6">
                        <!--begin::Label-->
                        <label class="required form-label">Fecha de emision</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="date" class="form-control" name="invoice[issuance_date]" id="invoice_issuance_date" required value="{{ old('issuance_date') }}" max="{{ date('Y-m-d') }}">
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="mb-10 col-md-6">
                        <!-- begin::Label-->
                        <label class="required form-label">Tipo de pago</label>
                        <!-- end::Label-->
                        <!-- begin::Select-->
                        <select name="invoice[payment_type_id]" id="invoice_payment_type_id" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                            <option selected hidden disabled>-- Elige una opción --</option>
                            @foreach ($paymentTypes as $paymentType)
                                <option value="{{ $paymentType->id }}" @selected($paymentType->id == old('payment_type_id'))>{{ $paymentType->name }}</option>
                            @endforeach
                        </select>
                        <!-- end::Select-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="mb-10 col-md-6">
                        <!--begin::Label-->
                        <label class="required form-label">Tipo de cambio</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <x-layouts.inputs typeInput="justInputNumber" name="invoice[exchange_rate]" id="invoice_exchange_rate" typeofInput="number" placeholder="Tipo de cambio" value="{{ old('exchange_rate') }}" />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="mb-10 col-md-6">
                        <!--begin::Label-->
                        <label class="required form-label">Archivo de factura</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <x-layouts.inputs typeInput="justInput" name="invoice[invoice_file]" id="invoice_file" typeofInput="file" value="{{ old('invoice_file') }}" />

                        <div class="input-group d-none" id="invoice_file_view" data-base-url="">
                            <span class="input-group-text">
                                <i class="ki-duotone ki-file fs-1 text-danger">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <a href="#invoice-container" class="form-control" target="_blank">
                                <span>Ver archivo</span>
                            </a>
                        </div>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Input group-->
            </div>
        </div>
        <!--end::Input group-->
    </div>
</div>
