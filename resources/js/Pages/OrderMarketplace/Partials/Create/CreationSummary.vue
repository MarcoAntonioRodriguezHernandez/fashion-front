<script setup>
import { computed, ref } from 'vue';
import Popper from 'vue3-popper';
import DiscountOffCanvas from '@/Pages/OrderMarketplace/Partials/Create/DiscountOffCanvas.vue';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    selectedItems: { type: Object, required: true },
    selectedShipping: { type: Object, default: null },
    saleTypes: { type: Object, required: true },
    onConfirm: { type: Function, required: true },
    reservedVariants: { type: Array, default: () => [] },
    confirmEnabled: { type: Boolean, required: true },
});

// ------------------------
// Declarations
// ------------------------

const discountInfo = ref(null);

const reservationConflict = computed(() => {
    return Object.values(props.selectedItems).some(item => props.reservedVariants.includes(item.value.product_variant_id));
});

const subtotalByType = computed(() => {
    let subtotals = {};

    for (const saleType in props.saleTypes) {
        subtotals[saleType] = Object.values(props.selectedItems)
            .filter(item => item.sale.sale_type == saleType)
            .reduce((acc, item) => acc + item.sale.subtotal, 0);
    }

    return subtotals;
});

const subtotalPrice = computed(() => {
    let total = 0;

    for (const saleType in props.saleTypes) {
        total += subtotalByType.value[saleType];
    }

    if (props.selectedShipping) {
        total += props.selectedShipping.shipping_price.price;
    }

    return total;
});

const totalPrice = computed(() => {
    return subtotalPrice.value - (discountInfo.value ? discountInfo.value.value : 0);
});

const submitOrderConfirm = () => {
    props.onConfirm(discountInfo.value);
}
</script>

<template>
    <!--begin::Pos order-->
    <div class="card card-flush bg-body" id="kt_pos_form">
        <!--begin::Header-->
        <div class="card-header pt-5">
            <h3 class="card-title fw-bold text-gray-800 fs-2qx">Resumen de la orden</h3>
        </div>
        <!--end::Header-->
        <!--begin::Body-->
        <div class="card-body pt-0">
            <!--begin:Current Order-->
            <div class="table-responsive mb-8">
                <table class="table align-middle gs-0 gy-4 my-0">
                    <tbody v-if="Object.keys(selectedItems).length">
                        <tr v-for="item, itemId in selectedItems" :key="itemId">
                            <td class="pb-0">
                                <i v-if="item.sale.sale_type == 1" class="ki-duotone ki-handcart fs-2qx text-success">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                <i v-if="item.sale.sale_type == 2" class="ki-duotone ki-calendar-add fs-2qx text-info">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                </i>
                            </td>
                            <td :class="{ 'pb-0': true, 'reserved-item': reservedVariants.includes(item.value.product_variant_id) }">
                                <Popper style="padding-left: 1em;" placement="bottom-start" offsetDistance="0" :interactive="false">
                                    <span class="order-item-name cursor-pointer fs-6">
                                        {{ item.value.product_name }}
                                    </span>
                                    <template #content>
                                        <div style="max-width: 8rem; padding: 0.3rem;">
                                            <img class="w-100 h-100" style="object-fit: contain;" :src="item.value.first_image" :alt="item.value.product_full_name">
                                        </div>
                                    </template>
                                </Popper>
                                <i class="d-none ki-duotone ki-information-5 fs-3 text-danger ms-3" title="Reservado anteriormente para el evento"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            </td>
                            <td class="pb-0">{{ item.value.barcode }}</td>
                            <td class="fw-bold text-primary fs-3 min-w-80px pb-0">${{ item.sale.subtotal }}</td>
                            <td class="pb-0 text-end">
                                <button @click="delete selectedItems[itemId]" class="btn btn-icon" type="button">
                                    <i class="ki-duotone ki-minus-square fs-1 text-danger hover-scale" title="Retirar de la orden">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </button>
                            </td>
                        </tr>
                    </tbody>

                    <tbody v-else>
                        <tr>
                            <td colspan="100%" class="text-center fs-5">Seleccione algunos productos para agregar a la orden
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="reservationConflict" class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-3 mb-6">
                <!--begin::Icon-->
                <i class="ki-outline ki-information fs-2tx text-warning me-4"></i>
                <!--end::Icon-->
                <!--begin::Wrapper-->
                <div class="d-flex flex-stack flex-grow-1">
                    <!--begin::Content-->
                    <div class="fw-semibold fs-6">
                        <div>Algunos <span class="reserved-item"><span class="order-item-name">vestidos</span></span>
                            seleccionados ya están reservados para el mismo evento.</div>
                        <div class="text-gray-600">Aún así, si lo desea, puede continuar con su orden.</div>
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Wrapper-->
            </div>

            <div class="rounded bg-success p-6">
                <div class="table-responsive text-white">
                    <table class="table table-row-bordered table-row-dashed gy-4 align-middle">
                        <tbody class="fw-bolder fs-5">
                            <tr>
                                <td>Subtotal Ventas</td>
                                <td class="text-end text-nowrap">$ {{ subtotalByType[1] }}</td>
                            </tr>
                            <tr>
                                <td>Subtotal Rentas</td>
                                <td class="text-end text-nowrap">$ {{ subtotalByType[2] }}</td>
                            </tr>
                            <tr>
                                <td>Precio de Envío</td>
                                <td v-if="selectedShipping" class="text-end text-nowrap">$ {{ selectedShipping.shipping_price.price }}</td>
                                <td v-else class="text-end">Sin envío</td>
                            </tr>
                            <tr>
                                <td>Subtotal</td>
                                <td class="text-end">$ {{ subtotalPrice }}</td>
                            </tr>
                            <tr v-if="discountInfo">
                                <td>Descuento</td>
                                <td class="text-end">$ {{ discountInfo.value }}</td>
                            </tr>
                            <tr class="fs-2">
                                <td class="fs-2qx lh-1 pb-0">Precio Total</td>
                                <td class="text-end fs-2qx lh-1 pb-0">$ {{ totalPrice }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div v-if="Object.keys(props.selectedItems ?? {}).length" class="d-flex flex-column">
                        <button class="btn btn-light mt-3 px-2 py-2 hover-elevate-up" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_example_advanced">
                            <i class="ki-duotone ki-discount fs-1"><span class="path1"></span><span class="path2"></span></i>
                            <span v-if="discountInfo == null">Añadir</span>
                            <span v-else>Editar</span>
                            Descuento
                        </button>
                        <button v-if="discountInfo != null" @click="discountInfo = null" class="btn btn-danger mt-3 px-2 py-2 hover-elevate-up">
                            <i class="ki-duotone ki-minus-square fs-1 text-light"><span class="path1"></span><span class="path2"></span></i>
                            Quitar Descuento
                        </button>
                    </div>
                </div>
            </div>

            <!--begin::Actions-->
            <div class="text-end mt-6">
                <!--begin::Submit button-->
                <button @click="submitOrderConfirm()" type="button" class="btn btn-primary fs-1 w-100 py-4 hover-scale" :disabled="!confirmEnabled">
                    Confirmar Orden
                </button>
                <!--end::Submit button-->
            </div>
            <!--begin::Actions-->
            <!--end:Current Order-->
        </div>
    </div>

    <DiscountOffCanvas :subtotalPrice="subtotalPrice" :onApplyDiscount="(d) => discountInfo = d" />
</template>

<style scoped lang="scss">
.order-item-name {
    font-weight: 600 !important;
    color: var(--bs-primary);
    margin-right: 0.25rem !important;
}

.reserved-item {
    >span, >div span {
        border-bottom: 2px solid #F8285A !important;
        color: #F8285A;
    }

    i {
        display: inline-flex !important;
    }
}
</style>
