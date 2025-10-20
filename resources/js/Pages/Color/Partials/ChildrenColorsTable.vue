<script setup>
import { useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import ConfirmationButton from '@components/ConfirmationButton.vue';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    colors: { type: Array, required: true },
});

const colorUpdateForm = useForm({
    id: null,
    name: null,
    hexadecimal: null,
    texture_src: null,
    editTexture: false,
    _method: 'put',
});

watch(() => colorUpdateForm.editTexture, (value) => {
    if (!value)
        colorUpdateForm.texture_src = null;
});

// ------------------------
// Declarations
// ------------------------

const colorContainerStyles = computed(() => {
    const backgroundImage = colorUpdateForm.texture_src && typeof colorUpdateForm.texture_src !== 'string' ?
        URL.createObjectURL(colorUpdateForm.texture_src) :
        colorUpdateForm.texture_src;

    return {
        backgroundColor: !backgroundImage ? colorUpdateForm.hexadecimal : '',
        backgroundImage: `url(${backgroundImage})`,
        backgroundSize: 'cover',
        backgroundPosition: 'center',
    };
});

const setEditionForColor = (color) => {
    colorUpdateForm.id = color?.id;
    colorUpdateForm.name = color?.name;
    colorUpdateForm.hexadecimal = color?.hexadecimal;
}

const submitColorEdition = () => {
    colorUpdateForm.post(url('color'), {
        forceFormData: !!colorUpdateForm.texture_src,
        preserveState: true,
        onSuccess: () => {
            setEditionForColor(null);
        },
    });
}
</script>

<template>
    <div class="card card-flush m-6">
        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5" data-paging="false">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-125px">Color/Textura</th>
                        <th class="min-w-125px">Hexadecimal</th>
                        <th class="min-w-125px text-start">Acciones</th>
                    </tr>
                </thead>

                <tbody class="fw-semibold text-gray-600">
                    <tr v-for="color in colors" :key="color.id">
                        <td class="min-w-125px">
                            <div class="d-flex align-items-center">
                                <p class="symbol symbol-50px">
                                    <span v-if="colorUpdateForm.id === color.id" class="symbol-label" :style="colorContainerStyles"></span>

                                    <span v-else class="symbol-label" :style="color.texture_src ? { backgroundImage: `url(${color.texture_src})`, backgroundSize: 'cover' } : { backgroundColor: color.hexadecimal }"></span>
                                </p>
                                <div class="ms-5">
                                    <input v-if="colorUpdateForm.id === color.id" v-model="colorUpdateForm.name" class="form-control form-control-solid" />

                                    <p v-else class="text-gray-800 text-hover-primary fs-5 fw-bold">{{ color.name }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <template v-if="colorUpdateForm.id === color.id">
                                <input type="color" v-model="colorUpdateForm.hexadecimal" class="form-control form-control-solid" />

                                <input v-if="colorUpdateForm.editTexture" @input="colorUpdateForm.texture_src = $event.target.files[0]" type="file" class="form-control form-control-solid" />
                            </template>
                            <span v-else class="fw-bold">{{ color.hexadecimal }}</span>
                        </td>
                        <td>
                            <template v-if="colorUpdateForm.id === color.id">
                                <a v-if="colorUpdateForm.editTexture" class="menu-link px-2 cursor-pointer" @click="colorUpdateForm.editTexture = false">
                                    <i class="ki-duotone ki-delete-files text-warning fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </a>
                                <a v-else class="menu-link px-2 cursor-pointer" @click="colorUpdateForm.editTexture = true">
                                    <i class="ki-duotone ki-add-files text-info fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </a>

                                <a class="menu-link px-2 cursor-pointer" @click="submitColorEdition()">
                                    <i class="ki-duotone ki-check-square text-success fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </a>
                                <a class="menu-link px-2 cursor-pointer" @click="setEditionForColor(null)">
                                    <i class="ki-duotone ki-cross-square text-danger fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </a>
                            </template>
                            <template v-else>
                                <a class="menu-link px-2 cursor-pointer" @click="setEditionForColor(color)">
                                    <i class="ki-duotone ki-notepad-edit text-success fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </a>
                                <ConfirmationButton v-if="color.status" title="¿Estas seguro que deseas desactivar este Color?" text="El color será desactivado" :route="url('color/' + color.id)" buttonText="Desactivar">
                                    <i class="ki-duotone ki-trash-square text-danger fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </ConfirmationButton>
                                <ConfirmationButton v-else title="¿Estas seguro que deseas reactivar este Color?" text="El color será reactivado" :route="url('color/' + color.id + '/reactivate')" buttonText="Reactivar" method="post">
                                    <i class="ki-duotone ki-arrows-circle text-info fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </ConfirmationButton>
                            </template>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
