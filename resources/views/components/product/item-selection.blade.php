@props([
    'colors' => [],
    'sizes' => [],
    'statuses' => [], 
])

<!--begin::Inventory-->
<div id="inventory-element-factory">
    <div class="row mb-5 mt-5">
        <!--begin::Input group-->
        <div class="col-12 col-md-6 mt-3">
            <!--begin::Label-->
            <label class="required form-label">Color</label>
            <!--end::Label-->
            <!--begin::Input-->
            <select name="color_id" id="color_id" class="form-select form-control" data-control="select2"
                data-placeholder="Color" onchange="handleColorSelection(this)">
                <option selected hidden disabled>-- Elige un color --</option>
                @foreach ($colors as $color)
                    <option value="{{ $color->id }}" @selected(old('color_id') == $color->id)>
                        {{ $color->name }}
                    </option>
                @endforeach
            </select>
            <!--end::Input-->
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="col-12 col-md-6 mt-3">
            <!--begin::Label-->
            <label class="required form-label">Talla</label>
            <!--end::Label-->
            <!--begin::Input-->
            <select id="size_id" class="form-select form-control" data-control="select2" data-hide-search="true"
                data-placeholder="Talla">
                <option selected hidden disabled>-- Elige una opción --</option>
                @foreach ($sizes as $size)
                    <option value="{{ $size->id }}" @selected(old('size_id'))
                        data-characteristics="|{{ $size->characteristics()->pluck('characteristics.id')->join('|') }}|">
                        {{ $size->full_name }}
                    </option>
                @endforeach
            </select>
            <!--end::Input-->
        </div>
        <!--end::Input group-->
    </div>

    <div class="row">
        <!--begin::Input group-->
        <div class="col-12 col-md-6">
            <!--begin::Label-->
            <label class="required form-label">Precio venta</label>
            <!--end::Label-->
            <!--begin::Input-->
            <div class="d-flex gap-3">
                <x-layouts.inputs typeInput="justInputNumber" typeofInput="number" id="price_sale"
                    placeholder="Precio venta" min="0" value="{{ old('price_sale') }}" />
            </div>
            <!--end::Input-->
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="col-12 col-md-6">
            <!--begin::Label-->
            <label class="required form-label">Cantidad Agregada</label>
            <!--end::Label-->
            <!--begin::Input-->
            <div class="d-flex gap-3">
                <x-layouts.inputs typeInput="justInputNumber" typeofInput="number" id="amount"
                    placeholder="Cantidad Agregada" min="0" value="{{ old('amount') }}" />
            </div>
            <!--end::Input-->
        </div>
        <!--end::Input group-->
    </div>

    <!--begin::Input group-->
    <div class="row">
        <div class="col-12 col-md-6 mt-3">
            <label class="required form-label">Estatus</label>
            <select id="status" class="form-select form-control" data-control="select2" data-hide-search="true"
                data-placeholder="Estatus">
                <option selected hidden disabled>-- Elige una opción --</option>
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <!--end::Input group-->


    <div class="row">
        <!--begin::Card buttons-->
        <div class="offset-9 col-3 d-flex justify-content-end px-2">
            <!--begin::Button-->
            <button id="btn-add-inventory" type="button" class="col-12 col-md-auto btn btn-success"
                onclick="addInventoryElement()">
                <span class="indicator-label">Agregar Artículos</span>
            </button>
            <!--end::Button-->
        </div>
        <!--end::Card buttons-->
    </div>

    <!--begin::Card buttons-->
    <div class="col-12 d-flex justify-content-end px-2 mt-1">
        <!--begin::Button-->
        <button id="btn-remove-inventory" type="button" class="col-12 col-md-auto btn btn-danger d-none">
            <span class="indicator-label">Remover Artículos</span>
        </button>
        <!--end::Button-->
    </div>
</div>
<!--end::Inventory-->

<!--begin::Inventory Container-->
<div id="inventory-title" class="col-12 d-none">
    <h2 class="my-3">Inventario Agregado</h2>
    <hr>
</div>
<!--end::Inventory Container-->

<div id="inventory-container" class="col-12"></div>
<!--end::Main column-->
