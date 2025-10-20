<script setup>
import { default as Auth, PermissionTypes, ModuleAliases } from '@src/Auth.js';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    module: { type: String, required: true, validator: (value) => Object.values(ModuleAliases).includes(value) },
    permissions: { type: Array, required: true, validator: (value) => value.every((v) => Object.values(PermissionTypes).includes(v)) },
});
</script>

<template>
    <slot v-if="Auth.hasAnyPermission(props.module, ...props.permissions)" />
</template>
