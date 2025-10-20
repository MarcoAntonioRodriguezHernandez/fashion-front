<script setup>
import { router } from '@inertiajs/vue3';
import { computed } from 'vue';
import Popper from 'vue3-popper';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    order: { type: Object, required: true },
    orderStatuses: { type: Object, required: true },
    employee: { type: Object, required: true },
    client: { type: Object, required: true },
    shipping: { type: Object, default: null },
    items: { type: Object, required: true },
});

// ------------------------
// Declarations
// ------------------------

const submitOrderStatusUpdate = (orderId, newStatus) => {
    router.put(`/order/marketplace/${orderId}/status`, {
        orderStatus: newStatus,
    }, {
        onSuccess: (page) => {
            const surcharge = page.props.flash.data.data.surcharge;

            if (surcharge)
                createAlert('Los recargos de la orden se han actualizado a un total de $' + surcharge, 'Agregue el pago o un descuento con el concepto "Condonación de recargos"', 'warning');
        }
    });
}

const isDisabled = computed(() => {
    return !props.order.is_enabled || [7].includes(props.order.status.value);
});

const docsTypes = Object.freeze({
    'Nota': url('/order/marketplace/' + props.order.id + '/docsale/noteSale'),
});
console.log('docsTypes', docsTypes);
</script>

<template>
    <div class="row d-flex align-items-stretch">
        <div class="col-md-6 d-flex my-4">
            <div class="card card-flush py-4 w-100">
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title">
                        <h2>Detalles de la orden #{{ order.code }}</h2>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                            <tbody class="fw-semibold text-gray-600">
                                <tr>
                                    <td class="text-muted">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-calendar fs-2 me-2"></i>Fecha y hora de creación
                                        </div>
                                    </td>
                                    <td class="fw-bold text-end text-primary">
                                        <span style="margin-right:2em;">{{ order.created_at }}</span>
                                        <span>{{ order.hour_created }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-shop fs-2 me-2"></i>Sucursal
                                        </div>
                                    </td>
                                    <td class="fw-bold text-end text-primary">
                                        {{ order.store.name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-truck  fs-2 me-2"></i> Cambiar estatus
                                        </div>
                                    </td>
                                    <td v-if="!isDisabled" class="fw-bold text-end text-primary">
                                        <Popper placement="left" :interactive="false">
                                            <div class="btn-group dropstart" title="Actualizar status de la orden">
                                                <button :class="['btn dropdown-toggle py-2 px-5', 'btn-' + order.status.color]" type="button">
                                                    {{ order.status.name }}
                                                </button>
                                            </div>

                                            <template #content>
                                                <div style="padding: 1rem 0.7rem;">
                                                    <ul class="list-group list-group-flush">
                                                        <button style="background-color: transparent;" v-for="name, value in orderStatuses" @click="submitOrderStatusUpdate(order.id, value)" class="list-group-item py-1 fs-small popper-bg">
                                                            {{ name }}
                                                        </button>
                                                    </ul>
                                                </div>
                                            </template>
                                        </Popper>
                                    </td>
                                    <td v-else>
                                        <div class="col-12 text-end">
                                            <span :class="['btn py-2 px-5 disabled', 'btn-' + order.status.color]">
                                                {{ order.status.name }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted text-start">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-calendar-8 fs-2 me-2"></i>Evento
                                        </div>
                                    </td>
                                    <td class="fw-bold text-end text-primary">
                                        {{ order.event_label }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-profile-circle fs-2 me-2"></i>Responsable
                                        </div>
                                    </td>
                                    <td class="fw-bold text-end">
                                        <div class="d-flex align-items-center justify-content-end">
                                            <!--begin:: Avatar -->
                                            <div class="symbol symbol-circle symbol-25px overflow-hidden me-3">
                                                <a>
                                                    <div class="symbol-label">
                                                        <img :src="employee.photo" alt="Dan Wilson" class="w-100" />
                                                    </div>
                                                </a>
                                            </div>
                                            <!--end::Avatar-->
                                            <!--begin::Name-->
                                            <a :href="`/users/${employee.id}`" class="fw-bold text-end text-primary">{{ employee.full_name }}</a>
                                            <!--end::Name-->
                                        </div>
                                    </td>
                                </tr>
                                <tr  v-if="order.sale_type_names?.[0] === 'Venta'">
                                    <td class="text-muted">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-cheque fs-2 me-2"></i>Ticket de venta
                                        </div>
                                    </td>
                                    <td class="fw-bold text-end">
                                        <div class="d-flex align-items-center justify-content-end">
                                            <a v-for="docUrl, docName in docsTypes" class="btn btn-secondary mb-2 mx-2" :href="docUrl" target="_blank">{{ docName }}</a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!--end::Table-->
                    </div>
                </div>
                <!--end::Card body-->
            </div>
        </div>
        <div class="col-md-6 d-flex my-4">
            <div class="card card-flush py-4 w-100">
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title">
                        <h2>Detalles del cliente</h2>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                            <tbody class="fw-semibold text-gray-600">
                                <tr>
                                    <td class="text-muted">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-profile-circle fs-2 me-2"></i>Cliente
                                        </div>
                                    </td>
                                    <td class="fw-bold text-end">
                                        <div class="d-flex align-items-center justify-content-end">
                                            <!--begin:: Avatar -->
                                            <div class="symbol symbol-circle symbol-25px overflow-hidden me-3">
                                                <a>
                                                    <div class="symbol-label">
                                                        <img :src="client.photo" alt="Dan Wilson" class="w-100" />
                                                    </div>
                                                </a>
                                            </div>
                                            <!--end::Avatar-->
                                            <!--begin::Name-->
                                            <a :href="`/users/${client.id}`" class="fw-bold text-end text-primary">{{ client.full_name }}</a>
                                            <!--end::Name-->
                                        </div>
                                    </td>
                                </tr>
                                <!-- gender -->
                                <tr>
                                    <td class="text-muted text-start">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-user-square fs-2 me-2"></i>Género
                                        </div>
                                    </td>
                                    <td class="text-end text-primary">
                                        <a class="fw-bold text-end text-primary">{{ client.gender }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-sms fs-2 me-2"></i>Correo
                                        </div>
                                    </td>
                                    <td class="fw-bold text-end">
                                        <a class="fw-bold text-end text-primary">{{ client.email }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-phone fs-2 me-2"></i>Telefono
                                        </div>
                                    </td>
                                    <td class="fw-bold text-end text-primary">{{ client.phone }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted text-start">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-search-list fs-2 me-2"></i>Encontro Conspiracion Moda en
                                        </div>
                                    </td>
                                    <td class="fw-bold text-end text-primary">
                                        {{ order.found_by }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!--end::Table-->
                    </div>
                </div>
                <!--end::Card body-->
            </div>
        </div>
    </div>
    <div class="row d-flex align-items-stretch">
        <div class="col-md-6 d-flex my-4">
            <div class="card card-flush flex-row-fluid position-relative w-100">
                <!--begin::Background-->
                <div class="position-absolute top-0 end-0 bottom-0 opacity-10 d-flex align-items-center me-5">
                    <i class="ki-solid ki-two-credit-cart" style="font-size: 14em"></i>
                </div>
                <!--end::Background-->
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title">
                        <h2>Totales</h2>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                            <tbody class="fw-semibold text-gray-600">
                                <tr>
                                    <td class="text-muted text-start">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-dollar fs-2 me-2"></i>Cantidad total
                                        </div>
                                    </td>
                                    <td class="text-end text-primary">
                                        <a class="fw-bold text-end text-primary">${{ order.amount }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted text-start">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-discount fs-2 me-2"></i>Descuento
                                        </div>
                                    </td>
                                    <td class="text-end text-primary">
                                        <a class="fw-bold text-end text-primary">${{ order.discount }}</a>
                                    </td>
                                </tr>
                                <tr v-if="order.surcharge">
                                    <td class="text-muted text-start">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-bill fs-2 me-2"></i>Recargos
                                        </div>
                                    </td>
                                    <td class="text-end text-primary">
                                        <a class="fw-bold text-end text-primary">${{ order.surcharge }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted text-start">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-wallet fs-2 me-2"></i>Saldo Pendiente
                                        </div>
                                    </td>
                                    <td class="text-end text-primary">
                                        <a class="fw-bold text-end text-primary">${{ order.amount_total - order.total_payment }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted text-start">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-bill fs-2 me-2"></i>Anticipo
                                        </div>
                                    </td>
                                    <td class="text-end text-primary">
                                        <a class="fw-bold text-end text-primary">${{ order.total_payment }}</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!--end::Table-->
                    </div>
                </div>
                <!--end::Card body-->
            </div>
        </div>
        <div class="col-md-6 d-flex my-4">
            <div class="card card-flush flex-row-fluid position-relative">
                <!--begin::Background-->
                <div class="position-absolute top-0 end-0 bottom-0 opacity-10 d-flex align-items-center me-5">
                    <i class="ki-solid ki-delivery" style="font-size: 13em"></i>
                </div>
                <!--end::Background-->
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title">
                        <h2>Envío</h2>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body p-9 pt-0" v-if="shipping">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                            <tbody class="fw-semibold text-gray-600">
                                <tr>
                                    <td class="text-muted text-start">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-geolocation-home fs-2 me-2"></i>Calle
                                        </div>
                                    </td>
                                    <td class="text-end text-primary">
                                        <a class="fw-bold text-end text-primary">{{ shipping.user_address.street }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted text-start">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-delivery-geolocation fs-2 me-2"></i>Colonia y estado
                                        </div>
                                    </td>
                                    <td class="text-end text-primary">
                                        <a class="fw-bold text-end text-primary">{{ shipping.user_address.colony }} - {{ shipping.user_address.state }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted text-start">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-map fs-2 me-2"></i>Ciudad y Código postal
                                        </div>
                                    </td>
                                    <td class="text-end text-primary">
                                        <a class="fw-bold text-end text-primary">{{ shipping.user_address.city }} - {{ shipping.user_address.zip_code }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted text-start">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-delivery-3 fs-2 me-2"></i>Cargo Extra
                                        </div>
                                    </td>
                                    <td class="text-end text-primary">
                                        <a class="fw-bold text-end text-primary">${{ shipping.shipping_price.price }}</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div v-else>
                    <div colspan="5" class="fw-semibold text-gray-800 text-center lh-lg text-center pt-12">
                        <h2 class="mt-12">No se solicitó envío</h2>
                    </div>
                </div>
                <!--end::Card body-->
            </div>
        </div>
    </div>
</template>
