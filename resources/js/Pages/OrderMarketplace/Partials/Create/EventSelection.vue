<script setup>
import { ref, reactive, watch, computed, onMounted, toRaw } from 'vue';
import { router } from '@inertiajs/vue3';
import { initSelectElements } from '@src/utils.js';
import DatePicker from '@components/DatePicker.vue';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    onConfirm: { type: Function, required: true },
    eventTypes: { type: Object, required: true },
    eventResults: { type: Array, default: () => null },
    dropdownParentId: { type: String, default: '#modal-event-selection' },
    startDate: { type: Date, default: null },
    endDate: { type: Date, default: null },
});

const eventSelectionForm = reactive({
    event_type: null,
    specification: null,
    scheduled_date: null,
    dateRange: {
        startDate: (new Date(props.endDate)).subDays(1),
        endDate: (new Date(props.endDate)).subDays(1),
    },
});

onMounted(() => {
    if (eventSelectionRoot.value)
        initSelectElements(eventSelectionRoot, eventSelectionForm);
});

watch(() => [eventSelectionForm.dateRange, eventSelectionForm.event_type], ([newDateRange]) => {
    eventSelectionForm.scheduled_date = newDateRange.startDate;

    if (specificationEnabled.value) {
        submitLoadEvents();
    }
});

defineExpose({
    initSelectElements: () => {
        initSelectElements(eventSelectionRoot, eventSelectionForm);
    },
});

// ------------------------
// Declarations
// ------------------------

const eventSelectionRoot = ref(null);

const confirmEventSelection = () => {
    let existingEvent = !isNaN(eventSelectionForm.specification) ?
        props.eventResults?.find(event => event.id == eventSelectionForm.specification)
        : null;
    let eventTypeName = isNaN(eventSelectionForm.event_type) ?
        eventSelectionForm.event_type :
        props.eventTypes.find(eventType => eventType.id == eventSelectionForm.event_type).name;
    let specificationText = existingEvent?.specification ?? eventSelectionForm.specification;

    let eventData = {
        label: eventTypeName + ', ' + specificationText,
        variants: toRaw(existingEvent?.variants),
    };

    if (isNaN(eventSelectionForm.specification)) {
        eventData.event = {
            specification: eventSelectionForm.specification,
            scheduled_date: eventSelectionForm.scheduled_date,
        };

        if (isNaN(eventSelectionForm.event_type)) {
            eventData.event.event_type_name = eventSelectionForm.event_type;
        } else {
            eventData.event.event_type_id = eventSelectionForm.event_type;
        }
    } else {
        eventData.event_id = eventSelectionForm.specification;
    }

    props.onConfirm(eventData);
}

const submitLoadEvents = () => {
    router.reload({
        data: {
            event_type_id: isNaN(eventSelectionForm.event_type) ? null : eventSelectionForm.event_type,
            scheduled_date: eventSelectionForm.scheduled_date,
        },
        only: ['eventResults'],
        method: 'post',
    });
}

const specificationEnabled = computed(() => {
    return eventSelectionForm.scheduled_date != null && eventSelectionForm.event_type != null;
});
</script>

<template>
    <div ref="eventSelectionRoot" class="fv-row fv-plugins-icon-container">
        <div class="col row fv-row">
            <!--begin::Input-->
            <div class="mb-3">
                <!--begin::Label-->
                <label class="required form-label">Fecha del evento</label>
                <!--end::Label-->
                <div class="col-12 text-center">
                    <DatePicker ref="datePicker" v-model="eventSelectionForm.dateRange" :min-Date="props.startDate" :max-date="props.endDate?.addDays(1)" single class="col-12" />
                </div>
            </div>
            <!--end::Input-->
            <!--begin::Input-->
            <div class="mb-3">
                <!--begin::Label-->
                <label class="required form-label">Tipo de Evento</label>
                <!--end::Label-->
                <select v-model="eventSelectionForm.event_type" name="event_type" class="form-select form-control" data-control="select2" data-placeholder="Tipo de Evento" data-tags="true" :data-dropdown-parent="dropdownParentId">
                    <option value="">Seleccione una opción</option>
                    <option v-for="eventType in eventTypes" :value="eventType.id">
                        {{ eventType.name }}
                    </option>
                </select>
            </div>
            <!--end::Input-->
            <!--begin::Input-->
            <div class="mb-3">
                <!--begin::Label-->
                <label class="required form-label">Anfitrión / Sede</label>
                <!--end::Label-->
                <select v-model="eventSelectionForm.specification" name="specification" class="form-select form-control" data-control="select2" data-placeholder="Anfitrión / Sede" data-tags="true" :disabled="!specificationEnabled" :data-dropdown-parent="dropdownParentId">
                    <option value="">Seleccione una opción</option>
                    <option v-for="event in eventResults" :value="event.id" :key="event.id">
                        {{ event.specification }}
                    </option>
                </select>
            </div>
            <!--end::Input-->
        </div>

        <!--begin::Actions-->
        <div class="text-end">
            <!--begin::Submit button-->
            <button @click="confirmEventSelection()" type="button" class="btn btn-lg btn-primary fw-bold h-40px fs-7" :disabled="!specificationEnabled">Confirmar</button>
            <!--end::Submit button-->
        </div>
        <!--begin::Actions-->
    </div>
</template>
