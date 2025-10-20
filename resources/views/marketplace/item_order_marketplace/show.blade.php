<x-layouts.master-layout title="Orden de Articulo" cardTitle="Mostrar Orden de Articulo: {{$data->code}}">

    <div class="form_padding">
        <!--begin::Form-->
        <div class="form d-flex flex-column flex-lg-row">            
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
                                    <label class="required form-label">Artículo</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="Artículo" id="Artículo" placeholder="Artículo" value="{{ $data->item->barcode }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
            
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Órden</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="Órden" id="Órden" placeholder="Órden" value="{{ $data->orderMarketplace->code }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Notas Adicionales</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <textarea class="form-control" cols="20" rows="5" disabled>{{ $data->additional_notes }}</textarea>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Precio de los Ajustes</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="d-flex gap-3">
                                        <x-layouts.inputs typeInput="justInputdisabled" value="{{ $data->fitting_price ?? 'Sin ajustes' }}" disabled />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                @if ($data->fitting_price)
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <label class="required form-label">Notas de Ajustes</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <div class="d-flex gap-3">
                                            <textarea class="form-control" cols="20" rows="5" disabled>{{ $data->fitting_notes }}</textarea>
                                        </div>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                @endif

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Tipo de venta</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="Tipo de venta" id="Tipo_de_venta" placeholder="Tipo de venta" value="{{$data->sale_type == 1 ? 'Renta' : 'Venta'}}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
            
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Estatus</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="Estatus" id="Estatus" placeholder="Estatus" value="{{$data->status == 1 ? 'Activo' : 'Inactivo'}}" disabled />
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
                    routeCancel="{{ route('marketplace.item_order_marketplace.index') }}"
                    isShow="true"
                    routeEdit="{{ route('marketplace.item_order_marketplace.edit.view', $data->id) }}"
                    />
                </div>
                <!--end::Main column-->
            </div>
        </div>
</x-layouts.master-layout>
