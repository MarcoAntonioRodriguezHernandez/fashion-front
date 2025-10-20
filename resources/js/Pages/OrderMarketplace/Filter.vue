<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import Master from '@layouts/Master.vue';
import Pagination from '@components/Pagination.vue';
import OrderModal from '@components/OrderModal.vue';
import { initSelectElements, updateSelectElements, scrollToTop, initGoTopButton } from '@src/utils.js';
import { generateExcelReport } from '../../src/utils';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    stores: { type: Array, required: true },
    saleTypes: { type: Object, required: true },
    orders: { type: Object, default: null },
});

const filtersToApply = reactive({
    orders: [],
    client_name: null,
    client_email: null,
    order_code: null,
    order_store_id: null,
    product_name: null,
    order_start_date: null,
    order_finish_date: null,
    sale_type: 0,
});

onMounted(() => {
    const currentDate = new Date();
    const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);

    filtersToApply.order_start_date = firstDay.toISOString().split('T')[0];
    filtersToApply.order_finish_date = lastDay.toISOString().split('T')[0];

    if (root.value) {
        [...root.value.querySelectorAll('[data-bs-toggle="tooltip"]')].map(el => new bootstrap.Tooltip(el, { // Initialize tooltips
            trigger: 'hover'
        }));

        initSelectElements(root, filtersToApply);
    }

    initGoTopButton('go-top-button');
});

watch(() => props.orders, (newOrders) => {
    if (newOrders != null)
        ordersData.value = newOrders;
});

// ------------------------
// Declarations
// ------------------------

const root = ref(null);
const itemOrderModal = ref(null);
const context = reactive({
    loadingOrders: false,
    loadingExcel: false,
    wasSuccessful: false,
    disableExcel: false,
})
const ordersData = ref({
    data: {},
    links: [],
    total: 0,
});
const orderInfo = ref({
    data: {},
});

const applyFilters = (page = null) => {
    context.loadingOrders = true;
    context.disableExcel = false;

    router.reload({
        data: {
            ...filtersToApply,
            page: page ?? 1,
        },
        only: ['orders'],
        method: 'post',
        onFinish: () => {
            context.loadingOrders = false;
            filtersToApply.wasSuccessful = true;
        },
    });
}

const showExcelButton = computed(() => {
    return ordersData.value.total > 0;
});

const showOrderDetails = (order) => {
    orderInfo.value = order;
};

const excelReport = () => {
    context.loadingExcel = true;
    const urlGenerate = 'order/report/generate-excel';
    const fileName = 'orders.xlsx';

    generateExcelReport(urlGenerate, fileName, filtersToApply, {
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
    filtersToApply.order_store_id = null;
    filtersToApply.product_name = null;
    filtersToApply.order_start_date = null;
    filtersToApply.order_finish_date = null;
    filtersToApply.sale_type = null;

    updateSelectElements(root, filtersToApply);
}
</script>

<template>
    <Master title="Búsqueda de órdenes" cardTitle="Búsqueda de órdenes">
        <div ref="root" class="row">
            <div class="card card-flush overflow-hidden h-md-100">
                <!--begin::Header-->
                <div class="card-header py-5">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-2 text-gray-900">Órdenes</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-6">Órdenes</span>
                    </h3>
                    <!--end::Title-->
                </div>
                <!--begin::Form-->
                <form @submit.prevent="applyFilters()">
                    <div class="card-body pt-0">
                        <div class="row mb-3 fv-row">
                            <div class="col">
                                <label class="required form-label">Nombre</label>
                                <input type="text" class="form-control" v-model="filtersToApply.client_name" placeholder="Nombre">
                            </div>
                            <div class="col">
                                <label class="required form-label">Correo</label>
                                <input type="text" class="form-control" v-model="filtersToApply.client_email" placeholder="Correo">
                            </div>
                            <div class="col">
                                <label class="required form-label">Número de Orden</label>
                                <input type="text" class="form-control" v-model="filtersToApply.order_code" placeholder="Número de Orden">
                            </div>
                        </div>
                        <div class="row mb-3 fv-row">
                            <div class="col">
                                <label class="required form-label">Sucursal</label>
                                <select v-model="filtersToApply.order_store_id" name="order_store_id" class="form-select form-control" data-control="select2" data-placeholder="Tienda">
                                    <option value="">Elige una Sucursal</option>
                                    <option v-for="store in stores" :value="store.id">{{ store.name }}</option>
                                </select>
                            </div>
                            <div class="col">
                                <label class="required form-label">Producto</label>
                                <input type="text" class="form-control" v-model="filtersToApply.product_name" placeholder="Nombre del producto">
                            </div>
                        </div>
                        <div class="row mb-3 fv-row">
                            <div class="col">
                                <label class="required form-label">Fecha de inicio</label>
                                <input type="date" class="form-control" v-model="filtersToApply.order_start_date" placeholder="Número de Orden">
                            </div>
                            <div class="col">
                                <label class="required form-label">Fecha Final</label>
                                <input type="date" class="form-control" v-model="filtersToApply.order_finish_date" placeholder="Número de Orden">
                            </div>
                            <div class="col">
                                <label class="required form-label">Venta o renta</label>
                                <select v-model="filtersToApply.sale_type" name="sale_type" class="form-select form-control" data-control="select2" data-placeholder="Venta o renta" data-hide-search="true">
                                    <option value="">Elige una opción</option>
                                    <option v-for="(name, value) in saleTypes" :value="value">{{ name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-header d-flex align-items-center position-relative">
                        <div v-if="showExcelButton" class="mx-4">
                            <a @click="excelReport" target="_blank" :class="['btn btn-success fs-5', { 'disabled': context.loadingExcel || context.disableExcel }]" :aria-disabled="context.loadingExcel || context.disableExcel">
                                <i class="ki-duotone ki-file-down text-gray fs-1"><span class="path1"></span><span class="path2"></span></i>
                                Exportar a Excel
                            </a>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="card-label fs-5 fw-bold text-gray-900" v-if="context.loadingExcel">Descargando
                                archivo...</span>
                            <div v-if="context.loadingExcel" class="spinner-border spinner-border-md text-dark ms-2" role="status" style="width: 3rem; height: 3rem;">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div class="ms-auto">
                            <div class="d-flex align-items-center menu-item">
                                <button class="btn btn-primary" type="submit">Buscar</button>
                                <button class="btn btn-secondary mx-5" type="button" @click="clearFilters">Limpiar filtros</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!--end form-->
                <!--begin::Card body-->
                <div v-if="ordersData.total > 0" class="card-body">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_table_widget_5_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start text-gray-900 fw-bold fs-7 text-uppercase gs-0">
                                <th class="text-start"># Orden</th>
                                <th class="text-name">Sucursal</th>
                                <th class="text-name">Fecha de solicitud</th>
                                <th class="text-name">Cliente</th>
                                <th class="text-name">Empleado</th>
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-bold text-gray-600">
                            <tr v-for="order in ordersData.data" :key="order.id">
                                <td>
                                    <button @click="showOrderDetails(order)" data-bs-toggle="modal" data-bs-target="#orderModal" class="text-primary fw-bold btn btn-sm p-0 bg-transparent border-0">
                                        {{ order.code }}
                                    </button>
                                </td>
                                <td>{{ order.store_name }}</td>
                                <td>{{ order.request_date }}</td>
                                <td>
                                    <div class="mb-1 text-gray-900 fw-bold">{{ order.client_name }}</div>
                                    <div class="fs-7 text-muted fw-bold">{{ order.client_email }}</div>
                                </td>
                                <td>
                                    <div class="mb-1 text-gray-900 fw-bold">{{ order.employee_name }}</div>
                                    <div class="fs-7 text-muted fw-bold">{{ order.employee_email }}</div>
                                </td>
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
                        <div v-else-if="context.loadingOrders" class="col-md-12">
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
            <Pagination v-if="ordersData.total > 0" :links="ordersData.links" :onPageClick="(newPage) => applyFilters(newPage)" />
        </div>
        <button id="go-top-button" class="float-button" @click="scrollToTop">↑</button>
    </Master>
    <OrderModal ref="itemOrderModal" :order="orderInfo" />
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