<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import Master from '@layouts/Master.vue';
import Pagination from '@components/Pagination.vue';
import ItemCard from '@components/ItemCard.vue';
import ColorSelection from '@components/ColorSelection.vue';
import { generateExcelReport, initSelectElements, updateSelectElements, scrollToTop, initGoTopButton } from '@src/utils';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    stores: { type: Array, required: true },
    categories: { type: Array, required: true },
    designers: { type: Array, required: true },
    sizes: { type: Array, required: true },
    colors: { type: Object, required: true },
    characteristics: { type: Array, required: true },
    conditions: { type: Object, required: true },
    pricingSchemes: { type: Array, required: true },
    itemsResult: { type: Object, default: () => null },
});

const filtersToApply = reactive({
    items: [],
    category: null,
    name: null,
    barcode: null,
    store: null,
    designer: null,
    sizes: [],
    colors: [],
    characteristics: [],
    condition: null,
});

onMounted(() => {
    if (root.value) {
        [...root.value.querySelectorAll('[data-bs-toggle="tooltip"]')].map(el => new bootstrap.Tooltip(el, { // Initialize tooltips
            trigger: 'hover'
        }));

        initSelectElements(root, filtersToApply);
    }

    initGoTopButton('go-top-button');
});

watch(() => props.itemsResult, (newItems) => {
    if (newItems != null)
        itemsData.value = newItems;
});

// ------------------------
// Declarations
// ------------------------

const root = ref(null);
const context = reactive({
    loadingItems: false,
    loadingExcel: false,
    wasSuccessful: false,
    isTableView: false,
    disableExcel: false,
})
const itemsData = ref({
    data: {},
    links: [],
    total: 0,
});

const applyFilters = (page = null) => {
    context.disableExcel = false;

    router.reload({
        data: {
            ...filtersToApply,
            page: page ?? 1,
        },
        only: ['itemsResult'],
        method: 'post',
        onStart: () => {
            context.loadingItems = true;
        },
        onFinish: () => {
            context.loadingItems = false;
            filtersToApply.wasSuccessful = true;
        },
    });
}

const showExcelButton = computed(() => {
    return itemsData.value.total > 0;
});

const excelReport = () => {
    context.loadingExcel = true;
    const urlGenerate = 'item/report/generate-excel';
    const fileName = 'inventory.xlsx';

    generateExcelReport(urlGenerate, fileName, filtersToApply, {
        onSuccess: () => {
            context.loadingExcel = false;
            context.disableExcel = true;
        },
        onError: () => {
            context.disableExcel = false;
            context.loadingExcel = false;
        }
    });
};

const clearFilters = () => {
    filtersToApply.name = null;
    filtersToApply.category = null;
    filtersToApply.barcode = null;
    filtersToApply.store = null;
    filtersToApply.designer = null;
    filtersToApply.sizes = [];
    filtersToApply.colors = [];
    filtersToApply.characteristics = [];
    filtersToApply.condition = null;

    updateSelectElements(root, filtersToApply);
}
</script>

<template>
    <Master title="Reporte de inventario" cardTitle="Reporte de inventario">
        <div ref="root" class="card mb-6">
            <div class="card-body pt-9 pb-0">
                <div id="asideFilter">
                    <form @submit.prevent="applyFilters()">
                        <!--begin::Menu item-->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="menu-item mb-6">
                                    <select v-model="filtersToApply.store" name="store" class="form-select form-control"
                                        data-control="select2" data-placeholder="Tienda">
                                        <option value="">Seleccione una tienda</option>
                                        <option v-for="store in stores" :value="store.id">{{ store.name }}</option>
                                    </select>
                                </div>
                                <div class="menu-item mb-6">
                                    <!--begin::Inbox-->
                                    <input v-model="filtersToApply.name" name="search" type="text" class="form-control"
                                        placeholder="Buscar por nombre">
                                    <!--end::Inbox-->
                                </div>
                                <div class="menu-item mb-6">
                                    <select v-model="filtersToApply.category" name="category"
                                        class="form-select form-control" data-control="select2"
                                        data-placeholder="Categoria">
                                        <option value="">Seleccione una categoría</option>
                                        <option v-for="category in categories" :key="category.id" :value="category.id">
                                            {{ category.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <!--begin::Menu item-->
                                <div class="menu-item mb-6">
                                    <select v-model="filtersToApply.characteristics" name="characteristics"
                                        class="form-select form-control" data-control="select2"
                                        data-placeholder="Elige una característica" multiple>
                                        <option value="">Elige una característica</option>
                                        <optgroup v-for="group in characteristics" :label="group.parent.name">
                                            <option v-for="child in group.children" :value="child.id">{{ child.name }}
                                            </option>
                                        </optgroup>
                                    </select>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item mb-6">
                                    <select v-model="filtersToApply.sizes" name="sizes" class="form-select form-control"
                                        data-control="select2" data-placeholder="Elige una talla" multiple>
                                        <option value="">Elige una talla</option>
                                        <option v-for="size in sizes" :value="size.id">{{ size.full_name }}</option>
                                    </select>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item mb-3">
                                    <select v-model="filtersToApply.designer" name="designer" class="form-select form-control" data-control="select2" data-placeholder="Elige una marca">
                                        <option value="">Elige una marca</option>
                                        <option v-for="designer in designers" :value="designer.id">{{ designer.name }}
                                        </option>
                                    </select>
                                </div>
                                <!--end::Menu item-->
                            </div>
                            <div class="col-md-3">
                                <div class="menu-item mb-6">
                                    <!--begin::Inbox-->
                                    <input v-model="filtersToApply.barcode" name="barcode" type="text" class="form-control" 
                                        placeholder="Buscar por código CM">
                                    <!--end::Inbox-->
                                </div>
                                <!--begin::Menu item-->
                                <div class="menu-item mb-6">
                                    <select v-model="filtersToApply.condition" name="condition" class="form-select form-control"
                                        data-control="select2" data-placeholder="Elige un estado">
                                        <option value="">Elige un estado</option>
                                        <option v-for="name, value in conditions" :value="value">{{ name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <!--begin::Menu item-->
                                <div class="menu-item mb-3">
                                    <ColorSelection v-model="filtersToApply.colors" :colors="colors" />
                                </div>
                                <!--end::Menu item-->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end menu-item mb-3">
                                    <button class="btn btn-primary" type="submit">Buscar</button>
                                    <button class="btn btn-secondary mx-5" type="button" @click="clearFilters">Limpiar
                                        filtros</button>
                                </div>
                            </div>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <!--end::Menu item-->
                    </form>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column">
            <div class="col-xxl-12">
                <!--begin::Element list-->
                <div class="d-flex">
                    <div v-if="showExcelButton" class="d-flex gap-5 mx-4">
                        <a @click="excelReport" target="_blank" 
                            :class="['btn btn-success fs-5', { 'disabled': context.loadingExcel || context.disableExcel }]" 
                            :aria-disabled="context.loadingExcel || context.disableExcel">
                            <i class="ki-duotone ki-file-down text-gray fs-1"><span class="path1"></span><span class="path2"></span></i>
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
                </div>
                <div class="d-flex flex-wrap flex-stack pt-10 pb-8">
                    <!--begin::Heading-->
                    <h3 class="fw-bold my-2">
                        {{ itemsData.total }} artículos encontrados
                        <span class="fs-6 text-gray-500 fw-semibold ms-1">según su busqueda ↓</span>
                    </h3>
                    <!--end::Heading-->
                    <!--begin::Controls-->
                    <div class="d-flex flex-wrap my-1">
                        <!--begin::Tab nav-->
                        <ul class="nav nav-pills me-5" role="tablist">
                            <li class="nav-item m-0" role="presentation">
                                <a class="btn btn-sm btn-icon btn-light btn-color-muted btn-active-primary me-3"
                                    :class="{ active: !context.isTableView }" @click="context.isTableView = false" aria-selected="true"
                                    role="tab">
                                    <i class="ki-outline ki-element-plus fs-1"></i>
                                </a>
                            </li>
                            <li class="nav-item m-0" role="presentation">
                                <a class="btn btn-sm btn-icon btn-light btn-color-muted btn-active-primary"
                                    :class="{ active: context.isTableView }" @click="context.isTableView = true" aria-selected="false"
                                    tabindex="-1" role="tab">
                                    <i class="ki-outline ki-row-horizontal fs-2"></i>
                                </a>
                            </li>

                        </ul>
                        <!--end::Tab nav-->
                    </div>
                    <!--end::Controls-->
                </div>
                <!--begin::Element list-->
                <div v-if="itemsData.total > 0">
                    <div v-if="context.isTableView">
                        <div class="card card-flush">
                            <div class="card-body pt-3">
                                <div class="table-responsive">
                                    <table class="table table-row-bordered table-row-dashed gy-4 align-middle fw-bold">
                                        <thead class="fs-7 text-gray-500 text-uppercase">
                                            <tr>
                                                <th class="text-center">ID</th>
                                                <th class="text-center">Categoría</th>
                                                <th class="text-center">Nombre del Producto</th>
                                                <th class="text-center">Color</th>
                                                <th class="text-center">Código de barras</th>
                                                <th class="text-center">Diseñador</th>
                                                <th class="text-center">Talla</th>
                                                <th class="text-center">Tienda</th>
                                                <th class="text-center">Renta</th>
                                                <th class="text-center">Venta</th>
                                                <th class="text-center">Venta Completo</th>
                                                <th class="text-center">Condición</th>
                                                <th class="text-center">Número de Rentas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="item in itemsData.data" :key="item.id"
                                                :class="{ 'selected-item-choose': filtersToApply.items.includes(item.id) }">

                                                <td>{{ item.id }}</td>
                                                <td class="text-center">{{ item.category_name }}</td>
                                                <td class="text-center">{{ item.product_name }}</td>
                                                <td>{{ item.color_name }}</td>
                                                <td class="text-center">{{ item.barcode }}</td>
                                                <td class="text-center">{{ item.designer_name }}</td>
                                                <td class="text-center">{{ item.size_name }}</td>
                                                <td class="text-center">{{ item.store_name }}</td>
                                                <td class="text-center">$ {{ item.prices_rent['4'].toFixed(2) }}</td>
                                                <td class="text-center">$ {{ item.price_sale.toFixed(2) }}</td>
                                                <td class="text-center">$ {{ item.full_price.toFixed(2) }}</td>
                                                <td class="text-center">{{ item.condition }}</td>
                                                <td class="text-center">{{ item.amount_rents }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="col-12 row row-cols-2 row-cols-md-2 row-cols-xl-2 row-cols-xxl-4">
                        <!--begin::Wrapper-->
                        <div v-for="item in itemsData.data" class="col mb-3 pb-3" :key="item.id">
                            <ItemCard :checkbox="false" :item="item"/>
                        </div>
                        <!--end::Wrapper-->
                    </div>
                </div>
                <!--end::Element list-->
                <!--begin::Informational text-->
                <div v-else class="card border-0 w-100">
                    <!--begin::Body-->
                    <div class="">
                        <div class="card-body">
                            <div class="col-md-12 text-center h3 my-5">
                                <div v-if="filtersToApply.wasSuccessful" class="col-md-12">
                                    Sin resultados
                                    <br>
                                    Puede ajustar los filtros y buscar de nuevo
                                </div>
                                <div v-else-if="context.loadingItems"
                                    class="d-flex flex-column align-items-center">
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
                    </div>
                    <!--end: Card Body-->
                </div>
                <!--end::Informational text-->
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
            <Pagination v-if="itemsData.total > 0" :links="itemsData.links" :onPageClick="(newPage) => applyFilters(newPage)" />
        </div>
        <button id="go-top-button" class="float-button" @click="scrollToTop">↑</button>
    </Master>
</template>

<style>
.shadow-green {
    box-shadow: rgba(6, 24, 44, 0.4) 0px 0px 0px 2px, rgba(6, 24, 44, 0.65) 0px 4px 6px -1px, rgba(255, 255, 255, 0.08) 0px 1px 0px inset;
}

.selected-item-choose {
    background-color: #f0f0f0;
}

.float-button {
    width: 50px;
    height: 50px;
    background-color: #000000;
    color: #fff;
    border: none;
    border-radius: 50%;
    font-size: 24px;
    text-align: center;
    position: fixed;
    bottom: 20px;
    right: 20px;
    cursor: pointer;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
}

.float-button:hover {
    background-color: #5b5b5b;
    color: #000000;
}

.state-square {
    width: 1em;
    height: 2em;
}
</style>
