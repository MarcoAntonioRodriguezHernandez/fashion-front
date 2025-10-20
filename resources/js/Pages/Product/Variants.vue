<script setup>
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Master from '@layouts/Master.vue';
import ColorImageSelectionModal from '@/Pages/Product/Partials/ColorImageSelectionModal.vue';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    product: { type: Object, required: true },
    productImages: { type: Array, required: true },
    productVariants: { type: Array, required: true },
});

const colorImageSelectionModal = ref(null);

const showColorImageSelection = (color) => {
    colorImageSelectionModal.value.showWith(color);
}

const onImageSelectionConfirm = (colorId, productImageId) => {
    router.put(url('product/' + props.product.id + '/variants'), {
        color_id: colorId,
        product_image_id: productImageId,
    }, {
        onSuccess: () => {
            colorImageSelectionModal.value.hide();
        },
    });
}

const deleteVariantsForColor = (color) => {
    createAlert('¿Eliminar variantes del color ' + color.name + '?', 'Todas las variantes para este color serán eliminadas', 'warning', false, {
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar',
        showCancelButton: true,
    }).then((result) => {
        if (result.isConfirmed)
            router.delete(url('product/' + props.product.id + '/variants/' + color.id));
    });
}
</script>

<template>
    <Master title="Variantes del producto" :cardTitle="product.full_name">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
            <div v-for="variantInfo in productVariants" class="h-100 p-5">
                <div class="card">
                    <!--begin::Body-->
                    <div class="d-flex flex-column card-body text-center">
                        <div :id="'variant-images-' + variantInfo.color.id" class="carousel slide">
                            <div class="carousel-indicators">
                                <button v-for="index in variantInfo.images.length" type="button" :data-bs-target="'#variant-images-' + variantInfo.color.id" :data-bs-slide-to="index" :class="{ active: index == 0, 'bg-primary': true }"></button>
                            </div>

                            <div class="carousel-inner">
                                <div v-for="image in variantInfo.images" class="carousel-item active">
                                    <div class="image-input text-center mt-5">
                                        <!--begin::Image preview wrapper-->
                                        <div class="rounded-2 placeholder-image shadow-sm">
                                            <div class="image-input-wrapper w-125px h-250px w-md-200px h-md-350px" :style="{ 'background-image': 'url(' + image.src_image + ')' }"></div>
                                        </div>
                                        <!--end::Image preview wrapper-->

                                        <!--begin::Delete button-->
                                        <label v-if="variantInfo.total_items == 0" @click="deleteVariantsForColor(variantInfo.color)" class="btn btn-icon btn-circle btn-color-danger btn-active-color-danger w-25px h-25px bg-body shadow" style="position: absolute; top: 0; right: 30px; transform: translateX(50%) translateY(-50%);">
                                            <i class="ki-duotone ki-abstract-11 fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </label>
                                        <!--end::Delete button-->

                                        <!--begin::Update image button-->
                                        <label @click="showColorImageSelection(variantInfo.color)" class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow" style="position: absolute; top: 0; right: 0; transform: translateX(50%) translateY(-50%);">
                                            <i class="ki-duotone ki-arrows-circle fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </label>
                                        <!--end::Update image button-->
                                    </div>
                                </div>
                            </div>

                            <button class="carousel-control-prev" type="button" :data-bs-target="'#variant-images-' + variantInfo.color.id" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" :data-bs-target="'#variant-images-' + variantInfo.color.id" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>

                        <div class="mt-2 text-center">
                            <div class="color-info w-100 justify-content-center">
                                <div class="color-circle" :style="{ 'background-color': variantInfo.color.hexadecimal }"></div>
                                <div class="color-text fw-bold">
                                    {{ variantInfo.color.name }}
                                </div>
                            </div>
                        </div>
                        <div class="mt-2"><span class="fw-bold">Tallas: </span>
                            <span>{{ variantInfo.sizes.map(s => s.full_name).join(', ') }}</span>
                        </div>
                        <div class="mt-2"><span class="fw-bold">Artículos: </span>
                            <span>{{ variantInfo.total_items }}</span>
                        </div>
                    </div>
                    <!--end::Body-->
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <a :href="url('product/' + product.id)" class="btn btn-primary me-5">
                <span class="indicator-label">Aceptar</span>
            </a>
        </div>
    </Master>

    <ColorImageSelectionModal ref="colorImageSelectionModal" :productName="product.full_name" :productImages="productImages" :onImageSelectionConfirm="onImageSelectionConfirm" />
</template>

<style>
.placeholder-image {
    background-image: url(/media/products/image404.png);
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    border: 2px solid #e0e0e0 !important;
}

/* FIXME: Move to styles file */
.color-info {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    margin-top: 0.5em;
}

.color-circle {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    margin-right: 5px;
    border: 1px solid black;
}

.color-text {
    font-size: 12px;
}
</style>
