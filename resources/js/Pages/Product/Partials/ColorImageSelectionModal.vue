<script setup>
import { onMounted, ref } from 'vue';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    productName: { type: String, required: true },
    productImages: { type: Array, required: true },
    onImageSelectionConfirm: { type: Function, required: true },
});

onMounted(() => {
    const modalElement = document.getElementById('modal-color-image-selection');
    modal = new bootstrap.Modal(modalElement);
});

defineExpose({
    showWith: (newColor) => {
        selectedColor.value = newColor;

        modal.show();
    },
    hide: () => {
        modal.hide();
    },
});

// ------------------------
// Declarations
// ------------------------

let modal = null;
const selectedColor = ref(null);

const onImageSelectionConfirm = (colorId, productImageId) => {
    if (colorId && productImageId)
        props.onImageSelectionConfirm(colorId, productImageId);
};
</script>

<template>
    <div class="modal fade" id="modal-color-image-selection" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Imagen para el color {{ selectedColor?.name }} del producto {{ productName }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row row-cols-2 row-cols-m-2 row-cols-lg-3 row-cols-xl-5 g-5 align-items-center">
                        <div v-for="image in productImages" class="image-input text-center">
                            <!--begin::Product img-->
                            <button type="button" class="btn btn-secondary placeholder-image shadow-sm col-12 p-0" @click="onImageSelectionConfirm(selectedColor.id, image.id)">
                                <div class="image-input-wrapper h-150px w-100" :style="{ 'background-image': 'url(' + image.src_image + ')', 'background-size': 'contain' }"></div>
                            </button>
                            <!--end::Product img-->

                            <div class="color-info w-100 justify-content-center">
                                <div class="color-circle" :style="{ 'background-color': image.color.hexadecimal }"></div>
                                <div class="color-text fw-bold">
                                    {{ image.color.name }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</template>
