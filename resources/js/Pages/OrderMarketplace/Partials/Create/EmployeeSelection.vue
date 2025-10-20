<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed, onMounted, reactive, ref, watch } from 'vue';
import { initSelectElements } from '@src/utils.js';

// ------------------------
// Component Attributes
// ------------------------

const pageProps = usePage().props;

const props = defineProps({
    storeId: { type: Number, default: null },
    onConfirm: { type: Function, required: true },
});

const context = reactive({
    employee_id: null,
});

onMounted(() => {
    if (selectionRoot.value)
        initSelectElements(selectionRoot, context);
});

watch(() => context.employee_id, (employeeId) => {
    props.onConfirm(pageProps.employees[employeeId]);
});

// ------------------------
// Declarations
// ------------------------

const selectionRoot = ref(null);

const employees = computed(() => {
    if (props.storeId == null)
        return {};

    return Object.values(pageProps.employees).filter((e) => e.employee_detail.store_id == props.storeId);
});
</script>

<template>
    <div class="card card-flush bg-body mb-4">
        <!--begin::Header-->
        <div class="card-header pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold fs-3 mb-1">Empleado</span>
                <span class="text-muted fw-semibold fs-5">Quién es el responsable de la orden. Deje vacío si es usted.</span>
            </h3>
        </div>
        <!--end::Header-->

        <!--begin::Body-->
        <div ref="selectionRoot" class="card-body pt-0">
            <div class="col">
                <label class="required form-label">Empleado</label>
                <select name="employee_id" class="form-select form-control" data-control="select2" data-placeholder="Empleado" :disabled="storeId == null">
                    <option value="">Elige una Empleado</option>
                    <option v-for="employee in employees" :value="employee.id">{{ employee.full_name }}</option>
                </select>
            </div>
        </div>
        <!--end::Body-->
    </div>
</template>
