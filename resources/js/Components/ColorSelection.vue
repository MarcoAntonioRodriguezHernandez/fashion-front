<script setup>
import { computed, nextTick, ref, watch } from 'vue';
import { toggleSelection } from '@src/utils.js';
import { defineExpose } from 'vue';

// ------------------------
// Component Attributes
// ------------------------

const selectedColors = defineModel({ required: true });

const props = defineProps({
    colors: { type: Object, required: true },
    usedColorIds: { type: [Array, Object], required: false, default: () => [] },
});

const usedColorIdsArr = computed(() => Array.isArray(props.usedColorIds)
    ? props.usedColorIds
    : Object.values(props.usedColorIds));

// ------------------------
// Declarations
// ------------------------

const selectedParent = ref(null);
const colorSelectionRoot = ref(null);

const parentColors = computed(() => {
    return Object.values(props.colors).map(group => group.parent);
});

const childrenColors = computed(() => {
    return selectedParent.value ? props.colors[selectedParent.value.id].children : null;
});

const toggleColorSelection = (colorId) => {
    toggleSelection(selectedColors.value, colorId);
}

const toggleShadeSelection = (parentId) => {
    const allChildren = props.colors[parentId].children.map(child => child.id);
    const selectableChildren = allChildren.filter(id => usedColorIdsArr.value.includes(id));
    const idsToToggle = [parentId, ...selectableChildren];

    if (!selectedColors.value.includes(parentId)) { // If parent was not selected, select all children
        idsToToggle.filter((e) => !selectedColors.value.includes(e))
            .forEach((e) => selectedColors.value.push(e));
    } else {
        idsToToggle.filter((e) => selectedColors.value.includes(e))
            .forEach((e) => selectedColors.value.splice(selectedColors.value.indexOf(e), 1));
    }
}

const initializeTooltips = () => {
    if (colorSelectionRoot.value) {
        [...colorSelectionRoot.value.querySelectorAll('[data-bs-toggle="tooltip"]')].map(el => new bootstrap.Tooltip(el, { // Initialize tooltips
            trigger: 'hover'
        }));
    }
}

watch(selectedParent, () => {
    nextTick(() => {
        initializeTooltips();
    });
});

defineExpose({
    clearSelection: () => {
        selectedParent.value = null;
    }
});
</script>

<template>
    <div class="menu-item mb-3">
        <div class="fs-5 fw-semibold mb-2">Selección de color</div>

        <div class="d-flex align-items-center">
            <div class="color-options">
                <label v-for="color in parentColors" :key="color.id"
                    :class="{ 'color-selected': selectedColors.includes(color.id) }" @click="selectedParent = color"
                    @dblclick="toggleShadeSelection(color.id)" :style="{ 'background-color': color.hexadecimal }"
                    data-bs-toggle="tooltip" :data-bs-title="color.name"></label>
            </div>
        </div>

        <div v-if="selectedParent" class="mt-3">
            <p class="fw-bold fs-6"> {{ selectedParent.name }}</p>
        </div>

        <template v-if="selectedParent">
            <div ref="colorSelectionRoot" class="mt-2">
                <div v-if="childrenColors.length > 0" class="color-options">
                    <label v-for="color in childrenColors" :key="color.id"
                        :class="[
                            { 'color-selected': selectedColors.includes(color.id) },
                            { 'color-disabled': !usedColorIdsArr.includes(color.id) }
                        ]"
                        @click="usedColorIdsArr.includes(color.id) ? toggleColorSelection(color.id) : null"
                        :style="{ 'background-color': color.hexadecimal }"
                        :color-id="color.id"
                        data-bs-toggle="tooltip"
                        :data-bs-title="usedColorIdsArr.includes(color.id) ? color.name : `Sin artículos asociados al color ${color.name}`"
                        :aria-disabled="!usedColorIdsArr.includes(color.id)">
                    </label>
                </div>

                <div v-else class="mt-2">
                    <p class="text-muted">No hay colores asignados a este tono</p>
                </div>
            </div>
        </template>
    </div>
</template>

<style lang="scss">
.color-options {
    display: flex;
    width: 100%;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-start;

    >label {
        aspect-ratio: 1 / 1;
        width: 2.5em;
        margin: 0 1.5%;
        border-radius: 50%;
        cursor: pointer;
        border: 1px solid black;
        margin-top: 5px;
        position: relative;
        transition: border 0.3s ease, outline 0.3s ease;

        &:hover {
            border: 2px solid white;
            box-shadow: none;
            outline: 2px solid black;
        }
    }

    .color-selected {
        border: 2px solid white;
        box-shadow: none;
        outline: 2px solid black;
    }
    .color-disabled {
        opacity: 0.5;
        pointer-events: none;
        cursor: not-allowed;
        border: 1px dashed #888;
    }
}
</style>
