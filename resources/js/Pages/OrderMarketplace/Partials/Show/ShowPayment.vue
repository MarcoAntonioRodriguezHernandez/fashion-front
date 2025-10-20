<script setup>
import { router, useForm } from '@inertiajs/vue3';
import { computed, ref, onMounted, watch, defineExpose } from 'vue';
import Popper from "vue3-popper";
import { initSelectElements, updateSelectElements } from '@src/utils.js';
import ShowAmounts from '@/Pages/OrderMarketplace/Partials/Show/ShowAmounts.vue';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    order: { type: Object, required: true },
    discounts: { type: Array, required: true },
    payments: { type: Array, required: true },
    paymentTypes: { type: Array, required: true },
    paymentStatuses: { type: Object, required: true },
    paymentReasons: { type: Object, required: true },
});

const paymentCreationForm = useForm({
    order_marketplace_id: null,
    total: null,
    payment: null,
    payment_type_id: null,
    reason: 0, // 0: Normal, 1: Surcharge, 2: Waiver of Surcharges 
    status: 2,
    note_reason: '',
});

onMounted(() => {
    if (showPaymentRoot.value) {
        initSelectElements(showPaymentRoot, paymentCreationForm);
    }
});

// ------------------------
// Declarations
// ------------------------

const showPaymentRoot = ref(null);

const submitPaymentCreation = () => {
    const paymentBtn = document.getElementById('payment-btn');
    if (paymentBtn) paymentBtn.disabled = true;
    paymentCreationForm.transform((data) => ({
        ...data,
        order_marketplace_id: props.order.id,
        total: data.payment,
    })).post(url('payment/order/marketplace'), {
        onSuccess: () => {
            paymentCreationForm.reset();
            updateSelectElements(showPaymentRoot, paymentCreationForm);
            window.dispatchEvent(new Event('payment:created'));
        },
        onFinish: () => {
            if (paymentBtn) paymentBtn.disabled = false;
        },
    });
}

const submitPaymentUpdate = (paymentId, status) => {
    router.put(url('payment/order/marketplace'), {
        id: paymentId,
        status }, {
        onSuccess: () => window.dispatchEvent(new Event('payment:created')),
    });
};

const orderPending = computed(() => {
    return props.order.amount_total - props.order.total_payment;
});

const normalDiscountAmount = computed(() => {
    return props.discounts.filter(x => (x.applies_to == 0)).reduce((sum, x) => sum + x.amount, 0);
});

const surchargeDiscountAmount = computed(() => {
    return props.discounts.filter(x => (x.applies_to == 1)).reduce((sum, x) => sum + x.amount, 0);
});

const surchargePaidAmount = computed(() => {
    return props.payments.filter(x => (x.reason == 1 && x.status == 2)).reduce((sum, x) => sum + x.total, 0);
})

const normalPaidAmount = computed(() => {
    return props.payments.filter(x => (x.reason == 0 && x.status == 2)).reduce((sum, x) => sum + x.total, 0);
})

const normalPendingAmount = computed(() => {
    return Number(props.order.amount) - normalPaidAmount.value - normalDiscountAmount.value;
});

const surchargePendingAmount = computed(() => {
    return Number(props.order.surcharge) - surchargePaidAmount.value - surchargeDiscountAmount.value;
});

watch(() => paymentCreationForm.reason, (newVal) => {
    const amount = newVal == 1
        ? surchargePendingAmount.value
        : normalPendingAmount.value;
    paymentCreationForm.payment = amount.toString();
}, { immediate: true });

const paymentInputRef = ref(null);

defineExpose({ paymentInputRef });

</script>

<template>
    <div class="card card-flush">
        <div class="card-body">
            <div ref="showPaymentRoot" class="row">
                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-md-6 mb-xl-10 mb-4">
                    <h1>Pagos</h1>
                    <div v-if="payments.length === 0">
                        <div class="col-12 d-flex flex-column align-items-center my-12">
                            <div class="fs-2 fw-bold">No hay pagos registrados</div>
                        </div>
                    </div>
                    <div v-else class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
                            <!--begin::Table head-->
                            <thead>
                                <tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
                                    <th class="p-0 pb-3 min-w-100px text-start">CANTIDAD</th>
                                    <th class="p-0 pb-3 min-w-100px text-start">FECHA</th>
                                    <th class="p-0 pb-3 min-w-100px text-start">MÉTODO DE PAGO</th>
                                    <th class="p-0 pb-3 min-w-100px text-start">TIPO DE PAGO</th>
                                    <th class="p-0 pb-3 min-w-100px text-start">ESTADO</th>
                                </tr>
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody>
                                <tr v-for="payment in payments">
                                    <td class="text-start pe-0">
                                        <span class="text-gray-600 fw-bold fs-6">
                                            $ {{ payment.payment }}
                                            <span v-if="payment.to_credit > 0">
                                                ($ {{ payment.to_credit }} a crédito)
                                            </span>
                                        </span>
                                    </td>
                                    <td class="text-start pe-0">
                                        <span class="text-gray-600 fw-bold fs-6">
                                            {{ payment.date }}
                                        </span>
                                    </td>
                                    <td class="text-start pe-0">
                                        <span class="text-gray-600 fw-bold fs-6">
                                            {{ payment.payment_type }}
                                        </span>
                                    </td>
                                    <td class="text-start pe-0">
                                        <span class="text-gray-600 fw-bold fs-6">
                                            {{ payment.reason_name }}
                                        </span>
                                    </td>
                                    <td class="text-">
                                        <Popper placement="left" :interactive="false">
                                            <div class="btn-group dropstart"
                                                :title="payment.payment > orderPending ? 'Este pago excede el valor pendiente de la orden' : ''">
                                                <button class="col-12 btn btn-warning dropdown-toggle py-2 px-5">
                                                    {{ payment.status_name }}
                                                </button>
                                            </div>

                                            <template #content>
                                                <div style="padding: 1rem 0.7rem;">
                                                    <ul class="list-group list-group-flush">
                                                        <button style="background-color: transparent;"
                                                            v-for="name, value in paymentStatuses"
                                                            :disabled="payment.payment > orderPending && value == 2 /* APPROVED */"
                                                            @click="submitPaymentUpdate(payment.id, value)"
                                                            class="list-group-item py-1 fs-small popper-bg">
                                                            {{ name }}
                                                        </button>
                                                    </ul>
                                                </div>
                                            </template>
                                        </Popper>
                                    </td>
                                </tr>
                            </tbody>
                            <!--end::Table body-->
                        </table>
                    </div>
                </div>

                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-md-6 mb-xl-10">
                    <div class="card card-custom card-stretch gutter-b">
                        <form @submit.prevent="submitPaymentCreation">
                            <div class="card-header align-items-center border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder text-dark">Agregar Pago</span>
                                    <span class="text-muted mt-2 fw-bold fs-7">Ingrese el monto a cubrir</span>
                                </h3>
                                <div class="d-flex flex-column align-items-end justify-content-end">
                                    <div>
                                        <span class="text-primary fw-bold fs-6 mx-2">Saldo Pendiente: </span>
                                        <span class="text-danger fw-bold fs-3">$ {{ orderPending.toFixed(2) }}</span>
                                    </div>
                                    <div v-if="order.surcharge > 0">
                                        <span class="text-muted fw-bold fs-7">Pago pendiente: $ {{
                                            (normalPendingAmount).toFixed(2)
                                        }}</span>
                                    </div>
                                    <div v-if="order.surcharge > 0">
                                        <span class="text-muted fw-bold fs-7">Recargo pendiente: $ {{
                                            (surchargePendingAmount).toFixed(2) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-3 pb-0">
                                <!-- Payment reason -->
                                <div class="form-group row mb-6" v-if="surchargePendingAmount > 0">
                                    <label class="col-form-label col-3 text-lg-end text-left">Tipo de Pago</label>
                                    <div class="col-9">
                                        <select v-model="paymentCreationForm.reason" name="reason"
                                            class="form-select form-control" data-control="select2"
                                            data-placeholder="Elige un tipo de pago" value="default"
                                            data-hide-search="true">
                                            <option id="default" :value="0">Elige un tipo de pago</option>
                                            <option v-for="name, value in paymentReasons" :value="value">
                                                {{ name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-6">
                                    <label class="col-form-label col-3 text-lg-end text-left">Monto</label>
                                    <div class="col-9">
                                        <div class="input-group">
                                            <span class="input-group-text disabled">$</span>
                                            <input v-if="paymentCreationForm.reason == 0"
                                                v-model="paymentCreationForm.payment" type="number"
                                                :max="normalPendingAmount" class="form-control form-control-lg"
                                                placeholder="Monto" ref="paymentInputRef"/>
                                            <input v-if="paymentCreationForm.reason == 1"
                                                v-model="paymentCreationForm.payment" type="number"
                                                :max="surchargePendingAmount" class="form-control form-control-lg"
                                                placeholder="Monto" ref="paymentInputRef"/>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment method -->
                                <div class="form-group row mb-6">
                                    <label class="col-form-label col-3 text-lg-end text-left">Método de Pago</label>
                                    <div class="col-9">
                                        <select v-model="paymentCreationForm.payment_type_id" name="payment_type_id"
                                            class="form-select form-control" data-control="select2"
                                            data-placeholder="Elige un método de pago" data-hide-search="true">
                                            <option value="">Elige un método de pago</option>
                                            <option v-for="paymentType in paymentTypes" :value="paymentType.id">
                                                {{ paymentType.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row mb-6">
                                    <label class="col-form-label col-3 text-lg-end text-left">Estado del Pago</label>
                                    <div class="col-9">
                                        <select v-model="paymentCreationForm.status" name="status"
                                            class="form-select form-control" data-control="select2"
                                            data-placeholder="Elige un estado del pago" data-hide-search="true">
                                            <option value="">Elige un estado del pago</option>
                                            <option v-for="name, value in paymentStatuses" :value="value">
                                                {{ name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer border-0 pt-0">
                                <div class="d-flex justify-content-end">
                                    <button type="submit" id="payment-btn" class="btn btn-success">Pagar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <ShowAmounts :totalAmount="order.amount_total" :totalPayment="order.total_payment"
                    :totalPending="orderPending" />
            </div>
        </div>
    </div>
</template>
