<script setup>
// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    order: { type: Object, required: true }
});
</script>

<template>
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex flex-column align-items-start justify-content-center">
                        <span class="fs-2 text-gray-800 text-hover-primary fw-bold lh-1 mb-2">Detalles de la Orden</span>
                        <span class="text-muted fs-6 fw-semibold lh-1"> {{ order.code }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="table-responsive" v-if="order.items && order.items.length > 0">
                            <table class="table table-row-bordered align-middle gy-0">
                                <thead class="border-bottom border-gray-200 fs-6 fw-bold bg-lighten">
                                    <tr>
                                        <th class="text-start">Artículos</th>
                                        <th class="text-name">Nombre</th>
                                        <th class="text-name">Sucursal</th>
                                        <th class="text-name">Fecha de solicitud</th>
                                        <th class="text-name">Fecha de inicio</th>
                                        <th class="text-name">Fecha final</th>
                                        <th class="text-name">Talla</th>
                                        <th class="text-name">Color</th>
                                        <th class="text-name">Tipo de venta</th>
                                        <th class="text-name">Cantidad de la transacción</th>
                                    </tr>
                                </thead>
                                <tbody class="fs-6 fw-semibold text-gray-600 mb-4">
                                    <tr v-for="item in order.items" :key="item.id">
                                        <td>
                                            <div class="symbol symbol-success me-3 mb-2">
                                                <img :src="item.image" class="w-75px h-100px mt-4">
                                            </div>
                                        </td>
                                        <td class="text-black ps-4">{{ item.name }}</td>
                                        <td>{{ item.store }}</td>
                                        <td>{{ order.request_date }}</td>
                                        <td>{{ item.date_start }}</td>
                                        <td>{{ item.date_end }}</td>
                                        <td>{{ item.size }}</td>
                                        <td>{{ item.color }}</td>
                                        <td>{{ item.sale_type }}</td>
                                        <td>${{ item.full_price }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else>
                            <div class="text-center">
                                <span class="text-muted
                                    fs-3 fw-semibold lh-1">No hay artículos en esta orden</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <a :href="url('order/marketplace/' + order.id)" class="btn btn-primary">View details</a>
                </div>
            </div>
        </div>
    </div>
</template>
