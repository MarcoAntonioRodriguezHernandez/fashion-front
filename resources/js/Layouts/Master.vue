<script setup>
import { watch, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const props = defineProps({
    title: { type: String, default: null },
    cardTitle: { type: String, default: null },
});

watch(() => page.props.flash, (flash) => {
    if (flash.success) {
        createToast(flash.success, '', 'success');
    }

    if (flash.error) {
        createToast(flash.error, '', 'error');
    }

    const errors = Object.values(flash.errors ?? {});

    if (errors.length) {
        createToast(errors.map(message => `<div>${message}</div>`).join(''), '', 'error');
    }
});

onMounted(() => {
    document.getElementById('layout-card-title').textContent = props.cardTitle || page.props.appName;
});
</script>

<template>
    <Head>
        <title>{{ title || page.props.appName }}</title>
    </Head>

    <slot></slot>
</template>
