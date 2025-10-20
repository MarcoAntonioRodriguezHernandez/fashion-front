<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import Master from '@layouts/Master.vue';
import Pagination from '@components/Pagination.vue';
import { initSelectElements, updateSelectElements, scrollToTop, initGoTopButton } from '@src/utils.js';
import { generateExcelReport, url } from '../../src/utils';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    stores: { type: Array, required: true },
    saleTypes: { type: Object, required: true },
    payments: { type: Object, default: null },
});

const filtersToApply = reactive({
    store_id: null,
    sale_type: 0,
    start_date: null,
    finish_date: null,
});
const today = new Date();
const maxDate = today.toISOString().split('T')[0];
const sixMonthsAgo = new Date(today);
sixMonthsAgo.setMonth(today.getMonth() - 6);
const minDate = sixMonthsAgo.toISOString().split('T')[0];

onMounted(() => {
    const today = new Date();
    const currentDate = new Date();
    const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);

    if (filtersToApply.date_filter === 'last_week') {
        const start = new Date(today);
        start.setDate(today.getDate() - 7);
        filtersToApply.start_date = start.toISOString().split('T')[0];
        filtersToApply.finish_date = today.toISOString().split('T')[0];
    } else if (filtersToApply.date_filter === 'last_month') {
        const start = new Date(today);
        start.setMonth(today.getMonth() - 1);
        filtersToApply.start_date = start.toISOString().split('T')[0];
        filtersToApply.finish_date = today.toISOString().split('T')[0];
    } else if (filtersToApply.date_filter === 'last_2_months') {
        const start = new Date(today);
        start.setMonth(today.getMonth() - 2);
        filtersToApply.start_date = start.toISOString().split('T')[0];
        filtersToApply.finish_date = today.toISOString().split('T')[0];
    } else if (filtersToApply.date_filter === 'custom') {
        filtersToApply.start_date = firstDay.toISOString().split('T')[0];
        filtersToApply.finish_date = lastDay.toISOString().split('T')[0];
    }

    if (root.value) {
        [...root.value.querySelectorAll('[data-bs-toggle="tooltip"]')].map(el => new bootstrap.Tooltip(el, { // Initialize tooltips
            trigger: 'hover'
        }));

        initSelectElements(root, filtersToApply);
    }

    initGoTopButton('go-top-button');
});
watch(() => filtersToApply.date_filter, (newVal) => {
    const today = new Date();
    const currentDate = new Date();
    const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);

    if (newVal === 'last_week') {
        const start = new Date(today);
        start.setDate(today.getDate() - 7);
        filtersToApply.start_date = start.toISOString().split('T')[0];
        filtersToApply.finish_date = today.toISOString().split('T')[0];
    } else if (newVal === 'last_month') {
        const start = new Date(today);
        start.setMonth(today.getMonth() - 1);
        filtersToApply.start_date = start.toISOString().split('T')[0];
        filtersToApply.finish_date = today.toISOString().split('T')[0];
    } else if (newVal === 'last_2_months') {
        const start = new Date(today);
        start.setMonth(today.getMonth() - 2);
        filtersToApply.start_date = start.toISOString().split('T')[0];
        filtersToApply.finish_date = today.toISOString().split('T')[0];
    } else if (newVal === 'custom') {
        filtersToApply.start_date = firstDay.toISOString().split('T')[0];
        filtersToApply.finish_date = lastDay.toISOString().split('T')[0];
    } else {
        filtersToApply.start_date = null;
        filtersToApply.finish_date = null;
    }
});

watch(() => props.payments, (newPayments) => {
    if (newPayments != null)
        paymentsData.value = newPayments;
});

watch(
    [() => filtersToApply.start_date, () => filtersToApply.finish_date],
    ([start, finish]) => {
        if (filtersToApply.date_filter !== 'custom') return;

        const today = new Date();
        const sixMonthsAgo = new Date(today);
        sixMonthsAgo.setMonth(today.getMonth() - 6);

        if (start) {
            const startDate = new Date(start);
            if (startDate < sixMonthsAgo) {
                filtersToApply.start_date = sixMonthsAgo.toISOString().split('T')[0];
            }
        }

        if (start && finish) {
            const startDate = new Date(start);
            const finishDate = new Date(finish);
            if (finishDate < startDate) {
                filtersToApply.finish_date = start;
            }
        }
    }
);

// ------------------------
// Declarations
// ------------------------

const root = ref(null);
const context = reactive({
    loadingPayments: false,
    loadingExcel: false,
    disableExcel: false,
})
const paymentsData = ref({
    data: {},
    links: [],
    total: 0,
});

const applyFilters = (page = null) => {
    context.loadingPayments = true;
    context.disableExcel = false;

    router.reload({
        data: {
            ...filtersToApply,
            page: page ?? 1,
        },
        only: ['payments'],
        method: 'post',
        onFinish: () => {
            context.loadingPayments = false;
            filtersToApply.wasSuccessful = true;
        },
    });
}

const showExcelButton = computed(() => {
    return paymentsData.value.total > 0;
});

const excelReport = () => {
    context.loadingExcel = true;

    generateExcelReport('order-marketplace/income/generate-excel', 'income-report.xlsx', filtersToApply, {
        onSuccess: () => {
            context.loadingExcel = false;
            context.disableExcel = true;
        },
        onError: () => {
            context.loadingExcel = false;
        }
    });
};

const clearFilters = () => {
    filtersToApply.client_name = null;
    filtersToApply.client_email = null;
    filtersToApply.order_code = null;
    filtersToApply.store_id = null;
    filtersToApply.product_name = null;
    filtersToApply.start_date = null;
    filtersToApply.finish_date = null;
    filtersToApply.sale_type = null;

    updateSelectElements(root, filtersToApply);
}
</script>

<template>
    <Master title="Reportes de ingresos" cardTitle="Reportes de ingresos">
        <div ref="root" class="row">
            <div class="card card-flush overflow-hidden h-md-100">
                <!--begin::Header-->
                <div class="card-header py-5">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-2 text-gray-900">Ingresos</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-6">Ingresos</span>
                    </h3>
                    <!--end::Title-->
                </div>
                <!--begin::Form-->
                <form @submit.prevent="applyFilters()">
                    <div class="card-body pt-0">
                        <div class="row row-cols-1 row-cols-lg-2 mb-3 fv-row">
                            <div class="col mb-3">
                                <label class="required form-label">Sucursal</label>
                                <select v-model="filtersToApply.store_id" name="store_id"
                                    class="form-select form-control" data-control="select2" data-placeholder="Tienda">
                                    <option value="">Elige una Sucursal</option>
                                    <option v-for="store in stores" :value="store.id">{{ store.name }}</option>
                                </select>
                            </div>
                            <div class="col mb-3">
                                <label class="required form-label">Venta o renta</label>
                                <select v-model="filtersToApply.sale_type" name="sale_type"
                                    class="form-select form-control" data-control="select2"
                                    data-placeholder="Venta o renta" data-hide-search="true">
                                    <option value="">Elige una opción</option>
                                    <option v-for="(name, value) in saleTypes" :value="value">{{ name }}</option>
                                </select>
                            </div>
                            <div class="col">
                                <label class="required form-label">Rango de fechas</label>
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="date_last_week"
                                            value="last_week" v-model="filtersToApply.date_filter">
                                        <label class="form-check-label" for="date_last_week">
                                            Última semana
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="date_last_month"
                                            value="last_month" v-model="filtersToApply.date_filter">
                                        <label class="form-check-label" for="date_last_month">
                                            Último mes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="date_last_2_months"
                                            value="last_2_months" v-model="filtersToApply.date_filter">
                                        <label class="form-check-label" for="date_last_2_months">
                                            Últimos 2 meses
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="date_custom" value="custom"
                                            v-model="filtersToApply.date_filter">
                                        <label class="form-check-label" for="date_custom">
                                            Asignar rango de fecha
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3" v-if="filtersToApply.date_filter === 'custom'">
                                <div class="col-6 mb-3">
                                    <label class="required form-label">Fecha de inicio</label>
                                    <input type="date" class="form-control" v-model="filtersToApply.start_date"
                                        :min="minDate" :max="maxDate">
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="required form-label">Fecha Final</label>
                                    <input type="date" class="form-control" v-model="filtersToApply.finish_date"
                                        :min="filtersToApply.start_date" :max="maxDate">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-header d-flex align-items-center position-relative">
                        <div v-if="showExcelButton" class="mx-4">
                            <a @click="excelReport" target="_blank"
                                :class="['btn btn-success fs-5', { 'disabled': context.loadingExcel || context.disableExcel }]"
                                :aria-disabled="context.loadingExcel || context.disableExcel">
                                <i class="ki-duotone ki-file-down text-gray fs-1"><span class="path1"></span><span
                                        class="path2"></span></i>
                                Exportar a Excel
                            </a>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="card-label fs-5 fw-bold text-gray-900" v-if="context.loadingExcel">Descargando
                                archivo...</span>
                            <div v-if="context.loadingExcel" class="spinner-border spinner-border-md text-dark ms-2"
                                role="status" style="width: 3rem; height: 3rem;">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div class="ms-auto">
                            <div class="d-flex align-items-center menu-item">
                                <button class="btn btn-primary" type="submit">Buscar</button>
                                <button class="btn btn-secondary mx-5" type="button" @click="clearFilters">Limpiar
                                    filtros</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!--end form-->
                <!--begin::Card body-->
                <div v-if="paymentsData.total > 0" class="card-body">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_table_widget_5_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start text-gray-900 fw-bold fs-7 text-uppercase gs-0">
                                <th class="text-start"># Pago</th>
                                <th class="text-start"># Orden</th>
                                <th class="text-name">Sucursal</th>
                                <th class="text-name">Fecha de solicitud</th>
                                <th class="text-name">Monto</th>
                                <th class="text-name">Cliente</th>
                                <th class="text-name">Correo</th>
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-bold text-gray-600">
                            <tr v-for="payment in paymentsData.data" :key="payment.id">
                                <td>
                                    {{ payment.id }}
                                </td>
                                <td>
                                    <a :href="url('order/marketplace/' + payment.order_code)">
                                        {{ payment.order_code }}
                                    </a>
                                </td>
                                <td>{{ payment.store_name }}</td>
                                <td>{{ payment.request_date }}</td>
                                <td>$ {{ payment.amount.toFixed(2) }}</td>
                                <td>{{ payment.client_name }}</td>
                                <td>{{ payment.client_email }}</td>
                            </tr>
                        </tbody>
                        <!--end::Table body-->
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Header-->
                <!--begin::Informational text-->
                <div v-else class="card-body">
                    <div class="col-md-12 text-center h3 my-5">
                        <div v-if="filtersToApply.wasSuccessful" class="col-md-12">
                            Sin resultados
                            <br>
                            Puede ajustar los filtros y buscar de nuevo
                        </div>
                        <div v-else-if="context.loadingPayments" class="col-md-12">
                            Buscando artículos...
                            <div class="mt-5">
                                <span class="loader-dots"></span>
                            </div>
                        </div>
                        <div v-else class="col-md-12">
                            Seleccione algunos filtros y haga click en "Buscar"
                        </div>
                    </div>
                </div>
                <!--end::Informational text-->
            </div>
        </div>
        <div class="d-flex justify-content-center mt-4">
            <Pagination v-if="paymentsData.total > 0" :links="paymentsData.links"
                :onPageClick="(newPage) => applyFilters(newPage)" />
        </div>
        <button id="go-top-button" class="float-button" @click="scrollToTop">↑</button>
    </Master>
</template>

<style>
.marginTittle {
    margin-bottom: 3px;
}

.color-filter {
    display: flex;
    align-items: center;
}

.color-option {
    aspect-ratio: 1 / 1;
    width: 13.66666%;
    margin: 0 1.5%;
    border-radius: 50%;
    cursor: pointer;
    border: 1px solid black;
    margin-top: 5px;
}

.color-options {
    display: flex;
    width: 100%;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-start;
}

.color-option.selected {
    border: 2px solid #333;
    /* Cambia el color del borde cuando se selecciona */
}
</style>