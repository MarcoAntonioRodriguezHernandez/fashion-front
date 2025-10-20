<script setup>
import { ref, onMounted } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { initSelectElements } from '@src/utils.js';

// ------------------------
// Component Attributes
// ------------------------

const page = usePage();

const props = defineProps({
    designers: { type: Object, default: () => ({}) },
});

const searchForm = useForm({
    designer_id: null,
    name: null,
});

onMounted(() => {
    initSelectElements(searchTabRoot, searchForm);
});

// ------------------------
// Declarations
// ------------------------

const searchTabRoot = ref(null);
const submitSearch = () => {
    searchForm.post(url('/supply/search'));
}
</script>

<template>
    <div ref="searchTabRoot" class="accordion-body">
        <form @submit.prevent="submitSearch">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-5">
                        <label class="form-label fs-6 fw-bold">Diseñador / Marca</label>
                        <select v-model="searchForm.designer_id" name="designer_id" class="form-select form-control" data-control="select2" data-placeholder="Diseñador / Marca">
                            <option v-for="designer in designers" :value="designer.id">{{ designer.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-5">
                        <label class="form-label fs-6 fw-bold">Nombre del vestido</label>
                        <input v-model="searchForm.name" type="text" class="form-control form-control-solid fs-7">
                    </div>
                </div>
                <div class="col-12 text-end">
                    <button class="btn btn-primary" type="submit" :disabled="!searchForm.isDirty || searchForm.processing">Buscar</button>
                </div>
            </div>
        </form>
    </div>
</template>
