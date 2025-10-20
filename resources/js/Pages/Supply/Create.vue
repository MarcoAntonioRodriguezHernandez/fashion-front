<script setup>
import { computed, ref, watch, nextTick } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import Master from '@layouts/Master.vue';
import Summary from './partials/Summary.vue';
import TableTab from './partials/TableTab.vue';
import SearchTab from './partials/SearchTab.vue';
import { randomString, arrayDeepClone } from '@src/utils';

// ------------------------
// Component Attributes
// ------------------------

const page = usePage();

const props = defineProps({
    common: { type: Object, default: () => ({}) },
    stores: { type: Object, default: () => ({}) },
    items: { type: Array, default: [] },
    designers: { type: Object, required: true },
});

const supplyForm = useForm({
    items: [],
    code: randomString(8),
});

// ------------------------
// Declarations
// ------------------------

const showSummary = ref(false);
const showTable = ref(false);
const finalItems = ref([]);

const onTableConfirm = (items) => {
    console.log('Items recibidos desde TableTab:', items);
    finalItems.value = items;
    showSummary.value = true;
};

const initStores = (storesData) => {
    return Object.values(storesData).map((b) => ({
        ...b,
        visible: true,
    }));
}

const initItems = (itemsData) => {
    return arrayDeepClone(itemsData);
}

const allStores = ref(initStores(props.stores));
const allItems = ref(initItems(props.items));

watch([() => props.stores, () => props.items], ([newStores, newItems]) => {
    allItems.value = initItems(newItems);
    allStores.value = initStores(newStores);
});

watch(() => props.items, async (newItems) => {
    allItems.value = initItems(newItems);
    showTable.value = newItems.length > 0;

    if (showTable.value) {
        await nextTick();
        document.querySelector('#search_tab')?.classList.remove('show'); // Colapsar búsqueda
        document.querySelector('#table_tab')?.classList.add('show'); // Expandir tabla
    }
});

const updatedItems = computed(() => {
    return allItems.value.map(({ items }) => Object.keys(items).map((destKey) => items[destKey]
        .filter((i) => i.origin != destKey) // Get only updated items
        .map((i) => {
            let origin = props.stores[i.origin];
            let destination = props.stores[destKey];

            return {
                item: i,
                destination: {
                    id: destination.id,
                    key: destination.key,
                    name: destination.title,
                },
                origin: {
                    id: origin.id,
                    key: origin.key,
                    name: origin.title,
                },
            };
        }))
        .filter((c) => c.length > 0))
        .flat(2);
});

const submitSupply = () => {
    supplyForm.items = finalItems.value.map((i) => ({
        item_id: i.item.id,
        destination_id: i.destination.id,
    }));

    supplyForm.post(url('/supply'));
}
</script>

<template>
    <Master title="Distribuciones modelo" cardTitle="Distribuciones de modelo">
        <div v-if="!showSummary" class="col-12">
            <!-- product info -->
            <div class="card card-flush">
                <!--begin::Accordion-->
                <div class="accordion" id="supply_accordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button fs-4 fw-semibold" :class="{ 'collapsed': showTable }"
                                type="button" data-bs-toggle="collapse" data-bs-target="#search_tab"
                                aria-controls="search_tab">
                                Búsqueda de producto
                            </button>
                        </h2>
                        <div id="search_tab" class="accordion-collapse collapse" :class="{ 'show': !showTable }"
                            data-bs-parent="#supply_accordion">
                            <SearchTab :designers="designers" />
                        </div>
                    </div>

                    <div v-if="items.length" class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button fs-4 fw-semibold" :class="{ 'collapsed': !showTable }"
                                type="button" data-bs-toggle="collapse" data-bs-target="#table_tab"
                                aria-controls="table_tab">
                                Movimientos de inventario
                            </button>
                        </h2>
                        <div id="table_tab" class="accordion-collapse collapse" :class="{ 'show': showTable }"
                            data-bs-parent="#supply_accordion">
                            <TableTab :stores="allStores" :items="allItems" :common="common" :on-confirm="onTableConfirm" :disabled="updatedItems.length == 0" />
                        </div>
                    </div>
                </div>
                <!--end::Accordion-->
            </div>
        </div>

        <Summary v-if="showSummary" v-model:items="finalItems" :on-confirm="submitSupply" 
                :on-cancel="() => showSummary = false" :sender="page.props.authUser" 
                :form-object="supplyForm" :stores="allStores" />
    </Master>
</template>