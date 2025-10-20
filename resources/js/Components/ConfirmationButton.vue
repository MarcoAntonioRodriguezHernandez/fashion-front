<script setup>
import { router } from '@inertiajs/vue3';
import { fullAlert } from '@src/utils';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    title: { type: String, required: true },
    text: { type: String, required: true },
    route: { type: String, required: true },
    buttonText: { type: String, default: 'Confirmar' },
    onSuccess: { type: Function, default: () => ({}) },
    onError: { type: Function, default: () => ({}) },
    method: { type: String, default: 'delete' },
});

// ------------------------
// Declarations
// ------------------------

const onButtonClick = () => {
    fullAlert({
        title: props.title,
        text: props.text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: props.buttonText,
        cancelButtonText: 'Cancelar',
        allowOutsideClick: false,
    }).then((result) => {
        if (result.isConfirmed) {
            router.visit(props.route, {
                method: props.method,
                preserveState: true,
                onSuccess: props.onSuccess,
                onError: props.onError,
            });
        }
    });
}
</script>

<template>
    <button class="border-0 bg-transparent" @click="onButtonClick">
        <slot />
    </button>
</template>
