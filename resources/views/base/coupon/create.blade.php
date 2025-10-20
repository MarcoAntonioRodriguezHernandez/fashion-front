<x-layouts.master-layout title="Crear Cupón" cardTitle="Crear Cupón">

    <div class="form_padding">
        <!--begin::Form-->
        <form id="staffAdd" class="form d-flex flex-column flex-lg-row" action="{{ route('base.coupon.create') }}" method="POST" enctype="multipart/form-data">
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
                                <div class="row">
                                    <div class="col-md-6">
                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">Código</label>
                                            <x-layouts.inputs typeInput="justInput" name="code" id="code" placeholder="Código" value="{{ old('code') }}" />
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">Usos disponibles</label>
                                            <x-layouts.inputs typeInput="justInput" typeofInput="number" name="uses_amount" id="uses_amount" placeholder="Usos disponibles" value="{{ old('uses_amount') }}" />
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">Categoría</label>
                                            <select name="category_id" id="category_id" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                                                <option selected hidden disabled>-- Elige una opción --</option>
                                                @foreach ($data['categories'] as $category)
                                                <option value="{{ $category->id }}" @selected($category->id == old('category_id'))>
                                                    {{ $category->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">Tipo de venta</label>
                                            <div class="d-flex gap-3">
                                                <select name="sale_type" id="sale_type" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                                                    @foreach (OrderSaleType::getAllNames() as $value => $name)
                                                    <option value="{{ $value }}" @selected(old('sale_type')==$value)>
                                                        {{ $name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">Cantidad mínima de productos</label>
                                            <x-layouts.inputs typeInput="justInput" typeofInput="number" name="min_products" id="min_products" placeholder="Cantidad mínima de productos" value="{{ old('min_products') }}" />
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <div class="col-md-6">
                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">Tipo de descuento</label>
                                            <div class="d-flex gap-3">
                                                <select name="coupon_type" id="coupon_type" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                                                    @foreach (CouponTypes::getAllNames() as $value => $name)
                                                    <option value="{{ $value }}" @selected(old('coupon_type')==$value)>
                                                        {{ $name }}
                                                    </option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">Cantidad de descuento</label>
                                            <x-layouts.inputs typeInput="justInput" typeofInput="number" name="discount" id="discount" placeholder="Cantidad de descuento" value="{{ old('discount') }}" />
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">Fecha de inicio</label>
                                            <input type="date" class="form-control" name="date_start" id="date_start" value="{{ old('date_start') }}" max="{{ date('Y-m-d') }}">
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">Fecha de fin</label>
                                            <input type="date" class="form-control" name="date_end" id="date_end" value="{{ old('date_end') }}" min="{{ date('Y-m-d') }}" />
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">Estatus</label>
                                            <div class="d-flex gap-3">
                                                <select name="status" id="status" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                                                    <option selected hidden disabled>-- Elige una opción --</option>
                                                    <option value="1" @selected(('status' )=='1' )>Activo</option>
                                                    <option value="0" @selected(('status' )=='0' )>Inactivo</option>
                                                </select>
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card header-->
                    </div>
                    <!--end::Inventory-->
                </div>
                <!--end::Tab content-->
                <x-btn-cancel-save routeCancel="{{ route('base.coupon.index') }}" :module="ModuleAliases::COUPON" />
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
                'code',
                'uses_amount',
                'category_id',
                'sale_type',
                'min_products',
                'coupon_type',
                'discount',
                'start_date',
                'end_date',
                'status',
            ].reduce((acc, field) => (acc[field] = {
                validators: {
                    notEmpty: {
                        message: '{{ __('
                        validation.required ', ['
                        attribute ' => ': attr ']) }}'.replace(':attr',
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