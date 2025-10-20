@props([
    'colors' => [],
    'frontImages' => [],
    'backImages' => [],
    'rightImages' => [],
    'leftImages' => [],
])
<style>
    .custom-nav-item {
        flex: 1;
        text-align: center;
        min-width: 160px;
    }

    .color-info:has(.color-circle:not([style])) {
        display: none;
    }
</style>

<div class="form d-flex flex-column flex-lg-row mt-6">
    <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
        <!--begin::Thumbnail settings-->
        <div class="card card-flush py-4">
            <div class="card-header d-flex align-items-baseline">
                <div class="card-title">
                    <h3>Previsualización</h3>
                </div>
            </div>
            <div class="card-body text-center pt-0">
                <div id="image-inputs" class="image-input image-input-empty text-center mx-4" style="cursor: pointer;"
                    data-kt-image-input="false">
                    <!--begin::Image preview wrapper-->
                    <div class="image-input-wrapper w-125px h-125px"
                        style="background-image: url('{{ asset('media/products/add-product.png') }}')"
                        id="image-preview-thumbnail">
                    </div>
                    <!--end::Image preview wrapper-->

                    <!--begin::Edit button-->
                    <label id="edit-image-btn"
                        class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                        data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="click"
                        title="Agregar Imagen">
                        <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span
                                class="path2"></span></i>

                        <!--begin::Inputs-->
                        <input type="file" id="image-inputs__image" accept=".png, .jpg, .jpeg, .webp" />
                        <!--end::Inputs-->
                    </label>
                    <!--end::Edit button-->

                    <!--begin::Cancel button-->
                    <label id="remove-image-btn" onclick="this.parentElement.remove()"
                        class="d-none btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                        style="position: absolute; top: 0; right: 0; transform: translateX(50%) translateY(-50%);"
                        data-bs-toggle="tooltip" title="Eliminar Imagen">
                        <i class="ki-outline ki-cross fs-3"></i>
                    </label>
                    <!--end::Cancel button-->

                    <!--end::Image values-->
                    <div id="color-info-container">
                        <input type="hidden" id="image-inputs__color">
                        <input type="hidden" id="image-inputs__view" value="front">
                        <div class="color-info">
                            <div id="image-info__circle" class="color-circle"></div>
                            <div id="image-info__name" class="color-text">Color</div>
                        </div>
                    </div>
                    <!--end::Image values-->
                </div>
            </div>
        </div>
        <!--end::Thumbnail settings-->
    </div>

    <!--begin::Image column-->
    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-5">
        <!--end:::Tabs-->
        <div class="d-flex flex-column gap-7 gap-lg-10">
            <!--begin::Inventory-->
            <div class="card card-flush py-4">
                <!--begin::Card header-->
                <div class="card-header">
                    <!--begin::Card body-->
                    <div id="form-inputs" class="card-body p-0">
                        <!--begin::Input group-->
                        <div class="mt-10 mb-10 fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Color</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <div class="d-flex gap-3">
                                <select id="color_id_image" class="form-select form-control" data-control="select2"
                                    data-placeholder="">
                                    <option selected hidden disabled>-- Elige una opción --</option>
                                    @foreach ($colors as $color)
                                        <option value="{{ $color->id }}" data-hex="{{ $color->hexadecimal }}">
                                            {{ $color->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <div class="fv-row">
                            <div class="col-12">
                                <ul class="nav nav-tabs nav-pills border-0 fs-5 row">
                                    <div class="btn-group btn-group-row d-flex mb-2">
                                        <li class="nav-item custom-nav-item" onclick="setViewTab('front')">
                                            <a class="nav-link active" data-bs-toggle="tab" id="first_view_side"
                                                href="#tab_pane_front">Vista frontal</a>
                                        </li>
                                        <li class="nav-item custom-nav-item" onclick="setViewTab('back')">
                                            <a class="nav-link" data-bs-toggle="tab" href="#tab_pane_back">Vista
                                                trasera</a>
                                        </li>
                                    </div>
                                    <div class="btn-group btn-group-row d-flex">
                                        <li class="nav-item custom-nav-item" onclick="setViewTab('right')">
                                            <a class="nav-link" data-bs-toggle="tab" href="#tab_pane_right">Vista
                                                derecha</a>
                                        </li>
                                        <li class="nav-item custom-nav-item" onclick="setViewTab('left')">
                                            <a class="nav-link" data-bs-toggle="tab" href="#tab_pane_left">Vista
                                                izquierda</a>
                                        </li>
                                    </div>
                                </ul>
                            </div>
                        </div>
                        <div style="visibility: hidden;">
                            <div class="d-flex gap-3">
                                <select disabled id="view_id_image" class="form-select form-control"
                                    data-control="select2" data-placeholder="" readonly>
                                    <option value="front" selected>Frontal</option>
                                    <option value="back">Trasera</option>
                                    <option value="right">Derecha</option>
                                    <option value="left">Izquierda</option>
                                </select>
                            </div>
                        </div>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Card header-->
                <div class="d-flex justify-content-end me-5">
                    <!--begin::Button-->
                    <button id="btn-add-image" type="button" class="btn btn-success mb-3">
                        <span class="indicator-label">Agregar</span>
                    </button>
                    <!--end::Button-->
                </div>
            </div>
            <!--end::Inventory-->
        </div>
        <!--end::Tab content-->
    </div>
    <!--end::Image column-->
</div>

<div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
    <!--end:::Tabs-->
    <div class="d-flex flex-column gap-7 gap-lg-10">
        <!--begin::Inventory-->
        <div class="card card-flush py-4">
            <!--begin::Card header-->
            <div class="card-header">
                <!--begin::Card body-->
                <div class="card-body pt-0" style="padding-left: 0;">
                    <!--BEGIN::IMG TAB settings-->
                    <div class="tab-content" id="myTabContent">
                        <x-product.image-container title="Vista Frontal" viewCode="front" :images="$frontImages"
                            :active="true" />
                        <x-product.image-container title="Vista Trasera" viewCode="back" :images="$backImages" />
                        <x-product.image-container title="Vista Derecha" viewCode="right" :images="$rightImages" />
                        <x-product.image-container title="Vista Izquierda" viewCode="left" :images="$leftImages" />
                    </div>
                    <!--end::Template settings-->
                </div>
            </div>
        </div>
    </div>
</div>
