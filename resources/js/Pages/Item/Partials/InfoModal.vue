<script setup>
import { onMounted, watch, nextTick, computed, ref } from 'vue';
import { useForm, usePage, router } from '@inertiajs/vue3';
import { initSelectElements, updateSelectElements } from '@src/utils.js';
import Summary from '../../Supply/partials/Summary.vue';
import { randomString } from '@src/utils';

// ------------------------
// Component Attributes
// ------------------------

const page = usePage()

const props = defineProps({
    pricingSchemes: { type: Array, required: true },
    stores: { type: Array, required: true },
    conditions: { type: Object, required: true },
    statuses: { type: Object, required: true },
    onUpdated: { type: Function, default: () => { } },
    employees: { type: Array, required: true },
});

const commonForm = useForm({
    item_id: undefined,
    name: undefined,
    product_title: undefined,
    current_store_name: undefined,
    target_store_name: undefined,
    condition: undefined,
    status: undefined,
    price_sale: undefined,
    full_price: undefined,
    barcode: undefined,
    serial_number: undefined,
    details: undefined,
    description: undefined,
    pricing_scheme_id: undefined,
    created_at: undefined,
    supplies: [],
    first_image: undefined,
    variant_color: undefined,
    variant_size: undefined,
    orders: [],
    is_in_transit: undefined,
    importation: undefined,
    target_recipient_name: undefined,
});

// Supply form & methods
const summaryObj = useForm({ items: [], code: '' })
const supplyForm = useForm({
    target_store_id: '',
    target_recipient_id: '',
    code: '',
});

const filteredEmployees = computed(() => {
    const filtered = props.employees.filter(emp => emp.store_id === Number(supplyForm.target_store_id));
    return filtered
});

const filteredConditions = computed(() => {
    const storeName = (commonForm.current_store_name || '').trim();
    const result = { ...props.conditions };
    if (storeName !== 'Almacén') {
        for (const [value, label] of Object.entries(result)) {
            if (label === 'Bazar') 
                delete result[value];
            }
    }
    return result;
});

watch(() => supplyForm.target_store_id, (newStoreId) => {
    supplyForm.code = randomString(8);
    supplyForm.target_recipient_id = ''
    summaryObj.code = supplyForm.code
    summaryObj.items = [{
        destination: { name: props.stores.find(x => x.id === Number(newStoreId))?.name ?? '' },
        origin: { name: commonForm.current_store_name },
        item: {
            tertiaryData: {
                first_image: commonForm.first_image,
                barcode: commonForm.barcode,
                color: commonForm.variant_color?.hexadecimal,
                size: commonForm.variant_size?.full_name,
            }
        }
    }];
},
    { immediate: true }
);

function submitSupply() {
    const recipientId = supplyForm.target_recipient_id;
    const body = {
        code: supplyForm.code,
        items: [{
            item_id: commonForm.item_id,
            destination_id: Number(supplyForm.target_store_id),
            recipient_id: recipientId !== '' ? Number(recipientId) : null,
        }]
    }
    router.post('/supply', body, {
        preserveState: true,
        onError: (errors) => {
            page.props.errors = {}
        }
    });
}

const targetStoreSelect = ref(null)
const targetReceptorSelect = ref(null)

function clearSupplyForm() {
    supplyForm.target_store_id = ''
    supplyForm.target_recipient_id = ''
    supplyForm.code = ''
    nextTick(() => {
        $(targetStoreSelect.value).val('').trigger('change')
        $(targetReceptorSelect.value).val('').trigger('change')
    })
}
// end supply form & methods

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
};

defineExpose({
    show: () => {
        modal.show();
        infoModalRoot.value.removeAttribute('aria-hidden');
    },
    showWithInput: async (data) => {
        formData = data;
        if (data.id || data.item_id) {
            commonForm.item_id = data.id || data.item_id;
        }
        resetToValues({
            ...formData,
            orders: formData.orders || []
        });
        modal.show();
        infoModalRoot.value.removeAttribute('aria-hidden');
        await fetchItemOrders();
    },
});

onMounted(() => {
    initSelectElements(infoModalRoot, commonForm);
    initSelectElements(infoModalRoot, supplyForm);

    modal = new bootstrap.Modal(infoModalRoot.value);

    infoModalRoot.value.addEventListener('show.bs.modal', () => {
        infoModalRoot.value.removeAttribute('aria-hidden');
    });

    infoModalRoot.value.addEventListener('hide.bs.modal', () => {
        infoModalRoot.value.setAttribute('aria-hidden', 'true');
    });

    infoModalRoot.value.addEventListener('hidden.bs.modal', clearSupplyForm)
});

// ------------------------
// Declarations
// ------------------------

let modal = null;
let formData = {};
const infoModalRoot = ref(null);

const updateSubmit = () => {
    commonForm.put(url('/item/data/' + commonForm.item_id), {
        onSuccess: (response) => {
            if (response.props?.orders) {
                commonForm.orders = response.props.orders.map(order => ({
                    order_code: order.order_code || order.code || `#${order.order_marketplace_id || order.marketplace_id}`,
                    date: order.date
                }));
            }
            else if (response.data?.orders) {
                commonForm.orders = response.data.orders.map(order => ({
                    order_code: order.order_code || order.code || `#${order.order_marketplace_id || order.marketplace_id}`,
                    date: order.date
                }));
            }
            else if (response.orders) {
                commonForm.orders = response.orders.map(order => ({
                    order_code: order.order_code || order.code || `#${order.order_marketplace_id || order.marketplace_id}`,
                    date: order.date
                }));
            }
            else {
                commonForm.orders = [];
            }

            props.onUpdated();
            modal.hide();
        },
        onError: (errors) => {
            console.error('Error en la solicitud:', errors);
        }
    });
}

const resetToValues = (data) => {
    for (const key in data) {
        if (key === 'orders' && Array.isArray(data[key])) {
            commonForm[key] = data[key].map(order => ({
                order_code: order.order_code || (order.order_marketplace_id ? `#${order.order_marketplace_id}` : 'N/A'),
                date: order.date,
                marketplace_id: order.order_marketplace_id
            }));
        } else {
            commonForm[key] = data[key];
        }
    }
    updateSelectElements(infoModalRoot, commonForm);
}

const fetchItemOrders = async () => {
    try {
        const response = await axios.get(`/api/test-orders/${commonForm.item_id}`);

        if (response.data?.orders) {
            commonForm.orders = response.data.orders.map(order => ({
                order_code: order.order_code || `#${order.order_marketplace_id}`,
                date: order.date,
                marketplace_id: order.order_marketplace_id,
                status: order.exists ? 'Activa' : 'Inactiva'
            }));
        } else {
            console.warn('El API no devolvió órdenes');
            commonForm.orders = [];
        }
    } catch (error) {
        console.error('Error al cargar órdenes:', error);
        commonForm.orders = [];
    }
};
</script>

<template>
    <div ref="infoModalRoot" id="info-modal" class="modal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modelo {{ commonForm.name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-pills mb-5" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button @click="resetToValues(formData)" class="nav-link active" data-bs-toggle="tab"
                                data-bs-target="#actions-tab" type="button" role="tab">Acciones</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button @click="resetToValues(formData)" class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#information-tab" type="button" role="tab">Información</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button @click="resetToValues(formData)" class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#model-tab" type="button" role="tab">Modelo</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button @click="resetToValues(formData)" class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#global-description-tab" type="button" role="tab">Descripción
                                Global</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button @click="resetToValues(formData)" class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#variant-tab" type="button" role="tab">Variante</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button @click="resetToValues(formData)" class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#sucursal-tab" type="button" role="tab">Sucursal</button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="actions-tab" role="tabpanel">

                            <h4>Condición</h4>

                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 mx-3">
                                <div v-for="(condition, value) in filteredConditions" class="form-check mt-2">
                                    <input v-model="commonForm.condition" class="form-check-input" type="radio"
                                        :value="value" :id="'item-condition-' + value">
                                    <label class="form-check-label" :for="'item-condition-' + value">
                                        {{ condition }}
                                    </label>
                                </div>
                            </div>

                            <hr class="my-8">

                            <h4>Estado</h4>

                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 mx-3">
                                <div v-for="status, value in statuses" class="form-check mt-2">
                                    <input v-model="commonForm.status" class="form-check-input" type="radio"
                                        :value="value" :id="'item-status-' + value">
                                    <label class="form-check-label" :for="'item-status-' + value">
                                        {{ status }}
                                    </label>
                                </div>
                            </div>

                            <div class="col-12 text-end mt-5">
                                <button @click="updateSubmit()" type="button" class="btn btn-light-primary">Guardar
                                    Información</button>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="information-tab" role="tabpanel">
                            <!--begin::Input group-->
                            <div class="mb-10 fv-row">
                                <!--begin::Label-->
                                <label class="required form-label">Precio de Venta</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input v-model="commonForm.price_sale" type="number" class="form-control"
                                    placeholder="Precio de Venta">
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-10 fv-row">
                                <!--begin::Label-->
                                <label class="required form-label">Código de Barras</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input v-model="commonForm.barcode" type="text" class="form-control"
                                    placeholder="Código de Barras">
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-10 fv-row">
                                <!--begin::Label-->
                                <label class="required form-label">Número de Serie</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input v-model="commonForm.serial_number" type="text" class="form-control"
                                    placeholder="Número de Serie">
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <div class="col-12 text-end mt-5">
                                <button @click="updateSubmit()" type="button" class="btn btn-light-primary">Guardar
                                    Información</button>
                            </div>

                            <hr>

                            <h3>Historial Sucursales</h3>

                            <span class="fw-bold">Ubicación actual:</span> {{ commonForm.current_store_name }}
                            <br>
                            <span class="fw-bold">Fecha de Creación:</span> {{ commonForm.created_at }}

                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Fecha de llegada</th>
                                            <th>Fecha de salida</th>
                                            <th>Sucursal</th>
                                            <th>Duración</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(location, index) in commonForm.supplies" :key="index">
                                            <td>
                                                <span
                                                    v-if="location.reception_date && location.shipping_date && new Date(location.reception_date.split('/').reverse().join('-')) > new Date(location.shipping_date.split('/').reverse().join('-'))">
                                                    {{ location.shipping_date }}
                                                </span>
                                                <span v-else-if="location.reception_date">{{ location.reception_date
                                                    }}</span>
                                                <span v-else class="text-muted">-</span>
                                            </td>
                                            <td>
                                                <span v-if="location.is_current" class="badge bg-success">A la
                                                    fecha</span>
                                                <span v-else-if="location.shipping_date">{{ location.shipping_date
                                                }}</span>
                                                <span v-else class="text-muted">-</span>
                                            </td>

                                            <td>
                                                {{ location.store_name }}
                                                <span v-if="location.from_created" class="badge bg-secondary"></span>
                                                <span v-else-if="location.is_current"></span>
                                            </td>
                                            <td>
                                                <span v-if="location.elapsed">
                                                    {{ location.elapsed.weeks }} semanas, {{ location.elapsed.days }}
                                                    días
                                                </span>
                                                <span v-else class="text-muted">-</span>
                                            </td>
                                        </tr>
                                        <tr v-if="commonForm.supplies.length === 0">
                                            <td colspan="4" class="text-center text-muted">
                                                No hay historial de sucursales
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <h3>Historial de Ordenes</h3>
                            <br>
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No. Orden</th>
                                            <th>Fecha de creación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(order, index) in commonForm.orders.slice().sort((a, b) => new Date(b.date) - new Date(a.date)).slice(0, 5)"
                                            :key="order.marketplace_id || index">
                                            <td><b>
                                                    <a :href="`/order/marketplace/${order.marketplace_id || order.order_marketplace_id}`"
                                                        class="text-primary">
                                                        {{ order.order_code || `#${order.marketplace_id}` }}
                                                    </a></b>
                                            </td>
                                            <td>
                                                {{ order.date ? formatDate(order.date) : 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr v-if="commonForm.orders.length === 0">
                                            <td colspan="2" class="text-center text-muted">
                                                No hay órdenes registradas
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="model-tab" role="tabpanel">
                            <!--begin::Input group-->
                            <div class="mb-10 fv-row">
                                <!--begin::Label-->
                                <label class="required form-label">Esquema de precios</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <div class="d-flex gap-3">
                                    <select v-model="commonForm.pricing_scheme_id" name="pricing_scheme_id"
                                        class="form-select form-control" data-control="select2"
                                        :data-dropdown-parent="'#info-modal'" data-placeholder="Esquema de Precios">
                                        <option value="">Seleccione un esquema de precios</option>
                                        <option v-for="pricingScheme in pricingSchemes" :value="pricingScheme.id">$ {{
                                            pricingScheme.sku_4.price }} - $ {{ pricingScheme.msrp }}</option>
                                    </select>
                                </div>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-10 fv-row">
                                <!--begin::Label-->
                                <label class="required form-label">Precio del modelo</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <div class="d-flex gap-3">
                                    <input v-model="commonForm.full_price" type="text" class="form-control"
                                        placeholder="Precio del Modelo">
                                </div>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <div class="row">
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row col-md-6">
                                    <!--begin::Label-->
                                    <label class="required form-label">Título del Producto</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input v-model="commonForm.product_title" type="text" class="form-control"
                                        placeholder="Título del Producto">
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row col-md-6">
                                    <!--begin::Label-->
                                    <label class="required form-label">Nombre del Modelo</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input v-model="commonForm.name" type="text" class="form-control"
                                        placeholder="Nombre del Modelo">
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--begin::Input group-->
                            <div class="mb-10 fv-row">
                                <!--begin::Label-->
                                <label class="required form-label">Descripción del Modelo</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input v-model="commonForm.details" type="text" class="form-control"
                                    placeholder="Descripción del modelo" required>
                                <!--end::Input-->
                            </div>

                            <!--end::Input group-->
                            <div class="col-12 text-end mt-5">
                                <button @click="updateSubmit()" type="button" class="btn btn-light-primary">Actualizar
                                    Información del Artículo</button>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="global-description-tab" role="tabpanel">
                            <!--begin::Input group-->
                            <div class="mb-10 fv-row">
                                <!--begin::Label-->
                                <label class="required form-label">Descripción global</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <div class="d-flex gap-3">
                                    <div v-html="commonForm.description" class="form-control"
                                        style="min-height: 200px;"></div>

                                </div>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <div class="col-12 text-end mt-5">
                                <button @click="updateSubmit()" type="button" class="btn btn-light-primary">Actualizar
                                    Información General</button>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="variant-tab" role="tabpanel">
                            <div class="row">
                                <!--begin::Input group-->
                                <div class="mb-10 col-md-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Color de la Variante</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input
                                        :value="typeof commonForm.variant_color === 'object' ? commonForm.variant_color.name : commonForm.variant_color"
                                        type="text" class="form-control" disabled>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 col-md-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Tamaño de la Variante</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input
                                        :value="typeof commonForm.variant_size === 'object' ? commonForm.variant_size.name : commonForm.variant_size"
                                        type="text" class="form-control" disabled>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                            </div>
                        </div>
                        <!-- Tab Sucursal -->
                        <div class="tab-pane fade" id="sucursal-tab" role="tabpanel">
                            <!-- <h4>Sucursal</h4> -->
                            <!-- <div v-show="!supplyForm.target_store_id"> -->
                            <div class="mb-4">
                                <div class="row align-items-end g-3">
                                    <div class="col-md-3">
                                        <label class="required form-label">Sucursal actual</label>
                                        <input v-model="commonForm.current_store_name" class="form-control" type="text"
                                            disabled>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="required form-label">Mover a sucursal</label>
                                        <input v-if="commonForm.is_in_transit" v-model="commonForm.target_store_name"
                                            class="form-control" type="text" disabled>
                                        <div v-show="!commonForm.is_in_transit">
                                            <div v-if="commonForm.importation">
                                                <input type="text" class="form-control"
                                                    value="No disponible: Artículo en importación" disabled>
                                            </div>
                                            <div v-else>
                                                <select v-model="supplyForm.target_store_id" class="form-select"
                                                    name="target_store_id" ref="targetStoreSelect"
                                                    data-control="select2" :data-dropdown-parent="'#info-modal'">
                                                    <option disabled value="">Seleccione una sucursal</option>
                                                    <option v-for="store in props.stores" :key="store.id"
                                                        :value="store.id">
                                                        {{ store.name }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="required form-label">Receptor</label>
                                        <input v-if="commonForm.is_in_transit"
                                            v-model="commonForm.target_recipient_name" class="form-control" type="text"
                                            disabled>
                                        <div v-show="!commonForm.is_in_transit">
                                            <div v-if="commonForm.importation">
                                                <input type="text" class="form-control" value="No disponible" disabled>
                                            </div>
                                            <div v-else>
                                                <select v-model="supplyForm.target_recipient_id" class="form-select"
                                                    name="target_recipient_id" ref="targetReceptorSelect"
                                                    data-control="select2" :data-dropdown-parent="'#info-modal'">
                                                    <option disabled value="">Seleccione un empleado</option>
                                                    <option v-for="employee in filteredEmployees"
                                                        :key="employee.user_id" :value="employee.user_id">
                                                        {{ employee.full_name }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <Summary v-if="supplyForm.target_store_id" :sender=page.props.authUser
                                :form-object="summaryObj" :items="summaryObj.items" :on-confirm="submitSupply"
                                :on-cancel="clearSupplyForm"></Summary>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
</template>
