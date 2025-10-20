<script setup>
import { ref, onMounted, onUnmounted, nextTick, computed } from 'vue';
import Master from '@layouts/Master.vue';
import WithPermission from '@components/WithPermission.vue';
import ShowGeneralData from '@/Pages/OrderMarketplace/Partials/Show/ShowGeneralData.vue';
import ShowPayment from '@/Pages/OrderMarketplace/Partials/Show/ShowPayment.vue';
import ShowDiscount from '@/Pages/OrderMarketplace/Partials/Show/ShowDiscount.vue';
import ShowItems from '@/Pages/OrderMarketplace/Partials/Show/ShowItems.vue';
import ItemEditionModal from '@/Pages/OrderMarketplace/Partials/Edit/ItemEditionModal.vue';
import { fullAlert } from '@src/utils';
import { router } from '@inertiajs/vue3';
import ShowDocs from './Partials/Show/ShowDocs.vue';
import { PermissionTypes, ModuleAliases } from '@src/Auth.js';
import ShowPaymentModal from '@/Pages/OrderMarketplace/Partials/Show/ShowPaymentModal.vue';
// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    order: { type: Object, required: true },
    employee: { type: Object, required: true },
    client: { type: Object, required: true },
    payments: { type: Array, required: true },
    discounts: { type: Array, required: true },
    items: { type: Array, required: true },
    shipping: { type: Object, default: null },
    paymentTypes: { type: Array, required: true },
    paymentStatuses: { type: Object, required: true },
    orderStatuses: { type: Object, required: true },
    paymentReasons: { type: Object, required: true },
    discountAppliesTo: { type: Object, required: true },
});

// ------------------------
// Declarations
// ------------------------

const itemEditionModal = ref(null);
const showPaymentModal = ref(false);    
const paymentInputRefComponent = ref(null);

const hasPayment = computed(() => props.payments?.some(p => p.payment > 0) ?? false);

const getActiveTabSelector = () => {
    const current = document.querySelector('.nav-link.active[data-bs-toggle="tab"]');
    return current ? current.getAttribute('href') : '#kt_tab_pane_1';
};

const normalizeTabs = () => {
    document.querySelectorAll('.nav-link[data-bs-toggle="tab"]').forEach(a => a.classList.remove('active'));
    document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.classList.remove('active');
        pane.classList.remove('show');
    });
};

const onPaymentCreated = () => {
    const currentTab = getActiveTabSelector();

    router.reload({
        only: ['order', 'payments', 'discounts'],
        preserveScroll: true,
        onSuccess: () => {
            normalizeTabs();

            const link = document.querySelector(`a.nav-link[href="${currentTab}"]`);
            if (link) {
                const tab = bootstrap.Tab.getOrCreateInstance(link);
                tab.show();
            }
        },
    });
};



const cancelOrder = () => {
    const maxAmount = props.order.advance_payment ?? 0;
    const confirmationText = props.order.is_active
        ? '¿Estás seguro de que deseas cancelar esta orden?'
        : '¿Estás seguro de que deseas devolver esta orden?';

    fullAlert({
        title: props.order.is_active ? 'Cancelar Orden' : 'Devolver Orden',
        html: `
            <div id="creditAmountWrapper">
                <label for="creditAmount" class="form-label">Monto a acreditar:</label>
                <div class="input-group">
                    <span class="input-group-text disabled">$</span>
                    <input type="number" class="form-control" id="creditAmount" value="${maxAmount}" min="0" max="${maxAmount}">
                </div>
            </div>
            <p class="mt-5" style="margin-bottom: 0;">${confirmationText}</p>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar',
        allowOutsideClick: false,
        preConfirm: () => {
            const creditAmount = parseFloat(document.getElementById('creditAmount').value);

            if (isNaN(creditAmount) || creditAmount < 0) {
                Swal.showValidationMessage('El monto a acreditar no es válido');
                return false;
            }

            if (creditAmount > maxAmount) {
                Swal.showValidationMessage(`El monto no puede ser mayor al pagado $${maxAmount.toFixed(2)}`);
                return false;
            }

            return { creditAmount };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            router.put(`/order/marketplace/${props.order.id}/status`, {
                orderStatus: props.order.is_active ? 8 : 9,
                credit_amount: result.value.creditAmount,
            });
        }
    });
}

onMounted(() => {
    if (!hasPayment.value) {
        showPaymentModal.value = true;

        const paymentsTab = document.querySelector('a[href="#kt_tab_pane_3"]');
        if (paymentsTab) {
            const tab = new bootstrap.Tab(paymentsTab);
            tab.show();
        }
    }
    window.addEventListener('payment:created', onPaymentCreated);
});

onUnmounted(() => {
    window.removeEventListener('payment:created', onPaymentCreated);
});
console.log('hasPayment:', hasPayment);

const onModalClose = () => {
    showPaymentModal.value = false;
    nextTick(() => {
        if (paymentInputRefComponent.value?.paymentInputRef) {
            paymentInputRefComponent.value.paymentInputRef.focus();
        }
    });
};
</script>

<template>
    <Master :title="'Orden #' + order.code" :cardTitle="'Orden #' + order.code">
        <div ref="root" class="row">
            <div class="col-12">
                <ul class="nav nav-pills text-nowrap mb-5 fs-6 mt-5" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" :class="{ disabled: !hasPayment}" data-bs-toggle="tab" href="#kt_tab_pane_1">Datos Generales</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{ disabled: !hasPayment}" data-bs-toggle="tab" href="#kt_tab_pane_2">Articulos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_3">Pagos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{ disabled: !hasPayment}" data-bs-toggle="tab" href="#kt_tab_pane_4">Descuentos</a>
                    </li>
                    <li v-if="order.identity_document != null" class="nav-item">
                        <a class="nav-link" :class="{ disabled: !hasPayment}" data-bs-toggle="tab" href="#kt_tab_pane_5">Documentación</a>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
                        <ShowGeneralData :orderStatuses="orderStatuses" :order="order" :employee="employee"
                            :client="client" :shipping="shipping" />
                    </div>
                    <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
                        <ShowItems :items="items" :onItemEdit="(...params) => itemEditionModal.showWith(...params)" />
                    </div>
                    <div class="tab-pane fade" id="kt_tab_pane_3" role="tabpanel">
                        <ShowPayment ref="paymentInputRefComponent" :order="order" :payments="payments" :paymentTypes="paymentTypes"
                            :paymentStatuses="paymentStatuses" :paymentReasons="paymentReasons" :discounts="discounts"/>
                    </div>
                    <div class="tab-pane fade" id="kt_tab_pane_4" role="tabpanel">
                        <ShowDiscount :order="order" :discounts="discounts" :payments="payments" :discountAppliesTo="discountAppliesTo" />
                    </div>
                    <div class="tab-pane fade" id="kt_tab_pane_5" role="tabpanel">
                        <ShowDocs v-if="order.identity_document != null" :order="order" :client="client" />
                    </div>
                </div>

                <div class="d-flex flex-wrap justify-content-end mt-4">
                    <WithPermission :module="ModuleAliases.SUPER_ORDER" :permissions="[PermissionTypes.UPDATE]">
                        <button v-if="order.is_enabled" class="btn btn-danger mx-2 mb-2" @click="cancelOrder">
                            {{ order.is_active ? 'Cancelar' : 'Devolver' }}
                            Orden
                        </button>
                    </WithPermission>

                    <a class="btn btn-primary mb-2" :href="url('/order/marketplace')">Regresar</a>
                </div>
            </div>
        </div>
    </Master>

    <ItemEditionModal ref="itemEditionModal" :order="order" />
    <ShowPaymentModal v-model:show="showPaymentModal" @close="onModalClose" />
</template>
