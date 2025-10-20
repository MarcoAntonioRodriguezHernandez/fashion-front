<script setup>
import { computed } from "vue";
import MasterLayout from "@layouts/Master.vue";
import ChildrenColorsTable from "@/Pages/Color/Partials/ChildrenColorsTable.vue";
import { url } from "@src/utils.js";

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    color: { type: Object, default: () => ({}) }
});

// ------------------------
// Declarations
// ------------------------

const colorContainerStyles = computed(() => {
    const backgroundImage = props.color.texture_src && typeof props.color.texture_src !== 'string' ?
        URL.createObjectURL(props.color.texture_src) :
        props.color.texture_src;

    return {
        backgroundColor: !backgroundImage ? props.color.hexadecimal : '',
        backgroundImage: `url(${backgroundImage})`,
        backgroundSize: 'cover',
        backgroundPosition: 'center',
    };
});
</script>

<template>
    <MasterLayout title="Mostrar color" :cardTitle="color.name">
        <div class="form_padding">
            <!--begin::Form-->
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
                                <!--begin::Preview existing avatar-->
                                <div class="image-input-wrapper w-250px h-250px" id="colorContainer" :style="colorContainerStyles"></div>
                                <!--end::Preview existing avatar-->
                            </div>
                            <!--end::Image input-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Thumbnail settings-->
                </div>
                <!--end::Aside column-->
                <!--begin::Main column-->
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <!--end:::Tabs-->
                    <div class="d-flex flex-column gap-7 gap-lg-10">
                        <!--begin::Inventory-->
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card body-->
                                <div class="card-body pt-0">
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <input type="hidden" name="id" :value="color.id">
                                        <label class="form-label">Nombre</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" name="name" id="name" class="form-control mb-2" :value="color.name" disabled />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <label class="form-label">Slug</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" name="slug" id="slug" class="form-control mb-2" :value="color.slug" disabled />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <label class="form-label">Hexadecimal</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" name="hexadecimal" id="hexadecimal" class="form-control mb-2" :value="color.hexadecimal" disabled />
                                        <!--end::Input-->
                                    </div>
                                </div>
                                <!--end::Card header-->
                            </div>
                            <!--end::Inventory-->
                        </div>
                        <!--end::Tab content-->
                        <div class="d-flex justify-content-end">
                            <a :href="url('color')" class="btn btn-secondary me-3">Cancelar</a>
                            <a :href="url('color/' + color.id + '/edit')" type="button" class="btn btn-primary">Editar</a>
                        </div>
                    </div>
                    <!--end::Main column-->
                </div>
            </div>
        </div>

        <ChildrenColorsTable :colors="color.children" />
    </MasterLayout>
</template>
