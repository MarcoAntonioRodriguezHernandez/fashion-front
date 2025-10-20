<script setup>
import { onMounted, reactive, ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import Master from "@layouts/Master.vue";
import Pagination from "@components/Pagination.vue";
import {
    initSelectElements,
    updateSelectElements,
    generateExcelReport,
} from "@src/utils.js";

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    stores: { type: Array, required: true },
    suppliesResult: { type: Object, default: () => null },
});

onMounted(() => {
    if (root.value) {
        [...root.value.querySelectorAll('[data-bs-toggle="tooltip"]')].map(
            (el) =>
                new bootstrap.Tooltip(el, {
                    trigger: "hover",
                })
        );

        initSelectElements(root, filtersToApply);
    }
});

watch(
    () => props.suppliesResult,
    (newSupplies) => {
        if (newSupplies != null) suppliesData.value = newSupplies;
    }
);

// ------------------------
// Declarations
// ------------------------

const root = ref(null);
const context = reactive({
    loadingSupplies: false,
    loadingDocument: false,
    disableDocument: true,
});
const suppliesData = ref({
    data: {},
    links: [],
    total: 0,
});
const filtersToApply = reactive({
    origin_id: null,
    destination_id: null,
    active_only: 1,
});

const submitApplyFilters = (page = null) => {
    context.disableDocument = false;

    router.reload({
        data: {
            ...filtersToApply,
            page: page ?? 1,
        },
        only: ["suppliesResult"],
        method: "post",
        onStart: () => {
            context.loadingSupplies = true;
        },
        onFinish: () => {
            context.loadingSupplies = false;
        },
    });
};

const clearFilters = () => {
    filtersToApply.origin_id = null;
    filtersToApply.destination_id = null;
    filtersToApply.active_only = 1;

    updateSelectElements(root, filtersToApply);
};

const generateDocument = () => {
    context.loadingDocument = true;

    generateExcelReport(
        "supply/report/generate",
        "supply_report.xlsx",
        filtersToApply,
        {
            onSuccess: () => {
                context.loadingDocument = false;
                context.disableDocument = true;
            },
            onError: () => {
                context.disableDocument = false;
                context.loadingDocument = false;
            },
        }
    );
};
</script>

<template>
    <Master
        title="Búsqueda de Distribuciones"
        cardTitle="Búsqueda de Distribuciones"
    >
        <div ref="root" class="card mb-6">
            <div class="card-body pt-9 pb-0">
                <div id="asideFilter">
                    <form @submit.prevent="submitApplyFilters()">
                        <!--begin::Menu supply-->
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4">
                            <div class="col">
                                <div class="menu-supply mb-6">
                                    <select
                                        v-model="filtersToApply.origin_id"
                                        name="origin_id"
                                        class="form-select form-control"
                                        data-control="select2"
                                        data-placeholder="Origen"
                                    >
                                        <option value="">
                                            Seleccione un origen
                                        </option>
                                        <option
                                            v-for="store in stores"
                                            :value="store.id"
                                        >
                                            {{ store.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="menu-supply mb-6">
                                    <select
                                        v-model="filtersToApply.destination_id"
                                        name="destination_id"
                                        class="form-select form-control"
                                        data-control="select2"
                                        data-placeholder="Destino"
                                    >
                                        <option value="">
                                            Seleccione un destino
                                        </option>
                                        <option
                                            v-for="store in stores"
                                            :value="store.id"
                                        >
                                            {{ store.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="menu-supply mb-6">
                                    <select
                                        v-model="filtersToApply.active_only"
                                        name="active_only"
                                        class="form-select form-control"
                                        data-control="select2"
                                        data-hide-search="true"
                                        data-placeholder="Sólo activos"
                                    >
                                        <option :value="1">Sólo activos</option>
                                        <option :value="0">Todos</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div
                                    class="d-flex justify-content-center menu-supply mb-3"
                                >
                                    <button
                                        class="btn btn-primary"
                                        type="submit"
                                    >
                                        Buscar
                                    </button>
                                    <button
                                        class="btn btn-secondary mx-5"
                                        type="button"
                                        @click="clearFilters"
                                    >
                                        Limpiar filtros
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!--end::Menu supply-->
                    </form>
                </div>
            </div>
        </div>

        <!--begin::Export button-->
        <div class="d-flex pb-8">
            <div class="d-flex gap-5 mx-4">
                <a
                    @click="generateDocument"
                    target="_blank"
                    :class="[
                        'btn btn-success fs-5',
                        {
                            disabled:
                                context.loadingDocument ||
                                context.disableDocument,
                        },
                    ]"
                    :aria-disabled="
                        context.loadingDocument || context.disableDocument
                    "
                >
                    <i class="ki-duotone ki-file-down text-gray fs-1"
                        ><span class="path1"></span><span class="path2"></span
                    ></i>
                    Exportar Documento
                </a>
            </div>
            <div class="d-flex align-items-center">
                <span
                    class="card-label fs-5 fw-bold text-gray-900"
                    v-if="context.loadingDocument"
                    >Descargando archivo...</span
                >
                <div
                    v-if="context.loadingDocument"
                    class="spinner-border spinner-border-md text-dark ms-2"
                    role="status"
                    style="width: 3rem; height: 3rem"
                >
                    <span class="visually-oculto">Loading...</span>
                </div>
            </div>
        </div>
        <!--end::Export button-->

        <!--begin::Element list-->
        <div v-if="suppliesData.total > 0">
            <div class="card card-flush">
                <div class="card-body pt-3">
                    <div class="table-responsive">
                        <table
                            class="table table-row-bordered table-row-dashed gy-4 align-middle"
                        >
                            <thead
                                class="fs-7 text-gray-500 text-uppercase fw-bold"
                            >
                                <tr>
                                    <th class="text-center">Código</th>
                                    <th class="text-center">Origen</th>
                                    <th class="text-center">Destino</th>
                                    <th class="text-center">Artículos</th>
                                    <th class="text-center">Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="supply in suppliesData.data"
                                    :key="supply.transfer_id"
                                >
                                    <td class="fw-bold">
                                        <a :href="url('supply/' + supply.id)">
                                            <span class="fw-bold">{{
                                                supply.code
                                            }}</span>
                                        </a>
                                    </td>
                                    <td>{{ supply.origin.name }}</td>
                                    <td>{{ supply.destination.name }}</td>
                                    <td>{{ supply.items_count }} artículos</td>

                                    <!-- Estatus: viene del trait como { name, color } -->
                                    <td class="text-center">
                                        <span
                                            :class="[
                                                'badge',
                                                'text-bg-' +
                                                    (supply.status?.color ||
                                                        'secondary'),
                                            ]"
                                        >
                                            {{ supply.status?.name || "—" }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Element list-->

        <!--begin::Informational text-->
        <div v-else class="card border-0 w-100">
            <div class="">
                <div class="card-body">
                    <div class="col-md-12 text-center h3 my-5">
                        <div
                            v-if="filtersToApply.wasSuccessful"
                            class="col-md-12"
                        >
                            Sin resultados
                            <br />
                            Puede ajustar los filtros y buscar de nuevo
                        </div>
                        <div
                            v-else-if="context.loadingSupplies"
                            class="d-flex flex-column align-items-center"
                        >
                            Buscando distribuciones...
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
        </div>
        <!--end::Informational text-->

        <Pagination
            :links="suppliesData.links"
            :onPageClick="submitApplyFilters"
        />
    </Master>
</template>
