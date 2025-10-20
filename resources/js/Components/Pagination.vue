<script setup>
import { computed } from 'vue';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    links: { type: Array, required: true },
    onPageClick: { type: Function, required: true },
});

// ------------------------
// Declarations
// ------------------------

const paginationInfo = computed(() => {
    return props.links.map(link => {
        return {
            active: link.active,
            label: link.label,
            url: link.url,
            page: link.url ? (new URLSearchParams((new URL(link.url)).search)).get('page') : null,
        };
    });
});
</script>

<template>
    <nav v-if="paginationInfo.length > 0">
        <ul class="pagination pagination-sm">
            <li v-for="link in paginationInfo" :class="{ 'text-nowrap': true, 'page-item': true, 'disabled': link.url == null, 'active': link.active }">
                <button v-if="link.url != null" @click="onPageClick(link.page)" v-html="link.label" class="page-link"></button>

                <span v-else class="page-link" v-html="link.label"></span>
            </li>
        </ul>
    </nav>
</template>
