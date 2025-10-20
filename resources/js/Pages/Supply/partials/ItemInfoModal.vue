<script setup>
import { ref, onMounted } from 'vue';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
});

defineExpose({
    showWithInput: (item) => {
        infoData.value = item.infoData;

        if (infoData.value == null)
            throw new Error('Item data is null');

        modal.show();
    },
});

onMounted(() => {
    modal = new bootstrap.Modal(infoModalRoot.value);
});

// ------------------------
// Declarations
// ------------------------

let modal = null;
const infoModalRoot = ref(null);
const infoData = ref({});
</script>

<template>
    <div ref="infoModalRoot" class="modal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modelo {{ infoData.name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4>Sucursal</h4>

                    <div class="row row-cols1 row-cols-lg-2">
                        <!--begin::Input group-->
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="form-label" disabled>Sucursal actual</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <div class="d-flex gap-3">
                                <input v-model="infoData.current_store_name" class="form-control" type="text" disabled>
                            </div>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="form-label">Mover a sucursal</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <div class="d-flex gap-3">
                                <input v-model="infoData.target_store_name" class="form-control" type="text" disabled>
                            </div>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                    </div>

                    <hr>

                    <h3>Historial Sucursales</h3>

                    <div class="col-12 table-responsive mt-4">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Sucursal</th>
                                    <th>Duración</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="location in infoData.supplies">
                                    <td>{{ location.date }}</td>
                                    <td>{{ location.store_name }}</td>
                                    <td>{{ location.elapsed.weeks }} semanas, {{ location.elapsed.days }} días</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
</template>
