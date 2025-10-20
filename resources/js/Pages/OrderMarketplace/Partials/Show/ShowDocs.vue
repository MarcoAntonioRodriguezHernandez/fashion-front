<script setup>
import { defineProps, onMounted, reactive, computed } from 'vue';
import ContractContent from '@/Pages/OrderMarketplace/Partials/ContractContent.vue';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    order: { type: Object, required: true },
    client: { type: Object, required: true },
});

// ------------------------
// Declarations
// ------------------------

let modal = null;
const modalInfo = reactive({
    imageSrc: '',
    title: '',
});

onMounted(() => {
    const modalElement = document.getElementById('imageModal');
    modal = new bootstrap.Modal(modalElement);
});

function openModal(imageSrc, title) {
    modalInfo.imageSrc = imageSrc;
    modalInfo.title = title;

    if (modal)
        modal.show();
}

const hasVenta = () => {
    const saleTypes = props.order.sale_type_names;

    if (!saleTypes) return false;

    const values = Array.isArray(saleTypes)
        ? saleTypes
        : Object.values(saleTypes || {});

    return values.some(t => String(t).toLowerCase().trim() === 'venta');
};

const docsTypes = Object.freeze({
    'Contrato': url('/order/marketplace/' + props.order.id + '/docs/contract'),
    'Pagaré': url('/order/marketplace/' + props.order.id + '/docs/promissory'),
    'Nota': url('/order/marketplace/' + props.order.id + '/docs/note'),
});

const docsTypesSale = Object.freeze({
    'Contrato': url('/order/marketplace/' + props.order.id + '/docs/contract'),
    'Pagaré': url('/order/marketplace/' + props.order.id + '/docs/promissory'),
    'Nota': url('/order/marketplace/' + props.order.id + '/docsaleRent/noteSaleRent'),
});
</script>

<template>
    <div class="row d-flex align-items-stretch">
        <div class="col-md-6 d-flex my-4">
            <div class="card card-flush py-4 w-100">
                <!--begin::Card header-->
                <div class="text-center mt-8">
                    <!--begin::Title-->
                    <h3 class="fs-1hx text-gray-900 mb-2 mt-2 text-uppercase">Contrato del cliente</h3>
                    <!--end::Title-->
                    <!--begin::Text-->
                    <div class="fs-5 text-muted fw-semibold">
                        Términos y Condiciones Renta de Vestidos
                    </div>
                    <!--end::Text-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body">
                    <div class="card-scroll h-475px">
                        <ContractContent :client-name="client?.full_name" />
                    </div>
                    <div class="d-flex justify-content-center mt-10">
                        <div class="d-flex justify-content-center">
                            <div class="d-flex justify-content-center">
                                <img :src="order.identity_document.signature" alt="Firma del cliente" class="w-75" />
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <p class="fw-bold">{{ client.full_name }}</p>
                    </div>
                </div>
                <!--end::Card body-->
            </div>
        </div>
        <div class="col-md-6 d-flex my-4">
            <div class="card card-flush py-4 w-100">
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title">
                        <h2>Detalles del cliente</h2>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <div class="table-responsive overflow-hidden">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                            <tbody class="fw-semibold text-gray-600">
                                <tr>
                                    <td class="text-muted">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-profile-circle fs-2 me-2"></i>Cliente
                                        </div>
                                    </td>
                                    <td class="fw-bold text-end">
                                        <div class="d-flex align-items-center justify-content-end">
                                            <!--begin:: Avatar -->
                                            <div class="symbol symbol-circle symbol-25px overflow-hidden me-3">
                                                <a>
                                                    <div class="symbol-label">
                                                        <img :src="client.photo" alt="Dan Wilson" class="w-100" />
                                                    </div>
                                                </a>
                                            </div>
                                            <!--end::Avatar-->
                                            <!--begin::Name-->
                                            <a :href="`/users/${client.id}`" class="fw-bold text-end text-primary">{{
                                                client.full_name }}</a>
                                            <!--end::Name-->
                                        </div>
                                    </td>
                                </tr>
                                <!-- gender -->
                                <tr>
                                    <td class="text-muted text-start">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-user-square fs-2 me-2"></i>Género
                                        </div>
                                    </td>
                                    <td class="text-end text-primary">
                                        <a class="fw-bold text-end text-primary">{{ client.gender }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-sms fs-2 me-2"></i>Correo
                                        </div>
                                    </td>
                                    <td class="fw-bold text-end">
                                        <a class="fw-bold text-end text-primary">{{ client.email }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-phone fs-2 me-2"></i>Telefono
                                        </div>
                                    </td>
                                    <td class="fw-bold text-end text-primary">{{ client.phone }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <!--end::Table-->

                        <div class="card-header px-0 mt-4">
                            <div class="card-title">
                                <h2>Identificaciones del cliente</h2>
                            </div>
                        </div>
                        <div class="row mt-8">
                            <div class="col-md-12 col-xl-12 col-xxl-6 mb-6">
                                <div class="d-flex justify-content-center">
                                    <div class="image-input image-input-outline" data-kt-image-input="true">
                                        <!--begin::Image preview wrapper-->
                                        <div class="image-input-wrapper w-275px h-200px d-flex justify-content-center align-items-center bg-no-repeat bg-contain"
                                            :style="{ backgroundImage: `url(${order.identity_document.front})`, }"
                                            @click="openModal(props.order.identity_document.front, 'Identificación frontal')">
                                        </div>
                                        <!--end::Image preview wrapper-->
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-xl-12 col-xxl-6 mb-6">
                                <div class="d-flex justify-content-center">
                                    <div class="image-input image-input-outline" data-kt-image-input="true">
                                        <!--begin::Image preview wrapper-->
                                        <div class="image-input-wrapper w-275px h-200px d-flex justify-content-center align-items-center bg-no-repeat bg-contain"
                                            :style="{ backgroundImage: `url(${order.identity_document.back})`, }"
                                            @click="openModal(props.order.identity_document.back, 'Identificación trasera')">
                                        </div>
                                        <!--end::Image preview wrapper-->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--begin::Docs buttons-->
                        <!--begin::Docs buttons-->
                        <div v-if="hasVenta()" class="d-flex flex-wrap justify-content-center mt-4">
                            <a v-for="docUrl, docName in docsTypesSale" class="btn btn-secondary mb-2 mx-2"
                                :href="docUrl" target="_blank">{{ docName }}</a>
                        </div>
                        <div v-else class="d-flex flex-wrap justify-content-center mt-4">
                            <a v-for="docUrl, docName in docsTypes" class="btn btn-secondary mb-2 mx-2" :href="docUrl"
                                target="_blank">{{ docName }}</a>
                        </div>
                        <!--end::Docs buttons-->
                        <!--end::Docs buttons-->
                    </div>
                </div>
                <!--end::Card body-->
            </div>
        </div>
    </div>
    <!-- Modal INE -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">{{ modalInfo.title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <img :src="modalInfo.imageSrc" class="img-fluid" alt="Ampliación de la imagen" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>