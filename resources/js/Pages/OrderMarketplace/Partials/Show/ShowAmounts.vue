<script setup>
const props = defineProps({
    totalAmount: { type: Number, required: true, },
    totalPayment: { type: Number, required: true, },
    totalPending: { type: Number, required: true, },
});
</script>

<template>
    <div class="col-12 row text-end mt-8">
        <!--begin::Input group-->
        <div class="col mb-10 text-start">
            <!--begin::Label-->
            <label class="required form-label">Costo Total</label>
            <!--end::Label-->
            <!--begin::Input-->
            <div class="d-flex gap-3">
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input :value="totalAmount.toFixed(2)" type="text" class="form-control" disabled>
                </div>
            </div>
            <!--end::Input-->
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class="col mb-10 text-start">
            <!--begin::Label-->
            <label class="required form-label">Pagado</label>
            <!--end::Label-->
            <!--begin::Input-->
            <div class="d-flex gap-3">
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input :value="totalPayment.toFixed(2)" type="text" class="form-control" disabled>
                </div>
            </div>
            <!--end::Input-->
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class="col mb-10 text-start">
            <!--begin::Label-->
            <label class="required form-label">Saldo Pendiente</label>
            <!--end::Label-->
            <!--begin::Input-->
            <div class="d-flex gap-3">
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input :value="totalPending.toFixed(2)" type="text" class="form-control" disabled>
                </div>
            </div>
            <!--end::Input-->
        </div>
        <!--end::Input group-->
    </div>
</template>
