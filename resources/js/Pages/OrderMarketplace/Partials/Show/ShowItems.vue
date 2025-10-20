<script setup>
// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    items: { type: Array, required: true },
    onItemEdit: { type: Function, required: true },
});

const formatDate = (dateString) => {
    if (!dateString) return 'N/A';


    const isoMatch = dateString.match(/^(\d{4})-(\d{2})-(\d{2})T/);
    if (isoMatch) {
        const [, year, month, day] = isoMatch;
        return `${day}/${month}/${year}`; // format data as DD/MM/YYYYs
    }

    return 'N/A';
};


</script>

<template>
    <div class="card card-flush">
        <div class="card-body">
            <h1>Artículos de la orden</h1>
            <div class="row mt-5">
                <div class="table-responsive">
                    <table class="table table-row-bordered table-row-dashed gy-4 align-middle fw-bold">
                        <thead class="fs-7 text-gray-500 text-uppercase">
                            <tr>
                                <th class="text-start">Imagen</th>
                                <th class="text-center">Producto</th>
                                <th class="text-center">Código de Barras</th>
                                <th class="text-center">Variante</th>
                                <th class="text-center">Rango de renta</th>
                                <th class="text-center">Transacción</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in items" :key="item.id"
                                :class="{ 'text-muted bg-light': item.item_order_status == 0 }">
                                <td><a :href="url('/item/' + item.id + '/edit')">
                                        <img :src="item.first_image" class="rounded-3" :alt="item.product_full_name"
                                            width="75">
                                    </a>
                                </td>
                                <td class="text-center">
                                    {{ item.product_name }} <br>
                                    {{ item.designer_name }}
                                </td>
                                <td class="text-center">{{ item.barcode || 'No asignado' }}</td>
                                <td class="text-center">
                                    {{ item.color_name }} <br>
                                    {{ item.size_name }}
                                </td>
                                <td class="text-center">
                                    <template v-if="item.order_detail?.rent_detail">
                                        {{ formatDate(item.order_detail.rent_detail.rent_start) }} - {{ formatDate(item.order_detail.rent_detail.rent_end) }}
                                    </template>
                                    <template v-else>
                                        N/A
                                    </template>
                                </td>
                                <td class="text-center">
                                    {{ item.sale_type_name }}<br>
                                    $ {{ item.price }}
                                </td>
                                <td class="text-center">
                                    <template v-if="item.item_order_status == 0">
                                        <span data-bs-toggle="tooltip" title="Este artículo ha sido cancelado">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                fill="currentColor" class="bi bi-exclamation-circle text-warning"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                                <path
                                                    d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
                                            </svg>
                                        </span>
                                    </template>
                                    <template v-else-if="item.order_detail.sale_type == 2">
                                        <a :href="url('item/order/marketplace/' + item.order_detail.id + '/edit')"
                                            class="btn px-2">
                                            <i class="ki-duotone ki-arrow-right-left fs-2 text-info">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </a>
                                        <button class="btn px-2" @click="onItemEdit(item)">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M11.2728 2.98294L13.0171 4.72637M13.3531 10.8823V13.3529C13.3531 13.7898 13.1795 14.2087 12.8706 14.5176C12.5618 14.8265 12.1428 15 11.706 15H2.64708C2.21024 15 1.7913 14.8265 1.48242 14.5176C1.17353 14.2087 1 13.7898 1 13.3529V4.29401C1 3.85718 1.17353 3.43824 1.48242 3.12935C1.7913 2.82046 2.21024 2.64693 2.64708 2.64693H5.11769M12.3945 1.44704L7.67807 6.16344C7.43437 6.40679 7.26817 6.71684 7.20042 7.05451L6.76476 9.23524L8.94549 8.79876C9.28314 8.73123 9.59279 8.5657 9.83656 8.32193L14.553 3.60553C14.6947 3.4638 14.8071 3.29555 14.8838 3.11037C14.9605 2.92519 15 2.72672 15 2.52628C15 2.32585 14.9605 2.12738 14.8838 1.9422C14.8071 1.75702 14.6947 1.58877 14.553 1.44704C14.4112 1.30531 14.243 1.19288 14.0578 1.11618C13.8726 1.03948 13.6741 1 13.4737 1C13.2733 1 13.0748 1.03948 12.8896 1.11618C12.7045 1.19288 12.5362 1.30531 12.3945 1.44704Z"
                                                    stroke="#5DCA29" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </template>
                                    <div v-else>
                                        <i class="ki-duotone ki-abstract-11 fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>