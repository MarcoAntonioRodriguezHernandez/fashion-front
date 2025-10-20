<x-layouts.master-layout title="Ver Cupón" cardTitle="Ver Cupón">
    <div class="form_padding">
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
                                        <x-layouts.inputs typeInput="justInput" name="code" id="code" placeholder="Código" value="{{ $data->code }}" disabled />
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <label class="required form-label">Usos disponibles</label>
                                        <x-layouts.inputs typeInput="justInput" name="uses_amount" id="uses_amount" placeholder="Usos disponibles" value="{{ $data->uses_amount }}" disabled />
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <label class="required form-label">Categoria</label>
                                        <x-layouts.inputs typeInput="justInputdisabled" name="category_id" id="category_id" placeholder="Nombre" value="{{ $data->category->name }}" disabled />

                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <label class="required form-label">Tipo de venta</label>
                                        <div class="d-flex gap-3">
                                            <select name="sale_type" id="sale_type" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="" disabled>
                                                @foreach (OrderSaleType::getAllNames() as $value => $name)
                                                <option value="{{ $value }}" @selected($data->sale_type==$value)>
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
                                        <x-layouts.inputs typeInput="justInput" name="min_products" id="min_products" placeholder="Cantidad mínima de productos" value="{{ $data->min_products }}" disabled />
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <div class="col-md-6">
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <label class="required form-label">Tipo de descuento</label>
                                        <div class="d-flex gap-3">
                                            <select name="coupon_type" id="coupon_type" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="" disabled>
                                                @foreach (CouponTypes::getAllNames() as $value => $name)
                                                <option value="{{ $value }}" @selected($data->coupon_type==$value)>
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
                                        <x-layouts.inputs typeInput="justInput" name="discount" id="discount" placeholder="Cantidad de descuento" value="{{ $data->discount }}" disabled />
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <label class="required form-label">Fecha de inicio</label>
                                        <input type="date" class="form-control" name="date_start" id="date_start" value="{{ $data->date_start }}" disabled />
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <label class="required form-label">Fecha de fin</label>
                                        <input type="date" class="form-control" name="date_end" id="date_end" value="{{ $data->date_end }}" disabled />
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <label class="required form-label">Estatus</label>
                                        <div class="d-flex gap-3">
                                            <select name="status" id="status" class="form-select form-control" data-control="select2" data-hide-search="true" disabled>
                                                @foreach(CategoryStatuses::getAllNames() as $value => $name)
                                                <option value="{{ $value }}" @selected($data->status==$value)>
                                                    {{ $name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
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
            <x-btn-cancel-save :module="ModuleAliases::COUPON" routeCancel="{{ route('base.coupon.index') }}" isShow="true" routeEdit="{{ route('base.coupon.edit.view', $data->id) }}" />
        </div>
        <!--end::Main column-->
    </div>
</x-layouts.master-layout>
