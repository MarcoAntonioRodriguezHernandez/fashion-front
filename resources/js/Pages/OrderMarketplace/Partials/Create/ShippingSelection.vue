<script setup>
import { ref, reactive, computed, onMounted, toRaw } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { initSelectElements } from '@src/utils.js';
import Wizard from '@components/Wizard.vue';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    client: { type: [Object, null], required: true },
    onConfirm: { type: Function, required: true },
    shippingPrices: { type: Object, required: true },
    selectedShipping: { type: Object, default: null },
});

const addressCreationForm = useForm({
    interior_number: null,
    external_number: null,
    street: null,
    colony: null,
    city: null,
    state: null,
    zip_code: null,
});

const shippingSelectionForm = reactive({
    address_id: null,
    shipping_price_id: null,
});

onMounted(() => {
    if (shippingSelectionRoot.value) {
        initSelectElements(shippingSelectionRoot, shippingSelectionForm);
    }
});

// ------------------------
// Declarations
// ------------------------

const shippingSelectionRoot = ref(null);
const addressesOptions = computed(() => props.client?.user_addresses);
const wizardStepsInfo = [
    {
        name: 'type-selection',
        actions: false,
    },
    {
        name: 'new-address',
        enabled: () => !addressCreationForm.processing && addressCreationForm.isDirty,
        actions: {
            prev: (stepper) => stepper.setStep(0),
            next: (stepper) => {
                submitAddressCreation(() => {
                    stepper.setStep(2);
                });
            },
        },
    },
    {
        name: 'existing-address',
        enabled: () => shippingSelectionForm.address_id != null && shippingSelectionForm.shipping_price_id != null,
        actions: {
            prev: (stepper) => stepper.setStep(0),
            next: () => {
                confirmShippingSelection();
            },
        },
    },
];

const confirmShippingSelection = () => {
    let address = addressesOptions.value.find((a) => a.id == shippingSelectionForm.address_id);
    let pricing = props.shippingPrices.find((p) => p.id == shippingSelectionForm.shipping_price_id);

    addressCreationForm.reset();

    props.onConfirm(toRaw({
        ...address,
        shipping_price: pricing,
    }));
}

const submitAddressCreation = (onSuccess) => {
    addressCreationForm.transform((data) => ({
        ...data,
        user_id: props.client.id,
    }))
        .post(url('user-addresses'), {
            onSuccess: (page) => {
                shippingSelectionForm.address_id = page.props.flash.data.data.id; // Auto select the new address

                router.reload({
                    data: {
                        client_id: props.client.id,
                    },
                    only: ['clientInfo'],
                    method: 'post',
                    onSuccess: onSuccess,
                });
            },
        });
}
</script>

<template>
    <div ref="shippingSelectionRoot" class="card card-flush py-4 mb-8">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Title-->
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold fs-3 mb-1 ">Agregar Envío a Orden</span>
                <span class="text-muted fw-semibold fs-5">Por favor selecciona la dirección de {{ client.full_name }} para
                    <span class="link-primary fw-bold">enviar la orden</span>.</span>
            </h3>
            <!--end::Title-->

            <div class="card-toolbar">
                <i class="ki-duotone ki-delivery-geolocation fs-2hx text-dark">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                    <span class="path4"></span>
                    <span class="path5"></span>
                </i>
            </div>
        </div>
        <!--end::Card header-->

        <div class="card-body scroll-y">
            <!--begin::Input group-->
            <Wizard v-if="selectedShipping == null" :stepsInfo="wizardStepsInfo" :showNavBar="false">
                <template v-slot:type-selection="{ stepperNav }">
                    <!--begin::Input group-->
                    <div class="fv-row fv-plugins-icon-container">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-12 hover-scale">
                                <!--begin::Option-->
                                <label @click="stepperNav.setStep(1)" class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center mb-10">
                                    <i class="ki-duotone ki-plus-circle fs-3x me-5">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <!--begin::Info-->
                                    <span class="d-block fw-semibold text-start">
                                        <span class="text-gray-900 fw-bold d-block fs-4 mb-2">
                                            Registrar una Nueva Dirección
                                        </span>
                                        <span class="text-muted fw-semibold fs-6">Sólo agregue los ajustes y listo!</span>
                                    </span>
                                    <!--end::Info-->
                                </label>
                                <!--end::Option-->
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                            <!--end::Col-->

                            <!--begin::Col-->
                            <div class="col-12 hover-scale">
                                <!--begin::Option-->
                                <label @click="stepperNav.setStep(2)" class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center">
                                    <i class="ki-duotone ki-home-3 fs-3x me-5">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <!--begin::Info-->
                                    <span class="d-block fw-semibold text-start">
                                        <span class="text-gray-900 fw-bold d-block fs-4 mb-2">
                                            Seleccionar una Dirección Existente
                                        </span>
                                        <span class="text-muted fw-semibold fs-6">Seleccione la fecha para lucir el vestido</span>
                                    </span>
                                    <!--end::Info-->
                                </label>
                                <!--end::Option-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Input group-->
                </template>

                <template v-slot:new-address>
                    <!--begin::Address Creation-->
                    <div class="col-12">
                        <div class="col row fv-row">
                            <!--begin::Input-->
                            <div class="col-6 mb-3">
                                <!--begin::Inbox-->
                                <input v-model="addressCreationForm.zip_code" type="text" class="form-control" placeholder="Código Postal">
                                <!--end::Inbox-->
                            </div>
                            <!--end::Input-->
                            <!--begin::Input-->
                            <div class="col-6 mb-3">
                                <!--begin::Inbox-->
                                <input v-model="addressCreationForm.state" type="text" class="form-control" placeholder="Estado">
                                <!--end::Inbox-->
                            </div>
                            <!--end::Input-->
                            <!--begin::Input-->
                            <div class="col-6 mb-3">
                                <!--begin::Inbox-->
                                <input v-model="addressCreationForm.city" type="text" class="form-control" placeholder="Ciudad">
                                <!--end::Inbox-->
                            </div>
                            <!--end::Input-->
                            <!--begin::Input-->
                            <div class="col-6 mb-3">
                                <!--begin::Inbox-->
                                <input v-model="addressCreationForm.colony" type="text" class="form-control" placeholder="Colonia">
                                <!--end::Inbox-->
                            </div>
                            <!--end::Input-->
                            <!--begin::Input-->
                            <div class="col-6 mb-3">
                                <!--begin::Inbox-->
                                <input v-model="addressCreationForm.street" type="text" class="form-control" placeholder="Calle">
                                <!--end::Inbox-->
                            </div>
                            <!--end::Input-->
                            <!--begin::Input-->
                            <div class="col-3 mb-3">
                                <!--begin::Inbox-->
                                <input v-model="addressCreationForm.interior_number" type="text" class="form-control" placeholder="N. Interior">
                                <!--end::Inbox-->
                            </div>
                            <!--end::Input-->
                            <!--begin::Input-->
                            <div class="col-3 mb-3">
                                <!--begin::Inbox-->
                                <input v-model="addressCreationForm.external_number" type="text" class="form-control" placeholder="N. Exterior">
                                <!--end::Inbox-->
                            </div>
                            <!--end::Input-->
                        </div>
                    </div>
                    <!--end::Address Creation-->
                </template>

                <template v-slot:existing-address>
                    <!--begin::Address Search-->
                    <div class="col-12">
                        <div class="mb-10">
                            <div class="fv-row">
                                <!--begin::Input-->
                                <div class="col mb-3">
                                    <select v-model="shippingSelectionForm.shipping_price_id" name="shipping_price_id" class="form-select form-control" data-control="select2" data-placeholder="Precio de Envío" data-hide-search="true">
                                        <option value="" hidden>Seleccione un precio de envío</option>
                                        <option v-for="shippingPrice in shippingPrices" :value="shippingPrice.id">
                                            $ {{ shippingPrice.price }} - {{ shippingPrice.name }}
                                        </option>
                                    </select>
                                </div>
                                <!--end::Input-->
                            </div>

                            <!--begin::Address Results-->
                            <div class="mh-375px scroll-y me-n7 pe-7">
                                <!--begin::Address-->
                                <div class="table-responsive">
                                    <table class="table table-row-bordered table-row-dashed gy-4 align-middle fw-bold">
                                        <tbody>
                                            <tr v-for="address in addressesOptions">
                                                <td class="text-center">
                                                    <input v-model="shippingSelectionForm.address_id" class="form-check-input" type="radio" name="address_id" :id="'address-id-' + address.id" :value="address.id">
                                                </td>
                                                <td class="text-start">
                                                    <!--begin::Details-->
                                                    <label class="col-12" :for="'address-id-' + address.id">
                                                        <!--begin::Name-->
                                                        <span class="d-flex align-items-center fs-5 fw-bold text-gray-900 text-hover-primary">{{ address.street }}
                                                            <span class="badge badge-light fs-8 fw-semibold ms-2">{{ address.zip_code }}</span></span>
                                                        <!--end::Name-->
                                                        <!--begin::Email-->
                                                        <div class="fw-semibold text-muted">{{ address.city }}, {{ address.state }}</div>
                                                        <!--end::Email-->
                                                    </label>
                                                    <!--end::Details-->
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!--end::Address-->
                            </div>
                            <!--end::Address Results-->
                        </div>
                    </div>
                    <!--end::Address Search-->
                </template>
            </Wizard>

            <div v-else class="d-flex align-items-start flex-column">
                <div class="text-gray-500 flex-grow-1 me-4 mt-3 mb-3 fw-bold">Dirección seleccinado</div>

                <div class="d-flex flex-stack">
                    <!--begin::Symbol-->
                    <div class="symbol symbol-40px me-5">
                        <i class="ki-duotone ki-map fs-3hx">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </div>
                    <!--end::Symbol-->
                    <!--begin::Section-->
                    <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                        <!--begin:Author-->
                        <div class="flex-grow-1 me-2">
                            <a class="text-gray-800 text-hover-primary fs-6 fw-bold"> {{ selectedShipping.street }}</a>
                            <span class="text-muted fw-semibold d-block fs-7"> {{ selectedShipping.city }}, {{ selectedShipping.zip_code }}</span>
                        </div>
                        <!--end:Author-->
                    </div>
                    <!--end::Section-->
                </div>

                <button @click="onConfirm(null)" class="btn btn-primary align-self-end mt-5">
                    Cambiar Cliente
                </button>
            </div>
        </div>
    </div>
</template>
