<template>

    <div class="grid">
        <div class="card col-12">
            <div class="inline-flex flex-column w-full gap-2">
                <label for="username" class="text-primary-50 font-semibold">Live Chat Widget Code</label>
                <Textarea v-model="codeForm.code" rows="10" cols="30" class="w-full" />
            </div>

            <div class="flex align-items-center gap-3">
                <!-- <Button label="Close" severity="danger" @click="closeCallback" class="p-3 w-full text-primary-50 border-1 border-white-alpha-30 hover:bg-white-alpha-10"></Button> -->
                <Button label="Save" @click="saveCode" severity="success" class="p-3 w-full text-primary-50 border-1 border-white-alpha-30 hover:bg-white-alpha-10"></Button>

            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, reactive, onMounted } from 'vue'
import { useNetwork } from '@/lib/request'
import { toast } from '@/lib/notifications'
import { useGlobalLoader } from 'vue-global-loader';


export default defineComponent({
    name: 'LiveChat',
    setup() {

        // const code = ref()
        const network = useNetwork(true)
        const { isLoading, displayLoader, destroyLoader } = useGlobalLoader()
        const codeForm = reactive({
            code : ''
        })

        onMounted(() => {
            loadWidget()
        })
        const loadWidget = () => {
            displayLoader()
            network.fetch('office/live_chat', {}).then((response) => {
                const form = {
                    code: response.data
                }
                Object.assign(codeForm, form)
            }).catch((error) => {
                toast.error(error.message!)
            }).finally(() => destroyLoader())
        }

        const saveCode = () => {
            displayLoader()
            network.push('office/save_chat', {code: codeForm.code}).then((response) => {
                console.log(response.data)
                toast.success('Code updated')
            }).catch((error) => {
                toast.error(error.message!)
            }).finally(() => destroyLoader())
        }

        return { saveCode, codeForm }
    }
})

</script>