<script setup>
import { ref, reactive, onMounted, toRaw } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { initSelectElements } from '@src/utils.js';
import Wizard from '@components/Wizard.vue';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    clientResults: { type: [Array, null], required: true },
    onConfirm: { type: Function, required: true },
    genders: { type: Object, required: true },
    clientInfo: { type: Object, default: null },
});

const clientCreationForm = useForm({
    name: null,
    last_name: null,
    email: null,
    phone: null,
    client_detail: {
        date_of_birth: null,
        gender: 0,
    },
});

onMounted(() => {
    if (clientSelectionRoot.value) {
        initSelectElements(clientSelectionRoot, clientCreationForm);
    }
});

// ------------------------
// Declarations
// ------------------------
const debounce = ref(null);
const clientSelectionRoot = ref(null);
const context = reactive({
    client_search: null,
});
const wizardStepsInfo = [
    {
        name: 'type-selection',
        actions: false,
    },
    {
        name: 'new-client',
        enabled: () => !clientCreationForm.processing && clientCreationForm.isDirty,
        actions: {
            prev: (stepper) => stepper.setStep(0),
            next: () => {
                submitClientCreation();
            },
        },
    },
    {
        name: 'existing-client',
        actions: {
            prev: (stepper) => stepper.setStep(0),
            next: false,
        },
    },
];

const confirmClientSelection = (client) => {
    clientCreationForm.reset();

    props.onConfirm(client);
}

const submitClientSearch = (search = null) => {
    context.client_search = search ?? context.client_search;

    router.reload({
        data: {
            client_search: context.client_search,
        },
        method: 'post',
        only: ['clientResults'],
    })
}

const submitClientCreation = () => {
    clientCreationForm.post(url('users'), {
        onSuccess: (page) => {
            confirmClientSelection(page.props.flash.data.data);
        },
    });
}

const debounceAction = (action, timeout = 1000) => {
    clearTimeout(debounce.value);

    debounce.value = setTimeout(() => {
        action();
    }, timeout);
}
</script>

<template>
    <div ref="clientSelectionRoot" class="card card-flush py-4 mb-8">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Title-->
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold fs-3 mb-1 ">Seleccione el cliente</span>
                <span class="text-muted fw-semibold fs-5">Crear un cliente nuevo o seleccionar uno existente</span>
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
        <!--end::Card header-->

        <!--begin::Card header-->
        <div class="card-body scroll-y">
            <!--begin::Input group-->
            <Wizard v-if="clientInfo == null" :stepsInfo="wizardStepsInfo" :showNavBar="false">
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
                                            Registrar un Nuevo Cliente
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
                                    <i class="ki-duotone ki-user-tick fs-3x me-5">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                    <!--begin::Info-->
                                    <span class="d-block fw-semibold text-start">
                                        <span class="text-gray-900 fw-bold d-block fs-4 mb-2">
                                            Seleccionar un Usuario Existente
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

                <template v-slot:new-client>
                    <!--begin::New Client-->
                    <div class="col-12">
                        <div class="col row row-cols-1 fv-row">
                            <!--begin::Input-->
                            <div class="col mb-3">
                                <!--begin::Inbox-->
                                <input v-model="clientCreationForm.name" type="text" class="form-control" placeholder="Nombre del Cliente">
                                <!--end::Inbox-->
                            </div>
                            <!--end::Input-->
                            <!--begin::Input-->
                            <div class="col mb-3">
                                <!--begin::Inbox-->
                                <input v-model="clientCreationForm.last_name" type="text" class="form-control" placeholder="Apellido del Cliente">
                                <!--end::Inbox-->
                            </div>
                            <!--end::Input-->
                            <!--begin::Input-->
                            <div class="col mb-3">
                                <!--begin::Inbox-->
                                <input v-model="clientCreationForm.email" type="text" class="form-control" placeholder="Correo Electrónico del Cliente">
                                <!--end::Inbox-->
                            </div>
                            <!--end::Input-->
                            <!--begin::Input-->
                            <div class="col-6 mb-3">
                                <!--begin::Inbox-->
                                <input v-model="clientCreationForm.phone" type="text" class="form-control" placeholder="Número celular del Cliente">
                                <!--end::Inbox-->
                            </div>
                            <!--end::Input-->
                            <!--begin::Input-->
                            <div class="col-6 mb-3">
                                <!--begin::Inbox-->
                                <input v-model="clientCreationForm.client_detail.date_of_birth" type="date" class="form-control" placeholder="Fecha de Nacimiento del Cliente">
                                <!--end::Inbox-->
                            </div>
                            <!--end::Input-->
                            <!--begin::Input-->
                            <div class="col-6 mb-3">
                                <!--begin::Inbox-->
                                <select v-model="clientCreationForm.client_detail.gender" name="client_detail.gender" class="form-select form-control" data-control="select2" data-placeholder="Género del Cliente" data-hide-search="true">
                                    <option value="">Seleccione una opción</option>
                                    <option v-for="name, key in genders" :value="key">
                                        {{ name }}
                                    </option>
                                </select>
                                <!--end::Inbox-->
                            </div>
                            <!--end::Input-->
                        </div>
                    </div>
                    <!--end::New Client-->
                </template>

                <template v-slot:existing-client>
                    <!--begin::Client Search-->
                    <div class="col-12">
                        <!--begin::Input Search-->
                        <div class="d-flex align-items-center position-relative w-100 m-0 pb-3" autocomplete="off">
                            <!--begin::Icon-->
                            <i class="ki-outline ki-magnifier fs-3 text-gray-500 position-absolute top-50 start-0 translate-middle-y ms-5"></i>
                            <!--end::Icon-->
                            <!--begin::Input-->
                            <div class="position-relative w-100">
                                <form @submit.prevent="submitClientSearch()">
                                    <input v-model="context.client_search" @input="debounceAction(() => submitClientSearch())" type="text" class="form-control form-control-solid ps-5" placeholder="Buscar cliente">
                                </form>
                            </div>
                            <!--end::Input-->
                        </div>
                        <!--end::Input Search-->

                        <!--begin::No results Alert-->
                        <div v-if="clientResults == null || clientResults.length == 0" class="alert alert-warning py-2" role="alert">
                            Ajuste su búsqueda para encontrar resultados.
                        </div>
                        <!--end::No results Alert-->

                        <!--begin::Limit Alert-->
                        <div v-else-if="clientResults.length >= 100" class="alert alert-warning py-2" role="alert">
                            ¡Sólo se muestran los primeros 100 resultados! <br>
                            Por favor, refine su búsqueda.
                        </div>
                        <!--end::Limit Alert-->

                        <!--begin::Client Results-->
                        <div class="mb-10">
                            <!--begin::List-->
                            <div class="mh-375px scroll-y me-n7 pe-7">
                                <!--begin::Client-->
                                <div class="table-responsive">
                                    <table class="table table-row-bordered table-row-dashed gy-4 align-middle fw-bold">
                                        <tbody>
                                            <tr v-for="client in clientResults">
                                                <td class="pe-0">
                                                    <!--begin::Avatar-->
                                                    <div class="symbol symbol-35px symbol-circle">
                                                        <img alt="Pic" :src="client.photo ?? url('/src/img/user-image.png')" />
                                                    </div>
                                                    <!--end::Avatar-->
                                                </td>
                                                <td class="w-100 ps-0">
                                                    <!--begin::Details-->
                                                    <div class="ms-6">
                                                        <!--begin::Name-->
                                                        <span class="d-flex align-items-center fs-5 fw-bold text-gray-900 text-hover-primary">{{ client.full_name }}
                                                            <span class="badge badge-light fs-8 fw-semibold ms-2">Staff</span></span>
                                                        <!--end::Name-->
                                                        <!--begin::Email-->
                                                        <div class="fw-semibold text-muted">{{ client.email }}</div>
                                                        <!--end::Email-->
                                                    </div>
                                                    <!--end::Details-->
                                                </td>
                                                <td class="text-center">
                                                    <button @click="confirmClientSelection(toRaw(client))" class="btn btn-icon btn-secondary" type="button">
                                                        <i class="ki-duotone ki-right-square fs-2tx">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!--end::Client-->
                            </div>
                            <!--end::List-->
                        </div>
                        <!--end::Client Results-->
                    </div>
                    <!--end::Client Search-->
                </template>
            </Wizard>
            <!--end::Input group-->

            <div v-else class="d-flex align-items-start flex-column">
                <div class="text-gray-500 flex-grow-1 me-4 mt-3 mb-3 fw-bold">Cliente seleccinado</div>

                <div class="d-flex flex-stack">
                    <!--begin::Symbol-->
                    <div class="symbol symbol-40px me-5">
                        <img :src="clientInfo.photo ?? url('/src/img/blank.png')" class="h-50 align-self-center" alt="">
                    </div>
                    <!--end::Symbol-->
                    <!--begin::Section-->
                    <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                        <!--begin:Author-->
                        <div class="flex-grow-1 me-2">
                            <a class="text-gray-800 text-hover-primary fs-6 fw-bold"> {{ clientInfo.full_name }}</a>
                            <span class="text-muted fw-semibold d-block fs-7"> {{ clientInfo.email }}</span>
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
        <!--end::Card header-->
    </div>
</template>
