<template>
    <admin-menu></admin-menu>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';

import { default as AdminMenu } from '@/components/Headers/AdminMenu.vue'

export default defineComponent({
    components: {
        AdminMenu
    },
    setup() {

        return { }
    },
})
</script>