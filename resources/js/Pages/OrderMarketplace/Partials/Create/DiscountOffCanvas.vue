<script setup>
import { computed, reactive, watch } from 'vue';
import { DiscountTypes } from '@src/utils';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    subtotalPrice: { type: Number, required: true },
    onApplyDiscount: { type: Function, required: true },
});

watch(() => props.subtotalPrice, () => {
    submitApplyDiscount();
});

// ------------------------
// Declarations
// ------------------------

const discount = reactive({
    type: null,
    reason: '',
    amount: 0,
});

const totalPrice = computed(() => {
    return props.subtotalPrice - discountValue.value;
});

const discountValue = computed(() => {
    return Math.round(discount.type == DiscountTypes.PERCENTAGE ? (discount.amount / 100) * props.subtotalPrice : discount.amount);
});

const submitApplyDiscount = () => {
    props.onApplyDiscount(discount.type !== null ? {
        type: discount.type,
        amount: discount.amount,
        reason: discount.reason,
        value: discountValue.value,
    } : null);
};

const isValidDiscount = computed(() => {
    if (discount.reason.length === 0)
        return false;

    switch (discount.type) {
        case DiscountTypes.PERCENTAGE:
            return discount.amount > 0 && discount.amount <= 100;
        case DiscountTypes.AMOUNT:
            return discount.amount > 0 && discount.amount <= props.subtotalPrice;
        default:
            return false;
    }
});
</script>

<template>
    <div id="kt_drawer_example_advanced" class="bg-white drawer drawer-start" data-kt-drawer="true" data-kt-drawer-activate="true" data-kt-drawer-toggle="#kt_drawer_example_advanced_button" data-kt-drawer-close="#kt_drawer_example_advanced_close" data-kt-drawer-name="docs" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="start" style="width: 500px !important;">
        <!--begin::Card-->
        <div class="card rounded-0 w-100">
            <!--begin::Card header-->
            <div class="card-header pe-5">
                <!--begin::Title-->
                <div class="card-title">
                    <!--begin::User-->
                    <div class="d-flex justify-content-center flex-column me-3">
                        <a href="#" class="fs-4 fw-bold text-gray-900 text-hover-primary me-1 lh-1">Aplicar Descuento</a>
                    </div>
                    <!--end::User-->
                </div>
                <!--end::Title-->

                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Close-->
                    <div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_drawer_example_advanced_close">
                        <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body hover-scroll-overlay-y">
                <form @submit.prevent="submitApplyDiscount">
                    <div class="mb-3">
                        <label for="discountType" class="form-label">Tipo de Descuento</label>
                        <select id="discountType" v-model="discount.type" class="form-select" required>
                            <option :value="null" hidden>Seleccione un tipo de descuento</option>
                            <option :value="DiscountTypes.PERCENTAGE">Porcentaje</option>
                            <option :value="DiscountTypes.AMOUNT">Cantidad</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="discountValue" class="form-label">Valor del Descuento</label>
                        <input id="discountValue" type="number" v-model.number="discount.amount" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="discountValue" class="form-label">Motivo del Descuento</label>
                        <textarea id="discountValue" v-model="discount.reason" class="form-control" required></textarea>
                    </div>
                    <div>
                        <p><strong>Precio Original:</strong> ${{ subtotalPrice }}</p>
                        <p><strong>Descuento:</strong> {{ discount.type == DiscountTypes.PERCENTAGE ? discount.amount + '%' : '$' + discount.amount }}</p>
                        <p><strong>Total con Descuento:</strong> ${{ totalPrice }}</p>
                    </div>
                    <button class="btn btn-success mt-3" :disabled="!isValidDiscount" type="submit">Aplicar Descuento</button>
                </form>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
</template>
