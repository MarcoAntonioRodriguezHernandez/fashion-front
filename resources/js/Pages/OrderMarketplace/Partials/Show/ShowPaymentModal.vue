<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
    show: { type: Boolean, default: false },
});

const emit = defineEmits(['update:show', 'close']);

const closeModal = () => {
    emit('update:show', false);
    emit('close');
};
</script>

<template>
  <div v-if="show" class="modal-backdrop fade show"></div>

  <div v-if="show" class="modal fade show d-block" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <!-- Header -->
        <div class="modal-header">
          <h5 class="modal-title">Pago Obligatorio</h5>
        </div>
        <!-- Body -->
        <div class="modal-body">
          <p>Para continuar, debe registrar al menos un pago.</p>
        </div>
        <!-- Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" @click="closeModal">
            Aceptar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.modal-backdrop {
  background-color: rgba(0, 0, 0, 0.5);
}
</style>
