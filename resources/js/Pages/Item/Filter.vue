<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import Master from '@layouts/Master.vue';
import WithPermission from '@components/WithPermission.vue';
import ItemCard from '@components/ItemCard.vue';
import Pagination from '@components/Pagination.vue';
import InfoModal from './Partials/InfoModal.vue';
import MassModal from './Partials/MassModal.vue';
import ColorSelection from '@components/ColorSelection.vue';
import { initSelectElements, updateSelectElements, scrollToTop, initGoTopButton, encodeShortenedArray, arrayDifference, arrayUnion, generateExcelReport } from '@src/utils.js';
const contextExcel = reactive({
    loading: false,
    disable: false,
});

const showExcelButton = computed(() => {
    return String(selectedFilters.condition) === '6' && itemsData.value.total > 0;
});

const exportExcel = () => {
    contextExcel.loading = true;
    contextExcel.disable = true;
    const urlGenerate = 'item/report/generate-excel';
    const fileName = 'inventory.xlsx';
    let payload;
    if (selectedItems.values.length > 0) {
        payload = { items: selectedItems.values.map(Number) };
    } else {
        payload = { ...selectedFilters };
    }
    generateExcelReport(urlGenerate, fileName, payload, {
        onSuccess: () => {
            contextExcel.loading = false;
            contextExcel.disable = false;
        },
        onError: () => {
            contextExcel.loading = false;
            contextExcel.disable = false;
        }
    });
};
import { default as Auth, PermissionTypes, ModuleAliases } from '@src/Auth.js';

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
    integrities: { type: Object, required: true },
    statuses: { type: Object, required: true },
    pricingSchemes: { type: Array, required: true },
    items: { type: Object, default: () => null },
    itemInfo: { type: Object, default: () => null },
    employees: { type: Array, required: true },
    usedColorIds: { type: Array, required: false, default: () => [] },
});
const isAsideVisible = ref(true);
const isMinimized = ref(false);

const toggleAside = () => {
    isAsideVisible.value = !isAsideVisible.value;
    toggleSidebarMinimized();
};

const toggleSidebarMinimized = () => {
    isMinimized.value = !isMinimized.value;
};

const toggleSidebarFilter = () => {
    if (typeof KTApp !== 'undefined' && KTApp.toggleSidebar) {
        KTApp.toggleSidebar('asidefilter');
    } else {
        console.error('KTApp no está definido o no tiene el método toggleSidebar.');
    }
};


const iconClass = computed(() => {
    return isMinimized.value
        ? "ki-outline ki-arrow-right fs-1 rotate-180"
        : "ki-outline ki-arrow-left fs-1";
});

const sidebarClass = computed(() => {
    return isMinimized.value
        ? "row-cols-xl-5 row-cols-xxl-5"
        : "row-cols-xl-3 row-cols-xxl-3";
});
const buildSelectionPayload = () => {
    return selectedItems.selectAllResults
        ? { select_all: true, filters: { ...selectedFilters } }
        : { items: selectedItems.values };
};
const updateConditionAndStatusSubmit = (formData) => {
    const data = { ...formData, ...buildSelectionPayload() };

    router.post(url('/item/mass/condition-status'), data, {
        onSuccess: () => {
            itemMassModal.value.onUpdated();
            itemMassModal.value.hide();
        },
        onFinish: () => {
            selectedItems.selectAllResults = false;
            selectedItems.values = [];
        },
    });
};
watch(() => props.items, (newItems) => {
    if (newItems != null) {
        itemsData.value = newItems;
    }
});
onMounted(() => {
    if (root.value) {
        [...root.value.querySelectorAll('[data-bs-toggle="tooltip"]')].map(el => new bootstrap.Tooltip(el, {
            trigger: 'hover'
        }));

        initSelectElements(root, selectedFilters);
    }

    initGoTopButton('go-top-button');
});

watch(() => props.items, (newItems) => {
    if (newItems != null)
        itemsData.value = newItems;
});

// ------------------------
// Declarations
// ------------------------

const root = ref(null);
const itemInfoModal = ref(null);
const itemMassModal = ref(null);
const context = reactive({
    onlySelected: false,
    loading: false,
    isTableView: false,
    showAll: false,
    page: 1,
    perPage: 15,
});

const selectedItems = reactive({
    values: [],
    selectAllResults: false,
});

const selectedFilters = reactive({
    category: null,
    name: null,
    barcode: null,
    store: null,
    designer: null,
    sizes: [],
    colors: [],
    characteristics: [],
    condition: null,
    integrities: null,
    status: null,
    wasSuccessful: false,
});

const itemsData = ref({
    data: {},
    links: [],
    total: 0,
});

const showAllItems = () => {
    resetSelection();
    context.showAll = !context.showAll;
    context.onlySelected = false;
    submitApplyFilters(false, 1, context.showAll ? itemsData.value.total : 15);
};

const resetSelection = () => {
    selectedItems.selectAllResults = false;
    selectedItems.values = [];
};

const submitApplyFilters = (onlySelected = null, page = null, perPage = null) => {
    context.onlySelected = onlySelected ?? context.onlySelected;
    context.page = page ?? 1;
    context.perPage = perPage ?? 15;
    context.showAll = context.perPage === itemsData.value.total;

    router.reload({
        data: {
            ...(context.onlySelected ? selectedItems : selectedFilters),
            page: context.page,
            perPage: context.perPage,
        },
        only: ['items', 'colors', 'usedColorIds'],
        method: 'post',
        preserveState: true,
        preserveScroll: true,
        onStart: () => { context.loading = true; },
        onFinish: () => {
            context.loading = false;
            selectedFilters.wasSuccessful = itemsData.value && itemsData.value.total === 0;
        },
    });
};

const visibleCount = computed(() => {
    const total = itemsData.value?.total ?? 0;
    if (!total) return 0;
    const upto = context.page * context.perPage;
    return Math.min(upto, total);
});

const printItemCards = () => {
    if (selectedItems.selectAllResults) {
        router.post(url('/item/card/print'), {
            select_all: true,
            filters: { ...selectedFilters },
        }, {
            preserveScroll: true,
            preserveState: true,
            onFinish: () => {
                selectedItems.selectAllResults = false;
                selectedItems.values = [];
            },
        });
        return;
    }

    router.get(url('/item/card/print'), {
        data: encodeShortenedArray(selectedItems.values),
    }, {
        preserveScroll: true,
        preserveState: true,
        onFinish: () => {
            selectedItems.values = [];
        },
    });
};

const submitGetItemInfo = (itemId) => {
    if (!Auth.hasAnyPermission(ModuleAliases.ITEM, PermissionTypes.UPDATE))
        return;

    router.reload({
        data: {
            itemId: itemId,
        },
        only: ['itemInfo'],
        method: 'post',
        onStart: () => {
            context.loading = true;
        },
        onSuccess: (page) => {
            itemInfoModal.value.showWithInput(page.props.itemInfo);
        },
        onFinish: () => {
            context.loading = false;
        },
    });
};

const showItemMassModal = (itemList) => {
    const filtersSnapshot = { ...selectedFilters };
    itemMassModal.value.showWithItems(
        selectedItems.selectAllResults
            ? { ids: itemList, selectAll: true, totalResults: itemsData.value?.total || 0, filters: filtersSnapshot }
            : { ids: itemList, selectAll: false, totalResults: itemList.length }
    );
};


const displayedItems = computed(() => {
    return Object.values(itemsData.value.data);
});

const allItemsSelected = computed(() => {
    const total = itemsData.value?.total || 0;
    if (total === 0) return false;
    return selectedItems.selectAllResults || selectedItems.values.length === total;
});

const allDisplayedItemsSelected = computed(() => {
    return arrayDifference(displayedItems.value.map((i) => i.id), selectedItems.values).length == 0;
});

const hasAnySelection = computed(() => {
    return selectedItems.selectAllResults || selectedItems.values.length > 0;
});

const selectedCount = computed(() => {
    return selectedItems.selectAllResults ? (itemsData.value?.total || 0)
        : selectedItems.values.length;
});
const colorSelectionRef = ref(null);

const toggleAllItems = () => {
    if (!selectedItems.selectAllResults) {
        selectedItems.selectAllResults = true;
        const displayedIds = displayedItems.value.map(i => i.id);
        selectedItems.values = arrayUnion([], displayedIds);
        return;
    }
    selectedItems.selectAllResults = false;
    selectedItems.values = [];
};

const clearFilters = () => {
    selectedFilters.name = null;
    selectedFilters.category = null;
    selectedFilters.barcode = null;
    selectedFilters.store = null;
    selectedFilters.designer = null;
    selectedFilters.sizes = [];
    selectedFilters.colors = [];
    selectedFilters.characteristics = [];
    selectedFilters.condition = null;
    selectedFilters.integrities = null;
    selectedFilters.status = null;
    selectedFilters.wasSuccessful = false;
    colorSelectionRef.value?.clearSelection();

    updateSelectElements(root, selectedFilters);

    itemsData.value = { data: {}, links: [], total: 0 };

    // Auto-submit filters (AJAX) when user types a product name (debounced).
    let nameDebounceTimer = null;
    watch(() => selectedFilters.name, (val) => {
        clearTimeout(nameDebounceTimer);
        const name = val ? String(val).trim() : '';
        // require at least 3 chars to avoid excessive requests
        if (name.length < 3) return;
        nameDebounceTimer = setTimeout(() => {
            // submit and request items + colors from backend
            submitApplyFilters(false, 1);
        }, 500);
    });
};
</script>

<template>
    <Master title="Búsqueda de Artículos" cardTitle="Búsqueda de Artículos">
        <div ref="root" class="d-flex flex-column flex-xl-row">
            <div id="asideFilter" class="flex-row-auto me-xl-9 mb-10 mb-xl-0 w-xl-300px w-xxl-350px aside-filter"
                :class="{ hidden: !isAsideVisible }">
                <div class="card card-flush overflow-y-auto mb-0" style="height: 80vh; position: sticky; top: 10em;">
                    <!--begin::Aside content-->
                    <div class="card-body">
                        <!--begin::Menu-->
                        <form @submit.prevent="submitApplyFilters(false)"
                            class="menu menu-column menu-rounded menu-state-bg menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary">
                            <!--begin::Menu item-->
                            <div class="menu-item mb-3">
                                <button type="button" @click="toggleAside"
                                    class="apps-sidebar-toggle btn btn-sm btn-icon bg-light btn-color-gray-700 btn-active-color-primary d-none d-lg-flex rotate m-1 mt-5"
                                    data-kt-toggle="true" data-kt-toggle-state="active"
                                    data-kt-toggle-target="asideFilter" data-kt-toggle-name="app-sidebar-minimize">
                                    <i :class="iconClass"></i>
                                </button>
                                <!--begin::Inbox-->
                                <input v-model="selectedFilters.name" name="search" type="text"
                                    class="form-control mt-9" placeholder="Buscar por nombre">
                                <!--end::Inbox-->
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item mb-3">
                                <!--begin::Inbox-->
                                <input v-model="selectedFilters.barcode" name="barcode" type="text" class="form-control"
                                    placeholder="Buscar por código CM">
                                <!--end::Inbox-->
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item mb-3">
                                <select v-model="selectedFilters.store" name="store" class="form-select form-control"
                                    data-control="select2" data-placeholder="Tienda">
                                    <option value="">Seleccione una tienda</option>
                                    <option v-for="store in stores" :value="store.id">{{ store.name }}</option>
                                </select>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item mb-3">
                                <select v-model="selectedFilters.category" name="category"
                                    class="form-select form-control" data-control="select2"
                                    data-placeholder="Categoria">
                                    <option value="">Seleccione una categoría</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">{{
                                        category.name }}</option>
                                </select>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item mb-3">
                                <select v-model="selectedFilters.designer" name="designer"
                                    class="form-select form-control" data-control="select2"
                                    data-placeholder="Elige una marca">
                                    <option value="">Elige una marca</option>
                                    <option v-for="designer in designers" :value="designer.id">{{ designer.name }}
                                    </option>
                                </select>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item mb-3">
                                <select v-model="selectedFilters.sizes" name="sizes" class="form-select form-control"
                                    data-control="select2" data-placeholder="Elige una talla" multiple>
                                    <option value="">Elige una talla</option>
                                    <option v-for="size in sizes" :value="size.id">{{ size.full_name }}</option>
                                </select>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item mb-3">
                                <ColorSelection v-model="selectedFilters.colors" :colors="colors"
                                    :usedColorIds="props.usedColorIds" ref="colorSelectionRef" />
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item mb-3">
                                <select v-model="selectedFilters.characteristics" name="characteristics"
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
                            <div class="menu-item mb-3">
                                <select v-model="selectedFilters.integrities" name="integrities"
                                    class="form-select form-control" data-control="select2"
                                    data-placeholder="Elige una integridad">
                                    <option value="">Elige una integridad</option>
                                    <option v-for="name, value in integrities" :value="value">{{ name }}</option>
                                </select>
                            </div>
                            <!--end::Menu item-->
                            <!-- Statuses -->
                            <div class="menu-item mb-3">
                                <select v-model="selectedFilters.status" name="status" class="form-select form-control"
                                    data-control="select2" data-placeholder="Elige un estado">
                                    <option value="">Elige un estado</option>
                                    <option v-for="name, value in statuses" :value="value">{{ name }}</option>
                                </select>
                            </div>
                            <!--begin::Menu item-->
                            <div class="menu-item mb-3">
                                <select v-model="selectedFilters.condition" name="condition"
                                    class="form-select form-control" data-control="select2"
                                    data-placeholder="Elige una condición">
                                    <option value="">Elige una condición</option>
                                    <option v-for="name, value in conditions" :value="value">{{ name }}</option>
                                </select>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item mb-3">
                                <button class="col-12 btn btn-primary" type="submit">Buscar</button>
                            </div>
                            <div class="menu-item mb-3">
                                <button class="col-12 btn btn-secondary" type="button" @click="clearFilters">Limpiar
                                    filtros</button>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item mb-3">
                                <button v-if="selectedItems.values.length > 0 && !allItemsSelected"
                                    @click="submitApplyFilters(true)" class="col-12 btn btn-primary mt-3" type="button">
                                    Mostrar {{ selectedItems.values.length }} seleccionado(s)
                                </button>
                            </div>
                            <!--end::Menu item-->
                        </form>
                        <!--end::Menu-->
                    </div>
                    <!--end::Aside content-->
                </div>
            </div>

            <div :class="{ 'col-xxxl-12': isAsideVisible, 'col-xl-9 move-left': !isAsideVisible }"
                class="d-flex flex-wrap d-grid gap-5 gap-xxl-9 w-100">
                <!--begin::Element list-->
                <div class="d-flex align-items-start flex-wrap gap-3" v-if="displayedItems.length > 0">
                    <button @click="toggleAllItems" class="btn btn-primary me-2">
                        <span class="state-square" :class="{ 'bg-success': selectedItems.selectAllResults }"></span>
                        {{ selectedItems.selectAllResults ? 'Deseleccionar todos' : 'Seleccionar todos' }}
                    </button>
                    <button @click="showAllItems" class="btn btn-primary me-2">
                        {{ context.showAll ? 'No mostrar todos' : 'Mostrar todos' }}
                    </button>
                    <div class="d-flex flex-wrap my-1 ms-2">
                        <ul class="nav nav-pills me-2" role="tablist">
                            <li class="nav-item m-0" role="presentation">
                                <a class="btn btn-sm btn-icon btn-light btn-color-muted btn-active-primary me-2"
                                    :class="{ active: !context.isTableView }" @click="context.isTableView = false"
                                    aria-selected="true" role="tab">
                                    <i class="ki-outline ki-element-plus fs-1"></i>
                                </a>
                            </li>
                            <li class="nav-item m-0" role="presentation">
                                <a class="btn btn-sm btn-icon btn-light btn-color-muted btn-active-primary"
                                    :class="{ active: context.isTableView }" @click="context.isTableView = true"
                                    aria-selected="false" tabindex="-1" role="tab">
                                    <i class="ki-outline ki-row-horizontal fs-2"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div v-if="hasAnySelection" class="d-flex gap-2 ms-2">
                        <WithPermission :module="ModuleAliases.ITEM" :permissions="[PermissionTypes.UPDATE]">
                            <button @click="showItemMassModal(selectedItems.values)" class="btn btn-primary">
                                Actualizar información
                            </button>
                        </WithPermission>
                        <button @click="printItemCards" class="btn btn-primary">
                            Imprimir fichas
                        </button>
                    </div>
                </div>

                <!-- Fila separada para el botón de Excel -->
                <div v-if="showExcelButton" class="w-100 d-flex align-items-center my-2">
                    <a @click="exportExcel" target="_blank"
                        :class="['btn btn-success fs-5', { 'disabled': contextExcel.loading || contextExcel.disable }]"
                        :aria-disabled="contextExcel.loading || contextExcel.disable">
                        <i class="ki-duotone ki-file-down text-gray fs-1"><span class="path1"></span><span
                                class="path2"></span></i>
                        Exportar a Excel
                    </a>
                    <span class="card-label fs-5 fw-bold text-gray-900 ms-2" v-if="contextExcel.loading">Descargando
                        archivo...</span>
                    <div v-if="contextExcel.loading" class="spinner-border spinner-border-md text-dark ms-2"
                        role="status" style="width: 3rem; height: 3rem; display:inline-block; vertical-align:middle;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <div v-if="hasAnySelection" class="w-100 mt-3 text-center">
                    <strong>Productos seleccionados: {{ selectedCount }}</strong>
                </div>

                <div v-if="displayedItems.length > 0 && !hasAnySelection" class="w-100 mt-3 text-center">
                    <strong>{{ visibleCount }} productos de {{ itemsData.total }} existentes.</strong>
                </div>

                <!-- Vista de tabla -->
                <div v-if="context.isTableView" class="card card-flush w-100 mb-5">
                    <div class="card-body pt-3">
                        <div class="table-responsive">
                            <table class="table table-row-bordered table-row-dashed gy-4 align-middle fw-bold">
                                <thead class="fs-7 text-gray-500 text-uppercase">
                                    <tr>
                                        <!-- NUEVA COLUMNA DE CHECKBOX -->
                                        <th class="text-center">✔</th>
                                        <th class="text-center">ID</th>
                                        <th class="text-center">Imagen</th>
                                        <th class="text-center">Talla</th>
                                        <th class="text-center">Ubicación</th>
                                        <th class="text-center">Nombre</th>
                                        <th class="text-center">Código de barras</th>
                                        <th class="text-center">Renta</th>
                                        <th class="text-center">Venta</th>
                                        <th class="text-center">Venta Completo</th>
                                        <th class="text-center">Rentas</th>
                                        <th class="text-center">Ultima Renta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in itemsData.data" :key="item.id"
                                        @click="submitGetItemInfo(item.id)" style="cursor: pointer;"
                                        :class="{ 'table-active': selectedItems.values.includes(item.id) }">

                                        <!-- CELDA CON CHECKBOX -->
                                        <td class="text-center" @click.stop>
                                            <input class="form-check-input" type="checkbox" :value="item.id"
                                                v-model="selectedItems.values" />
                                        </td>

                                        <td>{{ item.id }}</td>
                                        <td>
                                            <img :src="item.first_image" class="rounded-3" :alt="item.product_name"
                                                width="100" style="cursor: pointer"
                                                @click="submitGetItemInfo(item.id)" />
                                        </td>
                                        <td class="text-center">{{ item.size_name }}</td>
                                        <td class="text-center">{{ item.store_name }}</td>
                                        <td class="text-center">{{ item.product_name }}</td>
                                        <td class="text-center">{{ item.barcode }}</td>
                                        <td class="text-center">$ {{ item.prices_rent['4'].toFixed(2) }}</td>
                                        <td class="text-center">$ {{ item.price_sale.toFixed(2) }}</td>
                                        <td class="text-center">$ {{ item.full_price.toFixed(2) }}</td>
                                        <td class="text-center">{{ item.amount_rents }}</td>
                                        <td class="text-center">{{ item.last_rent_date || 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--begin::Element list-->
                <div v-else-if="displayedItems.length > 0" class="col-12 row row-cols-1 row-cols-md-2"
                    :class="sidebarClass">
                    <div v-for="item in itemsData.data" class="col mb-3 pb-3">
                        <ItemCard :key="item.id" :item="item" :is-selected="selectedItems.values.includes(item.id)"
                            v-model="selectedItems.values" :on-click="submitGetItemInfo" :disabled="context.loading" />
                    </div>
                </div>
                <!--end::Element list-->

                <!--begin::Informational text-->
                <div v-else class="card border-0 w-100">
                    <!--begin::Body-->
                    <div class="card-body">
                        <div class="col-md-12 text-center h2 my-5">
                            <div v-if="selectedFilters.wasSuccessful" class="col-md-12">
                                <strong>Sin resultados</strong>
                                <br>
                                No se encontro ningún artículo.
                                <br>
                                Puede ajustar los filtros y buscar de nuevo.
                            </div>
                            <div v-else-if="context.loading" class="col-md-12">
                                Buscando artículos...
                            </div>
                            <div v-else class="col-md-12">
                                Seleccione algunos filtros y haga click en "Buscar"
                            </div>
                        </div>
                    </div>
                    <!--end: Card Body-->
                </div>
                <!--end::Informational text-->
            </div>
        </div>

        <Pagination :links="itemsData.links" :onPageClick="(newPage) => submitApplyFilters(null, newPage)" />

        <button id="go-top-button" class="float-button" @click="scrollToTop">↑</button>
    </Master>

    <WithPermission :module="ModuleAliases.ITEM" :permissions="[PermissionTypes.UPDATE]">
        <InfoModal ref="itemInfoModal" :pricingSchemes="pricingSchemes" :stores="stores" :conditions="conditions"
            :employees="employees" :statuses="statuses"
            :onUpdated="() => submitApplyFilters(context.onlySelected, context.page, context.perPage)" />

        <MassModal ref="itemMassModal" :pricingSchemes="pricingSchemes" :conditions="conditions" :statuses="statuses"
            :onUpdated="() => submitApplyFilters(context.onlySelected, context.page, context.perPage)" />
    </WithPermission>
</template>

<style>
.move-left {
    transform: translateX(-350px);
    width: calc(100% + 200px);
}

.apps-sidebar-toggle {
    position: absolute;
    top: 0px;
    right: 0px;
    z-index: 1000;
    transition: transform 0.3s ease-in-out;
}

.aside-filter {
    transition: transform 0.3s ease-in-out;
}

.aside-filter.hidden {
    transform: translateX(-95%);
}


.shadow-green {
    box-shadow: rgba(6, 24, 44, 0.4) 0px 0px 0px 2px, rgba(6, 24, 44, 0.65) 0px 4px 6px -1px, rgba(255, 255, 255, 0.08) 0px 1px 0px inset;
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
