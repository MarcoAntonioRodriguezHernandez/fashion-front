<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import Pagination from '@components/Pagination.vue';
import ColorSelection from '@components/ColorSelection.vue';
import { updateSelectElements, initSelectElements } from '@src/utils.js';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    itemResults: { type: Object, default: () => null },
    selectedItems: { type: Object, required: true },
    onItemSelected: { type: Function, required: true },
    categories: { type: Object, required: true },
    designers: { type: Object, required: true },
    colors: { type: Object, required: true },
    characteristics: { type: Object, required: true },
});
const filterItemForm = reactive({
    name: null,
    barcode: null,
    category: null,
    characteristics: [],
    colors: [],
});

onMounted(() => {
    if (filterItemsRoot.value) {
        initSelectElements(filterItemsRoot, filterItemForm);
    }
});

// ------------------------
// Declarations
// ------------------------

const filterItemsRoot = ref(null);
const itemsData = computed(() => props.itemResults ? Object.values(props.itemResults.data) : []);
const context = reactive({
    loading: false,
});

const submitApplyFilters = (page = null) => {
    router.reload({
        data: {
            ...filterItemForm,
            page: page ?? 1,
        },
        only: ['itemResults'],
        method: 'post',
        onStart: () => {
            context.loading = true;
        },
        onFinish: () => {
            context.loading = false;
        },
    });
}

const clearItemFilters = () => {
    filterItemForm.category = null;
    filterItemForm.name = null;
    filterItemForm.barcode = null;
    filterItemForm.colors = [];
    filterItemForm.characteristics = [];

    updateSelectElements(filterItemsRoot, filterItemForm);
}
</script>

<template>
    <div class="w-100">
        <!--begin::Filters Selection-->
        <div ref="filterItemsRoot" class="card card-flush">
            <!--begin::Aside content-->
            <div class="card-header px-7">
                <h5 class="card-title fw-bold text-gray-800 fs-2">Busqueda de artículos</h5>
            </div>
            <div class="card-body">
                <!--begin::Menu-->
                <form @submit.prevent="submitApplyFilters()">
                    <!--begin::Input Container-->
                    <div class="row mb-3 fv-row p-6 pt-0">
                        <div class="col row row-cols-1 row-cols-lg-2 fv-row">
                            <!--begin::Input-->
                            <div class="col-md-3">
                                <!--begin::Inbox-->
                                <div class="menu-item mb-3">
                                    <input v-model="filterItemForm.name" type="text" class="form-control" placeholder="Buscar por nombre de producto">
                                </div>
                                <!--end::Inbox-->
                            </div>
                            <!--end::Input-->
                            <!--begin::Input-->
                            <div class="col-md-3">
                                <!--begin::Inbox-->
                                <div class="menu-item mb-3">
                                    <input v-model="filterItemForm.barcode" type="text" class="form-control" placeholder="Buscar por código CM">
                                </div>
                                <!--end::Inbox-->
                            </div>
                            <!--end::Input-->
                            <!--begin::Input-->
                            <div class="col-md-3">
                                <div class="menu-item mb-3">
                                    <select v-model="filterItemForm.category" name="category" class="form-select form-control" data-control="select2" data-placeholder="Categoria" data-hide-search="true">
                                        <option value="">Seleccione una categoría</option>
                                        <option v-for="category in categories" :value="category.id">
                                            {{ category.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <!--end::Input-->
                            <!--begin::Input-->
                            <div class="col-md-3">
                                <div class="menu-item mb-3">
                                    <select v-model="filterItemForm.designer" name="designer" class="form-select form-control" data-control="select2" data-placeholder="Diseñador" data-hide-search="true">
                                        <option value="">Seleccione un diseñador</option>
                                        <option v-for="designer in designers" :value="designer.id">
                                            {{ designer.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <!--end::Input-->
                            <!--begin::Input-->
                            <div class="col-md-3">
                                <div class="menu-item mb-3">
                                    <select v-model="filterItemForm.characteristics" class="form-select form-control" data-control="select2" data-placeholder="Elige una característica" multiple>
                                        <option value="">Elige una característica</option>
                                        <optgroup v-for="group in characteristics" :label="group.parent.name">
                                            <option v-for="child in group.children" :value="child.id">
                                                {{ child.name }}
                                            </option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <!--end::Input-->
                        </div>

                        <div class="col-md-3">
                            <!--begin::Input-->
                            <div class="menu-item mb-3">
                                <ColorSelection v-model="filterItemForm.colors" :colors="colors" />
                            </div>
                            <!--end::Input-->
                        </div>
                    </div>
                    <!--end::Input Container-->

                    <!--begin::Button Container-->
                    <div class="col-12 text-end mt-4">
                        <!--begin::Input-->
                        <button class="btn btn-primary me-3 mb-3" type="submit">Buscar</button>
                        <button class="btn btn-secondary me-3 mb-3" type="button" @click="clearItemFilters">Limpiar filtros</button>
                        <!--end::Input-->
                    </div>
                    <!--end::Button Container-->
                </form>
            </div>
            <!--end::Aside content-->
        </div>
        <!--end::Filters Selection-->

        <!--begin::Filter Results Heading-->
        <h3 class="fw-bold mt-8 pb-4">
            {{ itemResults?.total ?? 0 }} artículos encontrados
            <span class="fs-6 text-gray-500 fw-semibold ms-1">según su busqueda ↓</span>
        </h3>
        <!--end::Filter Results Heading-->

        <!--begin::Filter Results-->
        <div class="card card-flush mb-6">
            <!--begin::Body-->
            <div class="card-body">
                <div v-if="itemResults?.total" class="table-responsive">
                    <table class="table table-row-bordered table-row-dashed gy-4 align-middle fw-bold">
                        <thead class="fs-7 text-gray-500 text-uppercase">
                            <tr>
                                <th class="text-center">Imagen</th>
                                <th class="text-center">Producto</th>
                                <th class="text-center">Código de Barras</th>
                                <th class="text-center">Variante</th>
                                <th class="text-center">Precio Venta</th>
                                <th class="text-center">Precio Renta</th>
                                <th class="text-center">Agregar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in itemsData" :key="item.id">
                                <td class="text-center"><img :src="item.first_image" class="rounded-3" :alt="item.product_full_name" width="75"></td>
                                <td class="text-center">
                                    {{ item.product_name }} <br>
                                    {{ item.designer_name }}
                                </td>
                                <td class="text-center">{{ item.barcode || 'No asignado' }}</td>
                                <td class="text-center">
                                    {{ item.color_name }} <br>
                                    {{ item.size_name }}
                                </td>
                                <td class="text-center">$ {{ item.price_sale }}</td>
                                <td class="text-center">{{ item.price_rent_label }}</td>
                                <td class="text-center">
                                    <button @click="onItemSelected(item)" class="btn btn-icon btn-secondary" type="button" :disabled="selectedItems[item.id]">
                                        <i class="ki-duotone ki-right-square fs-2tx">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-else class="col-12 text-center h3 my-5">
                    <div v-if="context.loading" class="d-flex flex-column align-items-center">
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
            <!--end: Card Body-->
        </div>
        <!--end::Filter Results-->

        <Pagination :links="itemResults?.links ?? []" :onPageClick="(newPage) => submitApplyFilters(newPage)" />
    </div>
</template>
