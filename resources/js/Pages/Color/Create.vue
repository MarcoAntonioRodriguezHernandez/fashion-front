<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Master from "@layouts/Master.vue";
import { initSelectElements, url } from "@src/utils.js";

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    colorShades: { type: Array, default: () => ([]), required: true }
});

const colorForm = useForm({
    parent_color_id: 0,
    name: '',
    hexadecimal: '#FFFFFF',
    texture_src: null,
});

const context = reactive({
    editTexture: false,
});

onMounted(() => {
    if (root.value)
        initSelectElements(root, colorForm);
});

watch(() => context.editTexture, (value) => {
    if (!value)
        colorForm.texture_src = null;
});

// ------------------------
// Declarations
// ------------------------

const root = ref(null);

const colorContainerStyles = computed(() => {
    return {
        backgroundColor: !colorForm.texture_src ? colorForm.hexadecimal : '',
        backgroundImage: colorForm.texture_src ? `url(${URL.createObjectURL(colorForm.texture_src)})` : '',
        backgroundSize: 'cover',
        backgroundPosition: 'center',
    };
});

const submitForm = () => {
    colorForm.post(url('color'), {
        forceFormData: !!colorForm.texture_src,
        preserveState: true,
    });
}
</script>

<template>
    <Master title="Crear color" cardTitle="Crear color">
        <div class="form_padding">
            <div class="form d-flex flex-column flex-lg-row">
                <!--begin::Aside column-->
                <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                    <!--begin::Thumbnail settings-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h2>Color</h2>
                            </div>
                            <!--end::Card title-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body text-center pt-0">
                            <!--begin::Image input-->
                            <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3" data-kt-image-input="true">
                                <div class="image-input-wrapper w-250px h-250px" :style="colorContainerStyles">
                                </div>
                            </div>
                            <!--end::Image input-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Thumbnail settings-->
                </div>
                <!--end::Aside column-->
                <!--begin::Main column-->
                <div ref="root" class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <form @submit.prevent="submitForm()">
                        <div class="d-flex flex-column gap-7 gap-lg-10">
                            <div class="card card-flush py-4">
                                <div class="card-header">
                                    <div class="card-body pt-0">
                                        <!-- Input de Tono y Nombre -->
                                        <div class="row mb-10 fv-row">
                                            <div class="col">
                                                <label class="required form-label">Tono</label>
                                                <select v-model="colorForm.parent_color_id" name="parent_color_id" class="form-select form-control" data-control="select2" data-hide-search="true">
                                                    <option value="0" selected>Nuevo Tono</option>
                                                    <option v-for="color in colorShades" :key="color.id" :value="color.id">{{ color.name }}</option>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <label class="required form-label">Nombre</label>
                                                <input v-model="colorForm.name" type="text" name="name" id="name" class="form-control mb-2" placeholder="Nombre" />
                                            </div>
                                        </div>

                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">Hexadecimal</label>
                                            <div class="d-flex align-items-center">
                                                <input type="color" v-model="colorForm.hexadecimal" class="form-control form-control-solid me-2" />

                                                <a class="menu-link px-2" style="cursor: pointer;" data-bs-toggle="tooltip" @click="context.editTexture = !context.editTexture">
                                                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                        <svg v-if="context.editTexture" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                                <path d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                                <rect fill="#000000" x="9" y="12" width="6" height="2" rx="1" />
                                                            </g>
                                                        </svg>
                                                        <svg v-else xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                                <path d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                                <path d="M8.95128003,13.8153448 L10.9077535,13.8153448 L10.9077535,15.8230161 C10.9077535,16.0991584 11.1316112,16.3230161 11.4077535,16.3230161 L12.4310522,16.3230161 C12.7071946,16.3230161 12.9310522,16.0991584 12.9310522,15.8230161 L12.9310522,13.8153448 L14.8875257,13.8153448 C15.1636681,13.8153448 15.3875257,13.5914871 15.3875257,13.3153448 C15.3875257,13.1970331 15.345572,13.0825545 15.2691225,12.9922598 L12.3009997,9.48659872 C12.1225648,9.27584861 11.8070681,9.24965194 11.596318,9.42808682 C11.5752308,9.44594059 11.5556598,9.46551156 11.5378061,9.48659872 L8.56968321,12.9922598 C8.39124833,13.2030099 8.417445,13.5185067 8.62819511,13.6969416 C8.71848979,13.773391 8.8329684,13.8153448 8.95128003,13.8153448 Z" fill="#000000" />
                                                            </g>
                                                        </svg>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>

                                        <div class="mb-10 fv-row" v-if="context.editTexture">
                                            <label class="required form-label">Textura</label>
                                            <input @input="colorForm.texture_src = $event.target.files[0]" type="file" class="form-control form-control-solid" title="Seleccione una foto para cargar como textura" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <a :href="url('color')" class="btn btn-secondary me-3">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!--end::Main column-->
            </div>
        </div>
    </Master>
</template>
