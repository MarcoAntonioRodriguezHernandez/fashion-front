<script setup>
import { ref, reactive, computed, onMounted, toRaw, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import DatePicker from '@components/DatePicker.vue';
import { simpleAlert, url, normalizedDate } from '@src/utils';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    order: { type: Object, required: true },
    event: { type: Object, default: null },
});

const itemEditionForm = useForm({
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
        

        // Save type of sale in the origin order
        itemEditionForm.sale_type = itemInfo.order_detail.sale_type;
        
        // reset data eneable
        itemEditionForm.additional_notes = null;
        itemEditionForm.fitting_price = null;
        itemEditionForm.fitting_notes = null;
        
        // for de rents, reset data
        if (itemEditionForm.sale_type == 2) {
            itemEditionForm.insurance_price = null;
            itemEditionForm.dateRange = {
                startDate: null,
                endDate: null,
            };
            context.rent_duration = 4;
        }

        modal.show();
    },
});

// ------------------------
// Declarations
// ------------------------

let modal = null;
const selectedItem = ref(null);
const datePicker = ref(null);

const confirmItemEdition = () => {
    let rentDetail = itemEditionForm.sale_type == 2 ? { // Rent
        insurance_price: itemEditionForm.insurance_price,
        date_start: itemEditionForm.dateRange.startDate,
        date_end: itemEditionForm.dateRange.endDate,
        status: true,
    } : undefined;

    itemEditionForm.transform(() => ({
        id: selectedItem.value.order_detail.id,
        item_id: selectedItem.value.id,
        sale_type: itemEditionForm.sale_type,
        additional_notes: itemEditionForm.additional_notes ?? undefined,
        fitting_price: itemEditionForm.fitting_price ?? undefined,
        fitting_notes: itemEditionForm.fitting_notes ?? undefined,
        rent_detail: rentDetail,
        status: true,
    })).put(url('item/order/marketplace'), {
        onSuccess: () => {
            resetState();

            modal.hide();
        },
    });
}

const subtotal = computed(() => {

    if (selectedItem.value) {
        switch (itemEditionForm.sale_type) {
            case 1:
                const salePrice = selectedItem.value.price_sale || 0;
                const fittingPrice = itemEditionForm.fitting_price || 0;
                return salePrice + fittingPrice;
            case 2:
                const rentPrice = selectedItem.value.prices_rent?.[context.rent_duration] || 0;
                const insurancePrice = itemEditionForm.insurance_price || 0;
                const fittingPriceRent = itemEditionForm.fitting_price || 0;

                return rentPrice + insurancePrice + fittingPriceRent;
            default:
                return 0;
        }
    }
    return 0;
});

const resetState = () => {
    itemEditionForm.additional_notes = null;
    itemEditionForm.fitting_price = null;
    itemEditionForm.fitting_notes = null;
    itemEditionForm.insurance_price = null;
    itemEditionForm.dateRange = {
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
</script>

<template>
    <div class="modal fade" id="modal-item-selection">
        <div class="modal-dialog modal-xl">
            <div v-if="selectedItem" class="modal-content">
                <div class="modal-header">
                    <div class="d-flex flex-column align-items-start justify-content-center">
                        <span class="fs-2 text-gray-800 text-hover-primary fw-bold lh-1 mb-2">Editar Artículo de la Orden #{{ order.code }}</span>
                        <span class="text-muted fs-6 fw-semibold lh-1">{{ selectedItem.product_full_name }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="content flex-row-fluid">
                        <!--begin::Heading-->
                        <div class="pb-4 pb-lg-5">
                            <!--begin::Notice-->
                            <div class="text-muted fw-semibold fs-6">
                                Ajuste de precios y notas adicionales para la
                                <span class="link-primary fw-bold">
                                    {{ itemEditionForm.sale_type == 1 ? 'Venta' : 'Renta' }}
                                </span>.
                            </div>
                            <!--end::Notice-->
                        </div>
                        <!--end::Heading-->

                        <div class="fv-row fv-plugins-icon-container">
                            <div v-if="event?.variants.includes(selectedItem.product_variant_id)" class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-3 mb-6">
                                <!--begin::Icon-->
                                <i class="ki-outline ki-information fs-2tx text-warning me-4"></i>
                                <!--end::Icon-->
                                <!--begin::Wrapper-->
                                <div class="d-flex flex-stack flex-grow-1">
                                    <!--begin::Content-->
                                    <div class="fw-semibold fs-6">
                                        <div>El vestido seleccionado ya está reservado para el evento {{ event.label }}.</div>
                                        <div class="text-gray-600">Aún así, si lo desea, puede continuar con su orden.</div>
                                    </div>
                                    <!--end::Content-->
                                </div>
                                <!--end::Wrapper-->
                            </div>

                            <!--begin::Common Sale Details-->
                            <div class="row row-cols-2">
                                <div class="col-md-6">
                                    <!--begin::Input-->
                                    <div class="col-md-12">
                                        <!--begin::Inbox-->
                                        <div class="menu-item mb-3">
                                            <!--begin::Inbox-->
                                            <input v-model="itemEditionForm.fitting_price" type="number" class="form-control" placeholder="Precio Ajustes">
                                            <!--end::Inbox-->
                                        </div>
                                    </div>
                                    <!--end::Input-->
                                    <!--begin::Input-->
                                    <div class="col-md-12">
                                        <!--begin::Inbox-->
                                        <div class="menu-item mb-3">
                                            <!--begin::Inbox-->
                                            <textarea v-model="itemEditionForm.fitting_notes" type="text" class="form-control" placeholder="Notas de ajustes" rows="4" :disabled="!itemEditionForm.fitting_price"></textarea>
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
                                            <textarea v-model="itemEditionForm.additional_notes" type="text" class="form-control" placeholder="Notas adicionales" rows="7"></textarea>
                                            <!--end::Inbox-->
                                        </div>
                                    </div>
                                    <!--end::Input-->
                                </div>

                                <!--begin::For Rents Only -->
                                <div v-if="itemEditionForm.sale_type == 2" class="col-md-12 row">
                                    <!--begin::Input-->
                                    <div class="col mt-6 mb-5 text-center">
                                        <!--begin::Inbox-->
                                        <DatePicker ref="datePicker" v-model="itemEditionForm.dateRange" :disabled-ranges="selectedItem.reserved_dates" @start-selection="onStartDateSelection" :min-date="(new Date()).subDays(1)" />
                                        <!--end::Inbox-->
                                    </div>
                                    <!--end::Input-->

                                    <!--begin::Input-->
                                    <div class="col-6 mb-3">
                                        <!--begin::Inbox-->
                                        <div class="form-check mt-6">
                                            <input v-model="itemEditionForm.insurance_price" class="form-check-input" type="checkbox" :true-value="95" :false-value="null" id="insurance-price-input">
                                            <label class="form-check-label" for="insurance-price-input">
                                                Seguro: $ 95.00
                                            </label>
                                        </div>
                                        <div class="mt-12">
                                            <label class="form-label">Duración de la renta:</label>
                                            <select v-model="context.rent_duration" name="rent_duration" class="form-select form-control" data-control="select2" data-placeholder="Duración" data-hide-search="true">
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
                                    <input v-model="subtotal" type="text" class="form-control" aria-label="" disabled />
                                </div>
                                <!--end::Inbox-->
                            </div>
                            <!--end::Input-->
                            <!--end::Common Sale Details-->
                        </div>

                        <!--begin::Actions-->
                        <div class="text-end">
                            <!--begin::Submit button-->
                            <button @click="confirmItemEdition()" type="button" class="btn btn-lg btn-primary fw-bold h-40px fs-7">Confirmar</button>
                            <!--end::Submit button-->
                        </div>
                        <!--begin::Actions-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
