<script setup>
import { onMounted, ref } from 'vue';


// ------------------------
// Component Attributes
// ------------------------

const model = defineModel();

const props = defineProps({
    item: { type: Object, required: true },
    isSelected: { type: Boolean, default: false },
    onClick: { type: Function, default: () => { } },
    disabled: { type: Boolean, default: false },
    checkbox: { type: Boolean, default: true },
});

onMounted(() => {
    if (rootElement.value)
        [...rootElement.value.querySelectorAll('[data-bs-toggle="tooltip"]')].map(el => new bootstrap.Tooltip(el, { // Initialize tooltips
            trigger: 'hover'
        }));
});

const rootElement = ref(null);
</script>

<template>
    <div ref="rootElement" class="card h-100" :class="{ 'shadow-green': isSelected }">
        <!--begin::Body-->
        <div class="ribbon ribbon-top ribbon-vertical">
            <div :class="['ribbon-label', 'bg-' + item.integrityColor]">{{ item.integrityName }}</div>
        </div>
        <div class="d-flex flex-column card-body text-center">
            <div v-if="checkbox" class="form-check form-check-sm form-check-custom form-check-solid">
                <input class="form-check-input" type="checkbox" v-model="model" :value="item.id" />
            </div>
            <!--begin::Product img-->
            <button type="button" class="btn flex-grow-1" @click="onClick(item.id)" :disabled="disabled">
                <img :src="item.first_image" class="col-12 rounded-3 mb-4" :alt="item.product_name">
            </button>
            <!--end::Product img-->
            <!--begin::Info-->
            <div class="mb-2">
                <!--begin::Title-->
                <div class="text-center">
                    <div class="d-flex flex-column">
                        <span class="fw-bold text-gray-800 fs-6">{{ item.product_name }}</span>
                        <span class="fw-bold text-gray-800 fs-6">{{ item.id }}</span>
                        <span class="fw-bold text-gray-800 fs-6">{{ item.barcode }}</span>
                        <span class="fw-bold text-gray-800 fs-6">{{ item.code_origin }}</span>
                    </div>

                    <span class="text-gray-500 fw-semibold d-block fs-6 mt-2">{{ item.designer_name }}</span>
                    <div class="d-flex flex-column text-start mt-2">
                        <div class="mb-1 d-flex">
                            <template v-for="state in item.states">
                                <span v-if="state.enabled" :class="['state-square', 'bg-' + state.color]" data-bs-toggle="tooltip" :data-bs-title="state.name"></span>
                            </template>
                        </div>
                        <div class="mb-1"><span class="fw-bold">Talla: </span>
                            <span>{{ item.size_name }}</span>
                        </div>
                        <div class="mb-1"><span class="fw-bold">Ubicación: </span>
                            <span>{{ item.store_name }}</span>
                        </div>
                        <div class="mb-1"><span class="fw-bold">Renta: </span>
                            <span>$ {{ item.prices_rent['4'].toFixed(2) }}</span>
                        </div>
                        <div class="mb-1"><span class="fw-bold">Venta: </span>
                            <span>$ {{ item.price_sale.toFixed(2) }}</span>
                        </div>
                        <div class="mb-1"><span class="fw-bold">Venta Completo: </span>
                            <span>$ {{ item.full_price.toFixed(2) }}</span>
                        </div>
                        <div class="mb-1"><span class="fw-bold">Ingreso: </span>
                            <span>{{ item.entered_date }}</span>
                        </div>
                        <div class="mb-1"><span class="fw-bold">Rentas: </span>
                            <span>{{ item.amount_rents }}</span>
                        </div>
                        <div v-if="item.last_rent_date" class="mb-1"><span class="fw-bold">Ultima renta: </span>
                            <span>{{ item.last_rent_date }}</span>
                        </div>
                    </div>
                </div>
                <!--end::Title-->
            </div>
            <!--end::Info-->
        </div>
        <!--end::Body-->
    </div>
</template>
