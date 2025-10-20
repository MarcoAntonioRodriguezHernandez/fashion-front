<script setup>
import { reactive, ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useForm } from '@inertiajs/vue3';
import { initSelectElements } from '@src/utils.js';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    pricingSchemes: { type: Array, required: true },
    conditions: { type: Object, required: true },
    statuses: { type: Object, required: true },
    onUpdated: { type: Function, default: () => { } },
});

const commonForm = useForm({
    condition: undefined,
    status: undefined,
    item_price: undefined,
    product_price: undefined,
});

defineExpose({
    show: () => {
        modal.show();
    },
    showWithItems: async (items) => {
        setItems(items);
        await computeAllowBazarMass();
        modal.show();
    },
});

onMounted(() => {
    initSelectElements(modalRoot, commonForm);

    const modalElement = modalRoot.value;
    modal = new bootstrap.Modal(modalRoot.value);

    modalElement.addEventListener('show.bs.modal', () => {
    });

    modalElement.addEventListener('hide.bs.modal', () => {
        commonForm.reset();
    });
});

// ------------------------
// Declarations
// ------------------------

let modal = null;
const selectedItems = reactive({});
const selectionMeta = reactive({ selectAll: false, totalResults: 0, filters: null });
const modalRoot = ref(null);

const allowBazarMass = ref(false);

const massConditions = computed(() => {
  const result = { ...props.conditions };
  if (!allowBazarMass.value) {
    for (const [value, label] of Object.entries(result)) {
      if (label === 'Bazar') delete result[value];
    }
    if (commonForm.condition && !(commonForm.condition in result)) {
      commonForm.condition = undefined;
    }
  }
  return result;
});

async function computeAllowBazarMass() {
  try {
    const payload = selectionMeta.selectAll
      ? { select_all: true, filters: selectionMeta.filters || {} }
      : { items: Object.values(selectedItems) };

    const { data } = await axios.post('/item/can-bazar-mass', payload);
    allowBazarMass.value = !!data.allow_bazar;
  } catch (e) {
    allowBazarMass.value = false;
  }
}

const setItems = (payload) => {
  for (let key in selectedItems) delete selectedItems[key];
  selectionMeta.selectAll = false;
  selectionMeta.totalResults = 0;
  selectionMeta.filters = null;

  if (Array.isArray(payload)) {
    payload.forEach((id) => (selectedItems[id] = id));
    selectionMeta.totalResults = payload.length;
  } else if (payload && typeof payload === 'object') {
    const ids = Array.isArray(payload.ids) ? payload.ids : [];
    ids.forEach((id) => (selectedItems[id] = id));
    selectionMeta.selectAll = !!payload.selectAll;
    selectionMeta.totalResults = Number(payload.totalResults ?? ids.length) || 0;
    selectionMeta.filters     = payload.filters || null;
  }
};

const buildMassPayload = (extra) => {
  return selectionMeta.selectAll
    ? { select_all: true, filters: selectionMeta.filters, ...extra } 
    : { items: Object.values(selectedItems), ...extra };             
};

const updateConditionAndStatusSubmit = () => {
    commonForm.transform(() => buildMassPayload({
        condition: commonForm.condition,
        status:    commonForm.status,
    })).post(url('/item/mass/condition-status'), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
        props.onUpdated();
        modal.hide();
        },
    });
};

const updateSubmit = (field, value, model) => {
  commonForm.transform(() => buildMassPayload({ [field]: value }))
    .post(url('/' + model + '/mass/' + field.replaceAll('_', '-')), {
      preserveState: true,
      preserveScroll: true,
      onSuccess: () => {
        props.onUpdated();
        modal.hide();
      },
    });
};

const updateConditionSubmit = () => {
    updateSubmit('condition', commonForm.condition, 'item');
}

const updateItemPriceSubmit = () => {
    updateSubmit('price_sale', commonForm.item_price, 'item');
}

const updateProductPriceSubmit = () => {
    updateSubmit('full_price', commonForm.product_price, 'product');
}
</script>

<template>
    <div ref="modalRoot" class="modal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modelo {{ commonForm.name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-pills mb-5" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#condition-tab"
                                type="button" role="tab">Acciones</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#item-price-tab" type="button"
                                role="tab">Precio de Artículos</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#product-price-tab"
                                type="button" role="tab">Precio de Productos</button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="condition-tab" role="tabpanel">
                            <h4 class="mb-5">Condición de Artículos</h4>

                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 mx-3">
                                <div v-for="(condition, value) in massConditions" :key="value" class="form-check mt-2">
                                    <input v-model="commonForm.condition" class="form-check-input" type="radio"
                                        :value="value" :id="'item-condition-' + value + '-2'">
                                    <label class="form-check-label" :for="'item-condition-' + value + '-2'">
                                        {{ condition }}
                                    </label>
                                </div>
                            </div>

                            <hr class="my-8">

                            <h4 class="mb-5">Estatus de Artículos</h4>

                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 mx-3">
                                <div v-for="status, value in statuses" class="form-check mt-2">
                                    <input v-model="commonForm.status" class="form-check-input" type="radio"
                                        :value="value" :id="'item-status-' + value + '-2'">
                                    <label class="form-check-label" :for="'item-status-' + value + '-2'">
                                        {{ status }}
                                    </label>
                                </div>
                            </div>

                            <div class="col-12 text-end mt-5">
                                <button @click="updateConditionAndStatusSubmit" :disabled="commonForm.processing"
                                    type="button" class="btn btn-light-primary">Guardar Información</button>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="item-price-tab" role="tabpanel">
                            <h4 class="mb-5">Precio de Artículos</h4>

                            <!--begin::Input group-->
                            <div class="mb-10 fv-row">
                                <!--begin::Label-->
                                <label class="required form-label">Precio de Venta</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input v-model="commonForm.item_price" type="number" class="form-control"
                                    placeholder="Precio de Venta">
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <div class="col-12 text-end mt-5">
                                <button @click="updateItemPriceSubmit()" :disabled="commonForm.processing" type="button"
                                    class="btn btn-light-primary">Guardar Información</button>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="product-price-tab" role="tabpanel">
                            <h4 class="mb-5">Precio de Productos</h4>

                            <!--begin::Input group-->
                            <div class="mb-10 fv-row">
                                <!--begin::Label-->
                                <label class="required form-label">Precio de Venta</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input v-model="commonForm.product_price" type="number" class="form-control"
                                    placeholder="Precio de Venta">
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <div class="col-12 text-end mt-5">
                                <button @click="updateProductPriceSubmit()" :disabled="commonForm.processing"
                                    type="button" class="btn btn-light-primary">Guardar Información</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="col-12 text-start" style="color: red !important;">
                        Se actualizarán {{ selectionMeta.selectAll ? selectionMeta.totalResults : Object.values(selectedItems).length }} artículos seleccionados
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                        :disabled="commonForm.processing">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
</template>
