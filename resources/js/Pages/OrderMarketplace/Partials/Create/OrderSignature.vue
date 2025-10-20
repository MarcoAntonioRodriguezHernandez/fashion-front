<script setup>
import { reactive, ref } from 'vue';
import Vue3Signature from 'vue3-signature';
import ContractContent from '@/Pages/OrderMarketplace/Partials/ContractContent.vue';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    clientName: { type: String, default: null, },
    onSignatureConfirm: { type: Function, required: true },
    onIdentityDocumentsConfirm: { type: Function, required: true },
});

// ------------------------
// Declarations
// ------------------------

const context = reactive({
    identityDocuments: {
        frontal: {},
        back: {},
    },
});

const signatureElement = ref(null);

const handleIneUpload = (file, type) => {
    if (!file)
        return;

    context.identityDocuments[type] = {
        value: file,
        backgroundImage: 'url(' + URL.createObjectURL(file) + ')',
    };

    if (context.identityDocuments.frontal.value && context.identityDocuments.back.value) {
        props.onIdentityDocumentsConfirm({
            frontal: context.identityDocuments.frontal.value,
            back: context.identityDocuments.back.value,
        });
    }
}

const handleSignatureConfirm = (signaturePath) => {
    props.onSignatureConfirm(signaturePath);
}
</script>

<template>
    <div class="card card-xl-stretch mb-xl-8">
        <!--begin::Header-->
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
        <!--end::Header-->

        <!--begin::Body-->
        <div class="card-body">
            <div class="card-scroll h-475px">
                <ContractContent :client-name="clientName" />
            </div>

            <!-- Client-Sign -->
            <div class="card-footer">
                <div class="d-flex justify-content-center border shadow">
                    <Vue3Signature ref="signatureElement" w="100%" h="100%" :option="{ penColor: '#000', backgroundColor: '#FFF' }" class="signature-pad" @end="handleSignatureConfirm(signatureElement.save())" />
                </div>
                <div class="d-flex justify-content-center mt-4">
                    <button @click="signatureElement.clear()" class="btn btn-secondary">Borrar</button>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    <span class="card-label fw-bold text-gray-900 fs-5">Nombre y Firma de conformidad del Cliente</span>
                </div>
            </div>
            <!-- Client-Sign -->

            <hr>

            <!-- Client INE files -->
            <div class="row">
                <div v-for="key in Object.keys(context.identityDocuments)" class="col-md-12 col-xl-12 col-xxl-6 text-center">
                    <span class="required form-label">INE {{ key }}</span>
                    <div class="d-flex justify-content-center">
                        <div class="image-input image-input-outline" data-kt-image-input="true">
                            <div class="image-input-wrapper ine-container w-300px h-175px" :style="{ backgroundImage: context.identityDocuments[key].backgroundImage }">
                            </div>
                            <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" :title="'Tomar INE ' + key">
                                <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>
                                <!--begin::Inputs-->
                                <input type="file" @input="handleIneUpload($event.target.files[0], key)" />
                                <!--end::Inputs-->
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Client INE files -->
        </div>
    </div>
</template>

<style>
.ine-container {
    background-size: 'cover';
    background-position: 'center';
}
</style>
