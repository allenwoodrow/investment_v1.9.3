<template>
    <div class="grid">
        <Card class="col-8">
            <template #title>Debit User</template>
            <template #content>
                <Fieldset legend="User">
                    <p class="m-0">
                        <Dropdown v-model="form.user" :options="users"  filter optionLabel="username" optionValue="id" placeholder="Select a User" class="w-full md:w-14rem" /> 
                    </p>
                </Fieldset>

                <Fieldset legend="Amount">
                    <p class="m-0">
                        <InputText v-model="form.amount" class="w-full" placeholder="Enter Amount in USD" /> 
                    </p>
                </Fieldset>
                

                <div class="flex inline-flex align-items-center align-content-center gap-3">
                    <!-- <Button label="Close" severity="danger" @click="closeCallback" class="p-3 w-full text-primary-50 border-1 border-white-alpha-30 hover:bg-white-alpha-10"></Button> -->
                    <Button label="Save" @click="process" severity="success" class="p-3 w-100 text-primary-50 border-1 border-white-alpha-30 hover:bg-white-alpha-10"></Button>

                </div>
                
            </template>
        </Card>
    </div>
</template>

<script lang="ts">
// @ts-ignore-file
import { defineComponent, ref, reactive, onMounted } from 'vue'
import { useNetwork } from '@/lib/request'
import { toast } from '@/lib/notifications'
import { useGlobalLoader } from 'vue-global-loader';
import Card from 'primevue/card';
import Fieldset from 'primevue/fieldset';


export default defineComponent({
    name: 'AddDebitTransaction',
    setup() {

        const network = useNetwork(true)
        const { displayLoader, destroyLoader } = useGlobalLoader()

        const form = reactive({
            user: '',
            amount: ''
        })

        const users = ref()

        onMounted(() => {
            loadUsers()
        })
        const loadUsers = () => {
            displayLoader()
            network.fetch('office/users', {}).then((response) => {
                console.log(response)
                users.value = response.data
            }).catch((error) => {
                console.log(error)
            }).finally(() => {
                destroyLoader()
            })
        }

        const process = async () => {
            displayLoader()
            network.push<string, {}>('office/debit_account', form).then((response) => {
                toast.success(response.data!)
            }).catch((error: any) => {
                toast.error(error.message)
            }).finally(() => destroyLoader())
        }

        const copy = async (key: string) => {
            if(key.length < 1) {
                toast.error('Key is empty')
                return
            }
            try {
                await navigator.clipboard.writeText("" + key +"");
                toast.info('Copied!!', 'Key copied to clipboard')
            } catch (error) {
                toast.error('Unable to copy')
            }
            return
        }

        return { form, process, copy, users }
    }
})

</script>