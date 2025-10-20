<script setup>
import { computed, ref, watch } from 'vue';
import Popper from "vue3-popper";
import Draggable from 'vuedraggable';
import ItemDraggable from '@/Pages/Supply/partials/ItemDraggable.vue';
import ItemInfoModal from '@/Pages/Supply/partials/ItemInfoModal.vue';
import cloneDeep from 'lodash.clonedeep';

// ------------------------
// Component Attributes
// ------------------------
const props = defineProps({
    common: { type: Object, default: () => ({}) },
    stores: { type: Object, required: true },
    items: { type: Array, required: true },
    onConfirm: { type: Function, required: true },
    disabled: { type: Boolean, default: true },
});

const localItems = ref([...props.items]);

watch(() => props.items, (newItems) => {
    localItems.value = [...newItems];
});

// ------------------------
// Declarations
// ------------------------

const dragging = ref(false);
const itemInfoModal = ref(null);

const setAllVisibility = (newVisibility) => {
    for (const board of props.stores) {
        board.visible = newVisibility;
    }
};

const initialSnapshot = ref(cloneDeep(props.items));

const onUndo = () => {
    localItems.value = cloneDeep(initialSnapshot.value);
};

const allBoardsHidden = computed(() => {
    return Object.values(props.stores).filter((b) => b.visible).length == 0;
});

const handleItemsUpdate = (updatedItems) => {
    localItems.value = updatedItems;
};

function handleConfirm() {
    const movedItems = [];
    
    localItems.value.forEach((group) => {
        Object.keys(group.items).forEach(storeKey => {
            group.items[storeKey].forEach(item => {
                if (item.origin !== storeKey) {
                    const originStore = props.stores.find(s => s.key === item.origin);
                    const destinationStore = props.stores.find(s => s.key === storeKey);
                    
                    if (originStore && destinationStore) {
                        movedItems.push({
                            item: item,
                            destination: {
                                id: destinationStore.id,
                                key: destinationStore.key,
                                name: destinationStore.title,
                            },
                            origin: {
                                id: originStore.id,
                                key: originStore.key,
                                name: originStore.title,
                            },
                        });
                    }
                }
            });
        });
    });
    
    props.onConfirm(movedItems);
}
</script>

<template>
    <div class="accordion-body">
        <div class="d-flex justify-content-end mb-3">
            <button @click="setAllVisibility(true)" class="btn btn-info fs-7 py-2 px-4 m-1" type="button">
                Mostrar todos
            </button>

            <button @click="setAllVisibility(false)" class="btn btn-info fs-7 py-2 px-4 m-1" type="button">
                Ocultar todos
            </button>
        </div>

        <div class="row col-12">
            <div v-for="board in stores"
                class="w-auto form-check form-check-solid form-switch form-check-success mx-3 my-1 w-175px"
                :key="board.key">
                <input :id="board.key + 'switch'" class="form-check-input w-35px h-20px" type="checkbox"
                    v-model="board.visible">
                <label class="form-check-label fixed-width-label">{{ board.title }}</label>
            </div>
        </div>

        <div v-if="allBoardsHidden" class="text-center my-15">
            <h3>Seleccione una sucursal para ver sus artículos</h3>
        </div>

        <div class="overflow-auto my-10" style="height: 70vh;">
            <table class="table table-bordered">
                <thead class="table align-middle bg-black sticky-top">
                    <tr>
                        <th v-if="!allBoardsHidden" class="w-100px">
                            <span style="padding-left: 1em; color: #ffffff;">{{ common.name ?? 'Productos' }}</span>
                        </th>
                        <template v-for="board in stores">
                            <th v-if="board.visible" class="w-100px" :key="board.key">
                                {{ board.title }}
                            </th>
                        </template>
                    </tr>
                </thead>

                <tbody>
                    <template v-for="group in localItems" :key="group.id">
                        <tr style="border: 1px solid #000 !important;">
                            <td v-if="!allBoardsHidden" class="user-select-none bg-black">
                                <Popper style="padding-left: 1em;" placement="right" offsetDistance="0"
                                    :interactive="false">
                                    {{ group.name }}

                                    <template v-if="!dragging" #content>
                                        <div style="max-width: 8rem; padding: 0.3rem;">
                                            <img class="w-100 h-100" style="object-fit: contain;" :src="group.image_src"
                                                :alt="group.name">
                                        </div>
                                    </template>
                                </Popper>
                            </td>

                            <template v-for="store in stores" :key="store.key">
                                <Draggable v-if="store.visible" v-model="group.items[store.key]"
                                    @start="dragging = true" @end="dragging = false" tag="td" :group="group.id"
                                    handle=".enabled" :item-key="store.key + '-' + group.id"
                                    :class="{ 'table-active': group.items[store.key].length > 0 }">

                                    <template #item="{ element: item }">
                                        <ItemDraggable @itemSelected="itemInfoModal.showWithInput($event)" :item="item"
                                            :store="store" :dragging="dragging" />
                                    </template>
                                </Draggable>
                            </template>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-secondary" @click="onUndo" :disabled="disabled">Deshacer</button>
            <button type="button" class="btn btn-primary" @click="handleConfirm" :disabled="disabled">
                Guardar</button>
        </div>
       
    </div>

    <ItemInfoModal ref="itemInfoModal" />
</template>

<style>
table.bordered td {
    border: 1px solid #000 !important;
}

table.bordered tr {
    border: 1px solid #000 !important;
}

.draggable-container {
    position: relative;
    display: inline-flex;
    vertical-align: middle;
    margin-bottom: 0.5rem;
}

.draggable-item {
    border-radius: 0.475rem;
    white-space: nowrap;
    user-select: none;
    margin-right: 0.25rem;
    margin-left: 0.25rem;
    padding: 0.5rem;
    cursor: grab;
    border: 1px solid #646464;
    position: relative;
    margin-right: 15px;
}

.damaged-marker::before,
.lost-marker::before,
.liquidation-marker::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    height: 4px;
    width: 100%;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
}

.damaged-marker::before {
    background-color: rgb(255, 49, 49);
}

.lost-marker::before {
    background-color: #DC3545
}

.liquidation-marker::after {
    width: 40%;
    background-color: #FFA500;
    z-index: 1;
}

.importation-marker::after,
.pending-transfer-marker::before,
.transfer-marker::before {
    content: '';
    position: absolute;
    top: -5px;
    left: -5px;
    width: 10px;
    height: 10px;
    border: 1px solid #000;
    border-radius: 50%;
}

.importation-marker::after {
    background-color: #0000FF;
}

.pending-transfer-marker::before {
    background-color: #F6C000;
    z-index: 1;
}

.transfer-marker::before {
    background-color: rgb(255, 0, 0);
}

.draggable-container:not(.enabled) .draggable-item {
    background-color: rgb(114, 114, 114) !important;
    color: white !important;
    cursor: not-allowed;
}

.trayect-marker::before {
    content: '';
    position: absolute;
    top: -5px;
    left: -5px;
    width: 10px;
    height: 10px;
    border: 1px solid #000;
    border-radius: 50%;
    background-color: #FF0000;
    z-index: 1;
}

@keyframes shake {
    0% {
        transform: rotate(0deg);
    }

    25% {
        transform: rotate(-2.9deg);
    }

    50% {
        transform: rotate(2.9deg);
    }

    75% {
        transform: rotate(-2.9deg);
    }

    100% {
        transform: rotate(0deg);
    }
}

.draggable-item[updated=true] {
    border: 2px solid #646464;
    animation: shake 0.4s infinite;
}

.draggable-item.pending-transfer-marker,
.draggable-item.transfer-marker {
    animation: shake 0.5s ease infinite;
}

.bg-black {
    background-color: #000 !important;
    color: #ffffff !important;
}

.table-responsive {
    overflow-y: none !important;
    overflow-x: none !important;
}
</style>
