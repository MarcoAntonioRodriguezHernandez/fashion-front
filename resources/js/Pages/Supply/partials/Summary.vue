<script setup>
import { getCurrentDateTime, isDarkColor } from '@src/utils';
import { ref, watch, defineEmits, defineProps } from 'vue';

const props = defineProps({
    sender: { type: Object, required: true },
    items: { type: Array, required: true },
    onConfirm: { type: Function, required: true },
    onCancel: { type: Function, required: true },
    formObject: { type: Object, required: true },
    stores: { type: Array, required: true },
});

const emit = defineEmits(['update:items']);

const localItems = ref([...props.items]);

watch(() => props.items, (newItems) => {
    localItems.value = [...newItems];
});

function removeItem(index) {
    localItems.value.splice(index, 1);
    emit('update:items', [...localItems.value]);
}

function onConfirmClick() {
    const filteredItems = localItems.value.filter(item => item != null);
    props.onConfirm(filteredItems);
}

function updateDestination(index, newStoreId) {
    const store = props.stores.find(s => s.id === newStoreId);
    if (store) {
        localItems.value[index].destination = {
            id: store.id,
            key: store.key,
            name: store.title,
        };
    }
}

</script>

<template>
    <div class="col-12">
        <!--begin::Supply info-->
        <div class="card card-xl-stretch mb-xl-8">
            <!--begin::Header-->
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">Información de la distribución</span>
                </h3>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body row row-cols-1 row-cols-md-2 py-3">
                <!--begin::Input group-->
                <div class="mb-10 fv-row">
                    <!--begin::Label-->
                    <label class="required form-label">Emisor</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input type="text" class="form-control" :value="sender.name + ' ' + sender.last_name" readonly>
                    <!--end::Input-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-10 fv-row">
                    <!--begin::Label-->
                    <label class="required form-label">Código</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input type="text" class="form-control" v-model="formObject['code']"
                        placeholder="Ingrese un código para la distribución" required>
                    <!--end::Input-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-10 fv-row">
                    <!--begin::Label-->
                    <label class="required form-label">Fecha de Creación</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input type="datetime-local" class="form-control" :value="getCurrentDateTime()" readonly>
                    <!--end::Input-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-10 fv-row">
                    <!--begin::Label-->
                    <label class="required form-label">Estatus</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input type="text" class="form-control" value="Pendiente" readonly>
                    <!--end::Input-->
                </div>
                <!--end::Input group-->
            </div>
        </div>
        <!--end::Supply info-->

        <!--begin::Item locations-->
        <div class="card card-xl-stretch mb-xl-8">
            <!--begin::Header-->
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">Resumen de Movimientos</span>
                </h3>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body py-3">
                <!--begin::Table container-->
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table align-middle gs-0 gy-5">
                        <!--begin::Table head-->
                        <thead>
                            <tr>
                                <th class="p-0 min-w-350px text-center fw-bold">Artículos</th>
                                <th class="p-0 min-w-150px text-center fw-bold">Origen</th>
                                <th class="p-0 w-50px text-center"></th>
                                <th class="p-0 min-w-150px text-center fw-bold">Destino</th>
                                <th class="p-0 w-100px text-center fw-bold">Acciones</th>
                            </tr>
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody>
                            <tr v-for="(data, index) in localItems" :key="index">
                                <th>
                                    <div class="d-flex align-items-center overflow-auto item-element item_handle">
                                        <div class="symbol symbol-success me-3">
                                            <img alt="Img" :src="data.item.tertiaryData.first_image || ''"
                                                style="object-fit: cover;" class="w-45px h-80px">
                                        </div>
                                        <div class="d-flex flex-column flex-grow-1">
                                            <span class="text-gray-900-50 fw-bold mb-1"
                                                :title="data.item?.infoData?.name?.split(' / ')[0] || ''">
                                                Código de barras:
                                                <span class="text-gray-900 fw-normal mb-1">
                                                    {{ data.item.tertiaryData.barcode }}
                                                </span>
                                            </span>

                                            <hr style="margin: 0.5em !important; margin-left: 0;">
                                            <div class="d-flex flex-row justify-content-between flex-wrap mb-1">
                                                <span class="badge"
                                                    :style="{ 'background-color': data.item?.tertiaryData?.color || '#ccc', color: isDarkColor(data.item?.tertiaryData?.color) ? '#fff' : '#000' }">
                                                    {{ data.item.tertiaryData.size }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </th>
                                <td>
                                    <input class="form-control" type="text" :value="data.origin?.name || ''" readonly>
                                </td>
                                <td class="text-center">
                                    <i class="ki-outline ki-arrow-right fs-2"></i>
                                </td>
                                <td>
                                    <div v-if="data.editing">
                                        <select class="form-select" v-model="data.destination.id"
                                            @change="updateDestination(index, data.destination.id)">
                                            <option v-for="store in props.stores" :key="store.id" :value="store.id">
                                                {{ store.title }}
                                            </option>
                                        </select>
                                    </div>
                                    <div v-else>
                                        <input class="form-control" type="text" :value="data.destination?.name || ''" readonly>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-icon btn-sm" @click="data.editing = !data.editing">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M11.2728 2.98294L13.0171 4.72637M13.3531 10.8823V13.3529C13.3531 13.7898 13.1795 14.2087 12.8706 14.5176C12.5618 14.8265 12.1428 15 11.706 15H2.64708C2.21024 15 1.7913 14.8265 1.48242 14.5176C1.17353 14.2087 1 13.7898 1 13.3529V4.29401C1 3.85718 1.17353 3.43824 1.48242 3.12935C1.7913 2.82046 2.21024 2.64693 2.64708 2.64693H5.11769M12.3945 1.44704L7.67807 6.16344C7.43437 6.40679 7.26817 6.71684 7.20042 7.05451L6.76476 9.23524L8.94549 8.79876C9.28314 8.73123 9.59279 8.5657 9.83656 8.32193L14.553 3.60553C14.6947 3.4638 14.8071 3.29555 14.8838 3.11037C14.9605 2.92519 15 2.72672 15 2.52628C15 2.32585 14.9605 2.12738 14.8838 1.9422C14.8071 1.75702 14.6947 1.58877 14.553 1.44704C14.4112 1.30531 14.243 1.19288 14.0578 1.11618C13.8726 1.03948 13.6741 1 13.4737 1C13.2733 1 13.0748 1.03948 12.8896 1.11618C12.7045 1.19288 12.5362 1.30531 12.3945 1.44704Z"
                                                    stroke="#5DCA29" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                        <button class="btn btn-icon btn-sm" @click="removeItem(index)">
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M15.375 4.5H2.625M14.1248 6.375L13.7798 11.55C13.647 13.5405 13.581 14.5358 12.9323 15.1425C12.2835 15.75 11.2853 15.75 9.29025 15.75H8.70975C6.71475 15.75 5.7165 15.75 5.06775 15.1425C4.419 14.5358 4.35225 13.5405 4.22025 11.55L3.87525 6.375M7.125 8.25L7.5 12M10.875 8.25L10.5 12"
                                                    stroke="#ED1010" stroke-width="1.5" stroke-linecap="round" />
                                                <path
                                                    d="M4.875 4.5H4.9575C5.25933 4.49229 5.55182 4.39367 5.79669 4.21703C6.04157 4.0404 6.22744 3.79398 6.33 3.51L6.3555 3.43275L6.42825 3.2145C6.4905 3.02775 6.522 2.93475 6.56325 2.85525C6.64441 2.69954 6.76088 2.56499 6.90336 2.46237C7.04583 2.35974 7.21035 2.2919 7.38375 2.26425C7.4715 2.25 7.56975 2.25 7.76625 2.25H10.2338C10.4303 2.25 10.5285 2.25 10.6162 2.26425C10.7896 2.2919 10.9542 2.35974 11.0966 2.46237C11.2391 2.56499 11.3556 2.69954 11.4367 2.85525C11.478 2.93475 11.5095 3.02775 11.5717 3.2145L11.6445 3.43275C11.7395 3.7487 11.9361 4.02451 12.2038 4.21745C12.4714 4.41039 12.7952 4.5097 13.125 4.5"
                                                    stroke="#ED1010" stroke-width="1.5" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <!--end::Table body-->
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Table container-->
            </div>
            <!--end::Body-->
        </div>
        <!--endW::Item locations-->

        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-secondary me-5" @click="props.onCancel"
                :disabled="formObject.processing">
                <span class="indicator-label">Regresar</span>
            </button>
            <button type="button" class="btn btn-primary" @click="onConfirmClick" :disabled="formObject.processing">
                <span class="indicator-label">Confirmar</span>
            </button>
        </div>
    </div>
</template>
