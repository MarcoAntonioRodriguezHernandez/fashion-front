<script setup>
import { onMounted } from 'vue';
import EventSelection from '@/Pages/OrderMarketplace/Partials/Create/EventSelection.vue';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    onConfirm: { type: Function, required: true },
    eventTypes: { type: Object, required: true },
    eventResults: { type: Array, default: () => null },
});

onMounted(() => {
    const modalElement = document.getElementById('modal-event-selection');
    modal = new bootstrap.Modal(modalElement);
});

defineExpose({
    show: () => {
        modal.show();
    },
});

// ------------------------
// Declarations
// ------------------------

let modal = null;

const onConfirm = (eventData) => {
    props.onConfirm(eventData);
    modal.hide();
}
</script>

<template>
    <div class="modal fade" id="modal-event-selection">
        <div class="modal-dialog mw-650px">
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <!--begin::Close-->
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                    <!--end::Close-->
                </div>
                <!--begin::Modal header-->
                <div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-10">
                    <!--begin::Heading-->
                    <div class="text-center mb-5">
                        <i class="ki-duotone ki-calendar" style="font-size: 4em;">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                    <div class="text-center mb-9">
                        <!--begin::Title-->
                        <h1 class="mb-3">Seleccionar Evento</h1>
                        <!--end::Title-->
                        <!--begin::Description-->
                        <div class="text-muted fw-semibold fs-5">Por favor selecciona el evento al que deseas asistir
                        </div>
                        <!--end::Description-->
                    </div>
                    <!--end::Heading-->

                    <EventSelection :onConfirm="onConfirm" :eventTypes="eventTypes" :eventResults="eventResults" />
                </div>
            </div>
        </div>
    </div>
</template>
