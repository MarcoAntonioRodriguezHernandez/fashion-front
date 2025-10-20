<script setup>
import { router, useForm } from '@inertiajs/vue3';
import { computed, ref, onMounted } from 'vue';
import { initSelectElements, updateSelectElements } from '@src/utils.js';
import ShowAmounts from '@/Pages/OrderMarketplace/Partials/Show/ShowAmounts.vue';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    order: { type: Object, required: true },
    discounts: { type: Array, required: true },
    payments: { type: Array, required: true },
    discountAppliesTo: { type: Object, required: true },
});

const discountCreationForm = useForm({
    order_marketplace_id: props.order.id,
    reason: '',
    applies_to: 0,
    amount: null,
});

onMounted(() => {
    if (showDiscountRoot.value) {
        initSelectElements(showDiscountRoot, discountCreationForm);
    }
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


// ------------------------
// Declarations
// ------------------------

const showDiscountRoot = ref(null);

const submitDiscountCreation = () => {
    console.log(discountCreationForm)
    discountCreationForm.post(url('discounts'), {
        onSuccess: () => {
            discountCreationForm.reset();
            updateSelectElements(showDiscountRoot, discountCreationForm);
        },
    });
}

const submitDiscountDeletion = (discount) => {
    router.delete(url('discounts/' + discount.id));
}

const orderPending = computed(() => {
    return props.order.amount_total - props.order.total_payment;
});
</script>

<template>
    <div class="card card-flush">
        <div class="card-body">
            <div ref="showDiscountRoot" class="row">
                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-md-6 mb-xl-10 mb-4">
                    <h1>Descuentos</h1>
                    <div v-if="discounts.length === 0">
                        <div class="col-12 d-flex flex-column align-items-center my-12">
                            <div class="fs-2 fw-bold">No hay descuentos registrados</div>
                        </div>
                    </div>
                    <div v-else class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
                            <!--begin::Table head-->
                            <thead>
                                <tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
                                    <th class="p-0 pb-3 min-w-100px text-start">CANTIDAD</th>
                                    <th class="p-0 pb-3 min-w-100px text-start">MOTIVO</th>
                                    <th class="p-0 pb-3 min-w-100px text-start">TIPO</th>
                                    <th class="p-0 pb-3 min-w-100px text-end">ELIMINAR</th>
                                </tr>
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody>
                                <tr v-for="discount in discounts">
                                    <td class="text-start pe-0">
                                        <span class="text-gray-600 fw-bold fs-6">
                                            $ {{ discount.amount.toFixed(2) }}
                                        </span>
                                    </td>
                                    <td class="text-start pe-0">
                                        <span class="text-gray-600 fw-bold fs-6">
                                            {{ discount.reason }}
                                        </span>
                                    </td>
                                    <td class="text-start pe-0">
                                        <span class="text-gray-600 fw-bold fs-6">
                                            {{ discount.applies_to_name }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-0" style="width: 0;">
                                        <a class="menu-link px-2" @click="submitDiscountDeletion(discount)" style="cursor: pointer;">
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M15.375 4.5H2.625M14.1248 6.375L13.7798 11.55C13.647 13.5405 13.581 14.5358 12.9323 15.1425C12.2835 15.75 11.2853 15.75 9.29025 15.75H8.70975C6.71475 15.75 5.7165 15.75 5.06775 15.1425C4.419 14.5358 4.35225 13.5405 4.22025 11.55L3.87525 6.375M7.125 8.25L7.5 12M10.875 8.25L10.5 12" stroke="#ED1010" stroke-width="1.5" stroke-linecap="round" />
                                                <path d="M4.875 4.5H4.9575C5.25933 4.49229 5.55182 4.39367 5.79669 4.21703C6.04157 4.0404 6.22744 3.79398 6.33 3.51L6.3555 3.43275L6.42825 3.2145C6.4905 3.02775 6.522 2.93475 6.56325 2.85525C6.64441 2.69954 6.76088 2.56499 6.90336 2.46237C7.04583 2.35974 7.21035 2.2919 7.38375 2.26425C7.4715 2.25 7.56975 2.25 7.76625 2.25H10.2338C10.4303 2.25 10.5285 2.25 10.6162 2.26425C10.7896 2.2919 10.9542 2.35974 11.0966 2.46237C11.2391 2.56499 11.3556 2.69954 11.4367 2.85525C11.478 2.93475 11.5095 3.02775 11.5717 3.2145L11.6445 3.43275C11.7395 3.7487 11.9361 4.02451 12.2038 4.21745C12.4714 4.41039 12.7952 4.5097 13.125 4.5" stroke="#ED1010" stroke-width="1.5" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                            <!--end::Table body-->
                        </table>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-md-6 mb-xl-10">
                    <div class="card card-custom card-stretch gutter-b">
                        <form @submit.prevent="submitDiscountCreation">
                            <div class="card-header align-items-center border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder text-dark">Agregar Descuento</span>
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
                                <div class="form-group row mb-6" v-if="surchargePendingAmount > 0">
                                    <label class="col-form-label col-3 text-lg-end text-left">Tipo de descuento</label>
                                    <div class="col-9">
                                        <select v-model="discountCreationForm.applies_to" name="applies_to"
                                            class="form-select form-control" data-control="select2"
                                            data-placeholder="Elige un tipo de descuento" value="default"
                                            data-hide-search="true">
                                            <option id="default" :value="0">Tipo de descuento</option>
                                            <option v-for="name, value in discountAppliesTo" :value="value">
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
                                            <input v-if="discountCreationForm.applies_to == 0"
                                                v-model="discountCreationForm.amount" type="number"
                                                :max="normalPendingAmount" class="form-control form-control-lg"
                                                placeholder="Monto" />
                                            <input v-if="discountCreationForm.applies_to == 1"
                                                v-model="discountCreationForm.amount" type="number"
                                                :max="surchargePendingAmount" class="form-control form-control-lg"
                                                placeholder="Monto" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-6">
                                    <label class="col-form-label col-3 text-lg-end text-left">Motivo</label>
                                    <div class="col-9">
                                        <textarea v-model="discountCreationForm.reason" class="form-control form-control-lg" placeholder="Motivo"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer border-0 pt-0">
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-success">Guardar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <ShowAmounts :totalAmount="order.amount_total" :totalPayment="order.total_payment" :totalPending="orderPending" />
            </div>
        </div>
    </div>
</template>
