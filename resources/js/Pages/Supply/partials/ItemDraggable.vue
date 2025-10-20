<script setup>
import Popper from "vue3-popper";
import { isDarkColor } from '@src/utils';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    item: { type: Object, required: true },
    store: { type: Object, required: true },
    dragging: { type: Boolean, required: true },
});
</script>

<template>
    <Popper hover placement="right" offsetDistance="0" :interactive="false" :key="item.id">
        <div @dblclick="$emit('itemSelected', item)"
            :class="{ 'draggable-container': true, 'enabled': item.enabled && !item.markers.importation }">
            <span :updated="item.origin != store.key && !item.markers.trayect" :class="{
                'draggable-item': true,
                'common-line-marker': true,
                'liquidation-marker': item.markers.liquidation,
                'lost-marker': item.markers.lost,
                'damaged-marker': item.markers.damaged,
                'importation-marker': item.markers.importation,
                'transfer-marker': item.markers.transfer && !item.markers.pending_transfer && !item.markers.trayect,
                'pending-transfer-marker': item.markers.pending_transfer && !item.markers.trayect,
                'trayect-marker': item.markers.trayect,
            }" :style="{
                'background-color': item.primaryData.color,
                'color': isDarkColor(item.primaryData.color) ? 'white' : 'black'
            }">
                {{ item.primaryData.text }}
            </span>
        </div>
        <template v-if="!dragging" #content>
            <div style="padding: 1rem 0.7rem;">
                <ul class="list-group list-group-flush">
                    <li v-if="item.markers.importation" class="list-group-item py-1 fs-small popper-bg text-danger">
                        <strong>Aviso:</strong> No puedes mover artículos en importación
                    </li>
                    <li v-for="value, key in item.secondaryData" class="list-group-item py-1 fs-small popper-bg">
                        {{ key }}: {{ value }}
                    </li>

                    <button @click="$emit('itemSelected', item)" type="button"
                        class="list-group-item list-group-item-action py-1 fs-small popper-bg">
                        Ver más detalles
                    </button>
                </ul>
            </div>
        </template>
    </Popper>
</template>
