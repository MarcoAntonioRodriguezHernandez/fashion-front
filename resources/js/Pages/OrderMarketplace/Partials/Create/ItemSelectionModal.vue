<script setup>
import { ref, reactive, computed, onMounted, toRaw, watch } from 'vue';
import { simpleAlert, currentLocalDate } from '@src/utils';
import DatePicker from '@components/DatePicker.vue';
import Wizard from '@components/Wizard.vue';
import EventSelection from '@/Pages/OrderMarketplace/Partials/Create/EventSelection.vue';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    onItemsConfirm: { type: Function, required: true },
    onEventConfirm: { type: Function, default: null },
    eventTypes: { type: Object, required: true },
    eventResults: { type: Array, default: () => null },
    minDate: { type: String, default: null },
});

const itemSelectionForm = reactive({
    fitting_price: null,
    fitting_notes: null,
    additional_notes: null,
    sale_type: null, // 1: Sale, 2: Rent
    // For rents only
    insurance_price: null,
    dateRange: {
        startDate: null,
        endDate: null,
    },
});

const context = reactive({
    rent_duration: 4,
});

onMounted(() => {
    const modalElement = document.getElementById('modal-item-selection');
    modal = new bootstrap.Modal(modalElement);

    modalElement.addEventListener('hide.bs.modal', () => {
        resetState();
    });
});

watch(() => context.rent_duration, () => {
    if (datePicker.value != null)
        datePicker.value.clearSelection();
});

defineExpose({
    showWith: (itemInfo) => {
        selectedItem.value = itemInfo;

        modal.show();
    },
});

// ------------------------
// Declarations
// ------------------------

let modal = null;
const datePicker = ref(null);
const eventSelection = ref(null);
const selectedItem = ref(null);
const eventRequired = computed(() => itemSelectionForm.sale_type == 2 && props.onEventConfirm != null);
const reservedDatesLabel = computed(() => {
    if (selectedItem.value.reserved_dates.length == 0) return null;

    const normalizeDate = (date) => date.replaceAll('-', '/').split('T')[0];

    return selectedItem.value.reserved_dates.map((range) => normalizeDate(range.startDate) + ' a ' + normalizeDate(range.endDate)).join(', ');
});
const isTransfer = computed(() => selectedItem.value?.status === 5);
const transferMessage = computed(() => {
    return isTransfer.value ? 'Este producto no se puede vender ni rentar porque está en Distribución' : null;
});
const isBarlyDesigner = computed(() => selectedItem.value?.designer_name?.toLowerCase() === 'bärly');
const wizardStepsInfo = [
    {
        name: 'type-selection',
        title: 'Seleccion Tipo de Venta',
        description: 'Registrar venta o renta',
        actions: false,
    },
    {
        name: 'item-info',
        title: 'Información del Artículo',
        description: 'Datos del artículo',
        enabled: () => selectedItem.value != null,
    },
    {
        name: 'sale-details',
        title: 'Información del Pedido',
        description: 'Datos del pedido',
        showButtons: true,
        enabled: () => {
            switch (itemSelectionForm.sale_type) {
                case 1: // Sale
                    return true;
                case 2: // Rent
                    return itemSelectionForm.dateRange.startDate && itemSelectionForm.dateRange.endDate;
            }
        },
        actions: {
            next: (stepper) => eventRequired.value ? stepper.nextStep() : confirmItemSelection(),
        },
    },
    {
        name: 'event-selection',
        title: 'Seleccionar Evento',
        description: 'Seleccione el evento al que asistirá',
        onShow: () => {
            eventSelection.value.initSelectElements();
        },
        showStep: () => eventRequired.value,
        actions: {
            next: false,
        },
    },
];

const confirmItemSelection = () => {
    let rentDetail = itemSelectionForm.sale_type == 2 ? { // Rent
        insurance_price: itemSelectionForm.insurance_price,
        date_start: itemSelectionForm.dateRange.startDate,
        date_end: itemSelectionForm.dateRange.endDate,
    } : undefined;

    props.onItemsConfirm(
        {
            item_id: selectedItem.value.id,
            sale_type: itemSelectionForm.sale_type,
            additional_notes: itemSelectionForm.additional_notes ?? undefined,
            fitting_price: itemSelectionForm.fitting_price ?? undefined,
            fitting_notes: itemSelectionForm.fitting_notes ?? undefined,
            rent_detail: rentDetail,
            subtotal: subtotal.value,
        },
        toRaw(selectedItem.value)
    );

    modal.hide();
}

const confirmEventSelection = (eventData) => {
    props.onEventConfirm(eventData);

    confirmItemSelection();
}

const subtotal = computed(() => {
    if (selectedItem.value)
        switch (itemSelectionForm.sale_type) {
            case 1: // Sale
                return selectedItem.value.price_sale + itemSelectionForm.fitting_price;
            case 2: // Rent
                return selectedItem.value.prices_rent[context.rent_duration] + itemSelectionForm.insurance_price + itemSelectionForm.fitting_price;
        }
});

const resetState = () => {
    itemSelectionForm.fitting_price = null;
    itemSelectionForm.fitting_notes = null;
    itemSelectionForm.additional_notes = null;
    itemSelectionForm.sale_type = null;
    itemSelectionForm.insurance_price = null;
    itemSelectionForm.dateRange = {
        startDate: null,
        endDate: null,
    };

    context.rent_duration = 4;

    selectedItem.value = null;
}

const onStartDateSelection = (_, startDate) => {
    if (!startDate.disabled)
        datePicker.value.finishSelection(startDate.index + context.rent_duration - 1);
    else
        simpleAlert('Fecha no disponible', 'La fecha seleccionada no está disponible.', 'warning');
}

const minDate = computed(() => {
    return props.minDate ? new Date(props.minDate) : currentLocalDate();
});
</script>

<template>
    <div class="modal fade" id="modal-item-selection">
        <div class="modal-dialog modal-xl">
            <div v-if="selectedItem" class="modal-content">
                <div class="modal-header">
                    <div class="d-flex flex-column align-items-start justify-content-center">
                        <span class="fs-2 text-gray-800 text-hover-primary fw-bold lh-1 mb-2">Agregar Artículo a
                            Orden</span>
                        <span class="text-muted fs-6 fw-semibold lh-1">{{ selectedItem.product_full_name }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="content flex-row-fluid">
                        <Wizard :stepsInfo="wizardStepsInfo">
                            <template v-slot:type-selection="{ stepperNav }">
                                <!--begin::Heading-->
                                <div class="pb-4 pb-lg-5">
                                    <!--begin::Notice-->
                                    <div class="text-muted fw-semibold fs-6">
                                        Seleccione el tipo de operación que desea registrar.
                                        <span class="link-primary fw-bold">Venta o renta</span>.
                                    </div>
                                    <!--end::Notice-->
                                </div>
                                <!--end::Heading-->

                                <!--begin::Input group-->
                                <div class="fv-row fv-plugins-icon-container py-10">
                                    <!--begin::Row-->
                                    <div class="row">
                                        <!--begin::Col-->
                                        <div class="col-lg-6 hover-scale mb-3">
                                            <!--begin::Option-->
                                            <button @click="itemSelectionForm.sale_type = 1, stepperNav.nextStep()"
                                                class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center col-12"
                                                :disabled="reservedDatesLabel != null || isTransfer">
                                                <i class="ki-duotone ki-handcart text-success fs-3x me-5">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>
                                                <!--begin::Info-->
                                                <span class="d-block fw-semibold text-start">
                                                    <span class="text-gray-900 fw-bold d-block fs-4 mb-2">
                                                        Registrar una Venta
                                                    </span>
                                                    <span class="text-muted fw-semibold fs-6">Sólo agregue los ajustes y
                                                        listo!</span>
                                                </span>
                                                <!--end::Info-->
                                            </button>
                                            <!--end::Option-->
                                            <div v-if="reservedDatesLabel != null"
                                                class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback text-center">
                                                Este vestido no se puede vender ya tiene rentas para las fechas {{
                                                reservedDatesLabel }}
                                            </div>
                                        </div>
                                        <!--end::Col-->

                                        <!--begin::Col-->
                                        <div class="col-lg-6 hover-scale">
                                            <!--begin::Option-->
                                            <button @click="itemSelectionForm.sale_type = 2, stepperNav.nextStep()"
                                                class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center col-12"
                                                :disabled="isTransfer || isBarlyDesigner">
                                                <i class="ki-duotone ki-calendar-add text-warning fs-3x me-5">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                    <span class="path4"></span>
                                                    <span class="path5"></span>
                                                    <span class="path6"></span>
                                                </i>
                                                <!--begin::Info-->
                                                <span class="d-block fw-semibold text-start">
                                                    <span class="text-gray-900 fw-bold d-block fs-4 mb-2">
                                                        Registrar una Renta
                                                    </span>
                                                    <span class="text-muted fw-semibold fs-6">Seleccione la fecha para
                                                        lucir el vestido</span>
                                                </span>
                                                <!--end::Info-->
                                            </button>
                                            <!--end::Option-->
                                            <div v-if="isBarlyDesigner"
                                                class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback text-center">
                                                Este producto no esta disponible para rentas.
                                            </div>
                                        </div>
                                        <!--end::Col-->
                                        <div v-if="isTransfer || reservedDatesLabel != null"
                                            class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback text-center">
                                            <span v-if="isTransfer">
                                                {{ transferMessage }}
                                            </span>
                                        </div>
                                    </div>
                                    <!--end::Row-->
                                </div>
                                <!--end::Input group-->
                            </template>

                            <template v-slot:item-info>
                                <!--begin::Heading-->
                                <div class="pb-4 pb-lg-5">
                                    <!--begin::Notice-->
                                    <div class="text-primary fw-bold fs-6">
                                        Información del artículo seleccionado
                                    </div>
                                    <!--end::Notice-->
                                </div>
                                <!--end::Heading-->

                                <!--begin::Input group-->
                                <div class="fv-row fv-plugins-icon-container">
                                    <div class="row">
                                        <div class="col-4">
                                            <img class="img-fluid rounded-3"
                                                :src="selectedItem?.first_image ?? url('media/misc/image.png')"
                                                :alt="selectedItem.product_name">
                                        </div>
                                        <div class="col-8">
                                            <div class="row row-cols-lg-2">
                                                <!--begin::Input-->
                                                <div class="col mb-3">
                                                    <!--begin::Inbox-->
                                                    <input v-model="selectedItem.product_name" type="text"
                                                        class="form-control" disabled>
                                                    <!--end::Inbox-->
                                                </div>
                                                <!--end::Input-->
                                                <!--begin::Input-->
                                                <div class="col mb-3">
                                                    <!--begin::Inbox-->
                                                    <input v-model="selectedItem.store_name" type="text"
                                                        class="form-control" disabled>
                                                    <!--end::Inbox-->
                                                </div>
                                                <!--end::Input-->
                                                <!--begin::Input-->
                                                <div class="col mb-3">
                                                    <!--begin::Inbox-->
                                                    <input v-model="selectedItem.color_name" type="text"
                                                        class="form-control" disabled>
                                                    <!--end::Inbox-->
                                                </div>
                                                <!--end::Input-->
                                                <!--begin::Input-->
                                                <div class="col mb-3">
                                                    <!--begin::Inbox-->
                                                    <input v-model="selectedItem.size_name" type="text"
                                                        class="form-control" disabled>
                                                    <!--end::Inbox-->
                                                </div>
                                                <!--end::Input-->
                                            </div>

                                            <div class="row row-cols-lg-2">
                                                <!--begin::Input-->
                                                <div class="col mb-3">
                                                    <!--begin::Inbox-->
                                                    <input v-model="selectedItem.category_name" type="text"
                                                        class="form-control" disabled>
                                                    <!--end::Inbox-->
                                                </div>
                                                <!--end::Input-->
                                                <!--begin::Input-->
                                                <div class="col mb-3">
                                                    <!--begin::Inbox-->
                                                    <input v-model="selectedItem.designer_name" type="text"
                                                        class="form-control" disabled>
                                                    <!--end::Inbox-->
                                                </div>
                                                <!--end::Input-->
                                                <!--begin::Input-->
                                                <div class="col mb-3">
                                                    <!--begin::Inbox-->
                                                    <input v-model="selectedItem.price_sale" type="text"
                                                        class="form-control" disabled>
                                                    <!--end::Inbox-->
                                                </div>
                                                <!--end::Input-->
                                                <!--begin::Input-->
                                                <div class="col mb-3">
                                                    <!--begin::Inbox-->
                                                    <input v-model="selectedItem.price_rent_label" type="text"
                                                        class="form-control" disabled>
                                                    <!--end::Inbox-->
                                                </div>
                                                <!--end::Input-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Input group-->
                            </template>

                            <template v-slot:sale-details>
                                <!--begin::Heading-->
                                <div class="pb-4 pb-lg-5">
                                    <!--begin::Notice-->
                                    <div class="text-muted fw-semibold fs-6">
                                        Ajuste de precios y notas adicionales para la
                                        <span class="link-primary fw-bold">
                                            {{ itemSelectionForm.sale_type == 1 ? 'Venta' : 'Renta' }}
                                        </span>.
                                    </div>
                                    <!--end::Notice-->
                                </div>
                                <!--end::Heading-->

                                <div class="fv-row fv-plugins-icon-container">
                                    <!--begin::Common Sale Details-->
                                    <div class="row row-cols-2">
                                        <div class="col-md-6">
                                            <!--begin::Input-->
                                            <div class="col-md-12">
                                                <!--begin::Inbox-->
                                                <div class="menu-item mb-3">
                                                    <!--begin::Inbox-->
                                                    <input v-model="itemSelectionForm.fitting_price" type="number"
                                                        class="form-control" placeholder="Precio Ajustes">
                                                    <!--end::Inbox-->
                                                </div>
                                            </div>
                                            <!--end::Input-->
                                            <!--begin::Input-->
                                            <div class="col-md-12">
                                                <!--begin::Inbox-->
                                                <div class="menu-item mb-3">
                                                    <!--begin::Inbox-->
                                                    <textarea v-model="itemSelectionForm.fitting_notes" type="text"
                                                        class="form-control" placeholder="Notas de ajustes" rows="4"
                                                        :disabled="!itemSelectionForm.fitting_price"></textarea>
                                                    <!--end::Inbox-->
                                                </div>
                                            </div>
                                            <!--end::Input-->
                                        </div>
                                        <div class="col-md-6">
                                            <!--begin::Input-->
                                            <div class="col-md-12">
                                                <!--begin::Inbox-->
                                                <div class="menu-item mb-3">
                                                    <!--begin::Inbox-->
                                                    <textarea v-model="itemSelectionForm.additional_notes" type="text"
                                                        class="form-control" placeholder="Notas adicionales"
                                                        rows="7"></textarea>
                                                    <!--end::Inbox-->
                                                </div>
                                            </div>
                                            <!--end::Input-->
                                        </div>

                                        <!--begin::For Rents Only -->
                                        <div v-if="itemSelectionForm.sale_type == 2" class="col-md-12 row">
                                            <!--begin::Input-->
                                            <div class="col mt-6 mb-5 text-center">
                                                <!--begin::Inbox-->
                                                <DatePicker ref="datePicker" v-model="itemSelectionForm.dateRange"
                                                    :disabled-ranges="selectedItem.reserved_dates"
                                                    @start-selection="onStartDateSelection"
                                                    :min-date="minDate.subDays(1)" />
                                                <!--end::Inbox-->
                                            </div>
                                            <!--end::Input-->

                                            <!--begin::Input-->
                                            <div class="col-6 mb-3">
                                                <!--begin::Inbox-->
                                                <div class="form-check mt-6">
                                                    <input v-model="itemSelectionForm.insurance_price"
                                                        class="form-check-input" type="checkbox" :true-value="95"
                                                        :false-value="null" id="insurance-price-input">
                                                    <label class="form-check-label" for="insurance-price-input">
                                                        Seguro: $ 95.00
                                                    </label>
                                                </div>
                                                <div class="mt-12">
                                                    <label class="form-label">Duración de la renta:</label>
                                                    <select v-model="context.rent_duration" name="rent_duration"
                                                        class="form-select form-control" data-control="select2"
                                                        data-placeholder="Duración" data-hide-search="true">
                                                        <option :value="null" hidden>Seleccione una duración</option>
                                                        <option v-for="dayAmount in [4, 8]" :value="dayAmount">
                                                            {{ dayAmount }} días
                                                        </option>
                                                    </select>
                                                </div>
                                                <!--end::Inbox-->
                                            </div>
                                            <!--end::Input-->
                                        </div>
                                        <!--end::For Rents Only -->
                                    </div>

                                    <!--begin::Input-->
                                    <div class="col-4 mb-3 ms-auto">
                                        <!--begin::Inbox-->
                                        <label class="form-label">Subtotal:</label>
                                        <div class="input-group mb-5">
                                            <span class="input-group-text">$</span>
                                            <input v-model="subtotal" type="text" class="form-control" aria-label=""
                                                disabled />
                                        </div>
                                        <!--end::Inbox-->
                                    </div>
                                    <!--end::Input-->
                                    <!--end::Common Sale Details-->
                                </div>
                            </template>

                            <template v-slot:event-selection>
                                <!--begin::Heading-->
                                <div class="text-center mb-9">
                                    <!--begin::Title-->
                                    <h1 class="mb-3">Seleccionar Evento</h1>
                                    <!--end::Title-->
                                    <!--begin::Description-->
                                    <div class="text-muted fw-semibold fs-5">Por favor selecciona el evento al que
                                        deseas asistir
                                    </div>
                                    <!--end::Description-->
                                </div>
                                <!--end::Heading-->

                                <EventSelection ref="eventSelection" :startDate="itemSelectionForm.dateRange.startDate"
                                    :endDate="itemSelectionForm.dateRange.endDate"
                                    :rent_duration="context.rent_duration" :onConfirm="confirmEventSelection"
                                    :eventTypes="eventTypes" :eventResults="eventResults"
                                    dropdownParentId="#modal-item-selection" />
                            </template>
                        </Wizard>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
