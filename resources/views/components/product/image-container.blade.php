@props([
    'title' => '',
    'viewCode' => '',
    'images' => [],
    'active' => false,
])

<div @class(['tab-pane fade show', 'active' => $active]) id="tab_pane_{{ $viewCode }}" role="tabpanel">
    <h3 style="margin-left: 1.3em;" class="mt-4">{{ $title }}</h3>
    <div class="scroll-container">
        <div class="insider-cont" id="image-container-{{ $viewCode }}">
            <div class="image-input image-input-empty" style="cursor: pointer;" data-kt-image-input="false" onclick="previewImage(this, true)">
                <!--begin::Image preview wrapper-->
                <div class="image-input-wrapper w-125px h-125px" style="box-shadow: 0 0 9px 3px #00000036; background-image: url('{{ asset('media/products/add-product.png') }}')" id="image-preview-{{ $viewCode }}">
                </div>
                <!--end::Image preview wrapper-->
            </div>

            @foreach ($images as $image)
                <div class="image-input text-center image-input-changed">
                    <!--begin::Image preview wrapper-->
                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{ $image->src_image }});">
                    </div>
                    <!--end::Image preview wrapper-->

                    <!--begin::Cancel button-->
                    <label onclick="this.parentElement.remove()" class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow" style="position: absolute; top: 0; right: 0; transform: translateX(50%) translateY(-50%);" data-bs-toggle="tooltip" aria-label="Eliminar Imagen" data-bs-original-title="Eliminar Imagen" data-kt-initialized="1">
                        <i class="ki-outline ki-cross fs-3"></i>
                    </label>
                    <!--end::Cancel button-->

                    <!--end::Image values-->
                    <input type="hidden" value="{{ $image->id }}" name="keep_images[]">

                    <div class="color-info">
                        <div class="color-circle" style="background-color: {{ $image->color->hexadecimal }};"></div>
                        <div class="color-text">
                            {{ $image->color->name }}
                        </div>
                    </div>
                    <!--end::Image values-->
                </div>
            @endforeach
        </div>
    </div>
</div>
