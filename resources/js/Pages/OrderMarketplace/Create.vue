<script setup>
import { ref, reactive, computed, onMounted, toRaw, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import Master from '@layouts/Master.vue';
import WithPermission from '@components/WithPermission.vue';
import ItemSelectionModal from '@/Pages/OrderMarketplace/Partials/Create/ItemSelectionModal.vue';
import ClientSelection from '@/Pages/OrderMarketplace/Partials/Create/ClientSelection.vue';
import EmployeeSelection from '@/Pages/OrderMarketplace/Partials/Create/EmployeeSelection.vue';
import ShippingSelection from '@/Pages/OrderMarketplace/Partials/Create/ShippingSelection.vue';
import EventSelectionModal from '@/Pages/OrderMarketplace/Partials/Create/EventSelectionModal.vue';
import CreationSummary from '@/Pages/OrderMarketplace/Partials/Create/CreationSummary.vue';
import ItemSearch from '@/Pages/OrderMarketplace/Partials/Create/ItemSearch.vue';
import OrderSignature from '@/Pages/OrderMarketplace/Partials/Create/OrderSignature.vue';
import { PermissionTypes, ModuleAliases } from '@src/Auth.js';
import { initSelectElements, getCurrentDateTime } from '@src/utils.js';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    itemResults: { type: Object, default: () => null },
    clientResults: { type: Array, default: () => null },
    clientInfo: { type: Object, default: () => null },
    eventResults: { type: Array, default: () => null },
    categories: { type: Object, required: true },
    designers: { type: Object, required: true },
    colors: { type: Object, required: true },
    characteristics: { type: Object, required: true },
    foundByMethods: { type: Object, required: true },
    genders: { type: Object, required: true },
    eventTypes: { type: Object, required: true },
    saleTypes: { type: Object, required: true },
    shippingPrices: { type: Object, required: true },
});

const generalInfoForm = useForm({
    found_by: 0,
    created_at: getCurrentDateTime(),
});

onMounted(() => {
    if (generalInfoRoot.value) {
        initSelectElements(generalInfoRoot, generalInfoForm);
    }
});

// ------------------------
// Declarations
// ------------------------

// HTML element references
const generalInfoRoot = ref(null);
// Component element references
const itemSelectionModal = ref(null);
const eventSelectionModal = ref(null);
// Component data
const selectedItems = reactive({});
const selectedShipping = ref(null);
const selectedEvent = ref(null);
const selectedDiscount = ref(null);
const selectedEmployee = ref(null);
const signatureData = ref(null);
const identityDocuments = ref(null);
const showCheckout = ref(false);
const hasRents = computed(() => Object.values(selectedItems).some(item => item.sale.sale_type == 2));

const onOrderConfirm = (discountInfo = null) => {
    selectedDiscount.value = discountInfo;

    if (showCheckout.value)
        submitOrderCreation();
    else
        showCheckout.value = true;
}

const submitOrderCreation = () => {
    let itemList = Object.values(selectedItems).map(item => {
        let sale = {};

        for (const key in item.sale)
            if (item.sale[key] !== undefined)
                sale[key] = item.sale[key];

        return sale;
    });
    let shippingData = selectedShipping.value ? {
        shipping_price_id: selectedShipping.value.shipping_price.id,
        user_address_id: selectedShipping.value.id,
    } : undefined;

    let eventData = toRaw(selectedEvent.value);
    delete eventData?.label;
    delete eventData?.variants;

    generalInfoForm.transform((orderData) => ({
        employee_id: selectedEmployee.value?.id,
        client_id: props.clientInfo.id,
        ...orderData,
        ...eventData,
        create_at_date: new Date(new Date(orderData.created_at).setHours(0, 0, 0, 0)),
        discount: selectedDiscount.value ? {
            value: selectedDiscount.value?.value,
            reason: selectedDiscount.value?.reason,
        } : undefined,
        items: itemList,
        shipping: shippingData,
        has_rents: hasRents.value,
        identity_document_front: identityDocuments.value?.frontal,
        identity_document_back: identityDocuments.value?.back,
        contract_signature: signatureData.value ?? undefined,
    })).post(url('order/marketplace/full'));
};

const onItemSelectionConfirm = (selectionInfo, selectedItem) => {
    selectedItems[selectedItem.id] = {
        sale: selectionInfo,
        value: selectedItem,
    };
}

const onClientSelectionConfirm = (selectedClient) => {
    router.reload({
        data: {
            client_id: selectedClient?.id,
        },
        only: ['clientInfo'],
        method: 'post',
        onSuccess: () => {
            selectedShipping.value = null;
        }
    });
}

const onEmployeeSelectionConfirm = (newEmployee) => {
    selectedEmployee.value = newEmployee;
}

const onShippingSelectionConfirm = (newShipping) => {
    selectedShipping.value = newShipping;
}

const onEventSelectionConfirm = (newEvent) => {
    selectedEvent.value = newEvent;
}

const creationEnabled = computed(() => {
    return props.clientInfo != null &&
        selectedEvent != null &&
        checkOutEnabled.value &&
        (!hasRents.value || (identityDocuments.value != null && signatureData.value != null));
});

const checkOutEnabled = computed(() => {
    return Object.keys(selectedItems).length > 0
});

const isMissedOrder = computed(() => {
    return new Date((new Date(generalInfoForm.created_at)).toDateString()) < new Date(new Date().toDateString());
});

watch(selectedItems, (newItems) => {
    if (Object.keys(newItems).length == 0)
        showCheckout.value = false;
});
</script>

<template>
    <Master title="Crear Nueva Orden" cardTitle="Crear Nueva Orden">
        <div class="levels-container">
            <!--begin::Pos system-->
            <!--begin::Item Search-->
            <ItemSearch class="primary-1" v-if="!showCheckout" :itemResults="itemResults" :selectedItems="selectedItems" :onItemSelected="(...params) => itemSelectionModal.showWith(...params)" :categories="categories" :designers="designers" :colors="colors" :characteristics="characteristics" />
            <!--end::Item Search-->

            <!--start::Order Signature-->
            <OrderSignature class="primary-1" v-if="showCheckout && hasRents" :client-name="clientInfo?.full_name" :onSignatureConfirm="(v) => signatureData = v" :onIdentityDocumentsConfirm="(v) => identityDocuments = v" />
            <!--end::Order Signature-->
            <!--end::Pos system-->

            <!--begin::Sidebar-->
            <div v-if="showCheckout" class="secondary-1">
                <ClientSelection :genders="genders" :clientInfo="clientInfo" :clientResults="clientResults" :onConfirm="onClientSelectionConfirm" />

                <ShippingSelection v-if="clientInfo != null" :onConfirm="onShippingSelectionConfirm" :client="clientInfo" :shippingPrices="shippingPrices" :selectedShipping="selectedShipping" />

                <!-- begin::Event Selection -->
                <div class="card card-flush mt-5 mb-5">
                    <div class="card-header border-0 pt-6">
                        <!--begin::Title-->
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1 ">Evento</span>
                            <span class="text-muted fw-semibold fs-5">Información del evento, puede
                                modificarlo</span>
                        </h3>
                        <!--end::Title-->
                        <div class="card-toolbar">
                            <i class="ki-duotone ki-user-tick fs-2hx text-dark">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </div>
                    </div>
                    <div class="mx-9 align-items-start flex-column mb-5">
                        <div v-if="selectedEvent" class="col-md-12">
                            <!--begin::Inbox-->
                            <div class="menu-item mb-3">
                                <!--begin::Label-->
                                <label class="required form-label">Evento Seleccionado:</label>
                                <!--end::Label-->
                                <button @click="eventSelectionModal.show()" class="form-control d-flex justify-content-between">
                                    <span v-if="selectedEvent != null" class="text-truncate">
                                        {{ selectedEvent.label }}
                                    </span>
                                    <span v-else>
                                        Sin evento
                                    </span>
                                    <i class="ki-duotone ki-search-list fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end::Event Selection -->
            </div>
            <!--end::Sidebar-->

            <div class="secondary-2">
                <WithPermission :module="ModuleAliases.SUPER_ORDER" :permissions="[PermissionTypes.CREATE]">
                    <!--begin::Employee Selection-->
                    <EmployeeSelection :storeId="Object.values(selectedItems)[0]?.value.store_id" :onConfirm="onEmployeeSelectionConfirm" />
                    <!--end::Employee Selection-->

                    <!--begin::Date Selection-->
                    <div class="card card-flush bg-body mb-4">
                        <!--begin::Header-->
                        <div class="card-header pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold fs-3 mb-1">Fecha de Solicitud</span>
                                <span class="text-muted fw-semibold fs-5">Fecha en que se solicitó la orden.</span>
                            </h3>
                        </div>
                        <!--end::Header-->

                        <!--begin::Body-->
                        <div ref="selectionRoot" class="card-body pt-0">
                            <div class="col">
                                <input v-model="generalInfoForm.created_at" type="datetime-local" class="form-control" :max="getCurrentDateTime()">

                                <div v-if="isMissedOrder" class="text-danger fw-semibold fs-6 mt-3 mx-2">La orden será creada como pagada por método Liverpool.</div>
                            </div>
                        </div>
                    </div>
                    <!--end::Date Selection-->
                </WithPermission>

                <!--begin::Creation Summary-->
                <CreationSummary :selectedItems="selectedItems" :selectedShipping="selectedShipping" :onConfirm="onOrderConfirm" :saleTypes="saleTypes" :confirmEnabled="showCheckout ? creationEnabled : checkOutEnabled" :reservedVariants="selectedEvent?.variants" />
                <!--end::Creation Summary-->
            </div>
        </div>
    </Master>

    <ItemSelectionModal ref="itemSelectionModal" :onItemsConfirm="onItemSelectionConfirm" :onEventConfirm="selectedEvent ? null : onEventSelectionConfirm" :eventTypes="eventTypes" :eventResults="eventResults" :minDate="generalInfoForm.created_at" />
    <EventSelectionModal ref="eventSelectionModal" :onConfirm="onEventSelectionConfirm" :eventTypes="eventTypes" :eventResults="eventResults" />
</template>
