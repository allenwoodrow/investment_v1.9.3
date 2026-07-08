<template>
    <!-- <loading-view :isLoading="isLoading"></loading-view> -->
    <GlobalLoader>
      <CircleSpinner />
    </GlobalLoader>
    <notifications position="top right"/>
    <router-view />
</template>

<script lang="ts">
// @ts-ignore
import { defineComponent, onMounted } from 'vue'
// @ts-ignore
import GlobalLoader from 'vue-global-loader/GlobalLoader.vue'
// @ts-ignore
import CircleSpinner from 'vue-global-loader/CircleSpinner.vue'

// import { initFlowbite } from 'flowbite';
import { Notifications } from '@kyvg/vue3-notification';

export default defineComponent({
  setup() {
    

    return {}
  },
  components: {
    GlobalLoader,
    CircleSpinner,
    Notifications
  },
})
</script>
