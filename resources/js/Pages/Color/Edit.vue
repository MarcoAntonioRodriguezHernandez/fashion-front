<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Master from "@layouts/Master.vue";
import { initSelectElements, url } from "@src/utils.js";

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    color: { type: Object, required: true },
});

const colorForm = useForm({
    id: props.color.id,
    name: props.color.name,
    hexadecimal: props.color.hexadecimal,
    texture_src: props.color.texture_src,
    _method: 'put',
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
    const backgroundImage = colorForm.texture_src && typeof colorForm.texture_src !== 'string' ?
        URL.createObjectURL(colorForm.texture_src) :
        colorForm.texture_src;

    return {
        backgroundColor: !backgroundImage ? colorForm.hexadecimal : '',
        backgroundImage: `url(${backgroundImage})`,
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
    <Master title="Editar color" :cardTitle="'Editar ' + color.name">
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
                                                <label class="required form-label">Nombre</label>
                                                <input v-model="colorForm.name" type="text" name="name" id="name" class="form-control mb-2" placeholder="Nombre" />
                                            </div>
                                        </div>

                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">Hexadecimal</label>
                                            <div class="d-flex align-items-center">
                                                <input type="color" v-model="colorForm.hexadecimal" class="form-control form-control-solid me-2" />

                                                <a class="menu-link px-2 cursor-pointer" @click="context.editTexture = !context.editTexture">
                                                    <i v-if="context.editTexture" class="ki-duotone ki-delete-files text-warning fs-1">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    <i v-else class="ki-duotone ki-add-files text-info fs-1">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                    </i>
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
