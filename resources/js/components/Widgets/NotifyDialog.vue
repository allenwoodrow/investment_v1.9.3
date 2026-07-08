<template>
<Dialog v-model:visible="visible" modal :header="title" :style="{ width: '25rem' }">
            <p class="text-surface-500 dark:text-surface-400 block mb-8">{{ text }}</p>
            
            <div class="flex justify-center gap-2">
                <Button type="button" label="Close" severity="secondary" @click="visible = false"></Button>
            </div>
        </Dialog>
</template>

<script lang="ts">
import { defineComponent, ref, watch, computed } from 'vue';
import Dialog from 'primevue/dialog';

export default defineComponent({
    props: {
        visible: {
            type: Boolean,
            default: false
        },
        text: {
            type: String,
            default: ''
        },
        title: {
            type: String,
            default: 'System Notification'
        }
    },
    setup(props) {
        const visible = ref<boolean>(false)
        const text = computed(() => {
            return props.text
        })
        const title = computed(() => {
            return props.title
        })

        watch(() => props.visible, (newCount, oldCount) => {
            visible.value = newCount
        });
        return {visible, title, text}
    }

})

</script>