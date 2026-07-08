<template>
    <page-header title="Contact Us" page="Company" class="w-full lg:w-full md:w-full"></page-header>

    <div class="surface-section px-4 py-8 md:px-6 lg:px-8">
        <div class="grid">
            <div class="col-12 md:col-6">
                <div class="p-fluid pr-0 md:pr-6">
                    <div class="field">
                        <label for="name" class="font-medium">Name</label>
                        <InputText type="text" v-model="form.name"/>
                        <InlineMessage class="w-full" severity="warn" v-if="v$.name.$error"> {{ v$.name.$errors[0].$message }} </InlineMessage> 
                    </div>

                    <div class="field">
                        <label for="email" class="font-medium">Email</label>
                        <InputText type="text" v-model="form.email"/>
                        <InlineMessage class="w-full" severity="warn" v-if="v$.email.$error"> {{ v$.email.$errors[0].$message }} </InlineMessage> 
                    </div>
                    
                    <div class="field">
                        <label for="message" class="font-medium">Message</label>
                        <Textarea rows="5" cols="30" autoResize v-model="form.message"/>
                        <InlineMessage class="w-full" severity="warn" v-if="v$.message.$error"> {{ v$.message.$errors[0].$message }} </InlineMessage> 
                    </div>
                    <Button class="p-button" type="button" icon="pi pi-envelope" label="Send Message" v-ripple @click="handleForm" />
                </div>
            </div>
            <div id="contact" class="col-12 md:col-6 bg-no-repeat bg-right-bottom">
                <div class="text-900 text-2xl font-medium mb-6">Contact Us</div>
                <div class="text-700 line-height-3 mb-6">Have questions? want to know more? we are available to talk to you.
                </div>
                <ul class="list-none p-0 m-0 mt-6 text-700">
                    <li class="flex align-items-center mb-3"><i
                            class="pi pi-phone mr-2"></i><span> {{  app_phone }}</span></li>
                    <li class="flex align-items-center mb-3"><i
                            class="pi pi-whatsapp mr-2"></i><span>Chat with us</span></li>
                    <li class="flex align-items-center">
                        <i class="pi pi-inbox mr-2"></i><span>{{ app_email }}</span></li>
                </ul>
            </div>
        </div>
    </div>
   
    <div class="surface-section px-4 py-8 md:px-6 lg:px-8">
        <div class="text-700 text-center">
            <!-- <div class="text-blue-600 font-bold mb-3"><i class="pi pi-discord"></i>&nbsp;POWERED BY DISCORD</div> -->
            <div class="text-900 font-bold text-5xl mb-3">Join Our Awesome Community</div>
            <div class="text-700 text-2xl mb-5">Ready to be part of the market moves with a global leader in online
                currency trading?</div>
            <router-link :to="{name: 'Register'}">
                <Button :label="$t('common.joinNow')"
                    class="font-bold px-5 py-3 p-button-raised p-button-rounded white-space-nowrap"></Button>
            </router-link>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, reactive, onMounted, computed } from 'vue';
import Button from 'primevue/button'
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import { PageHeader } from '../components';
import { useGlobalLoader } from 'vue-global-loader'
import { useVuelidate } from '@vuelidate/core'
import { required, email } from '@vuelidate/validators'
import { useNetwork } from '../lib/request';
import { toast } from '@/lib/notifications'

interface ContactForm {
    name: string,
    email: string,
    message: string
}

export default defineComponent({
    setup() {
        const base = import.meta.env.VITE_APP_URL
        const appName = import.meta.env.VITE_APP_NAME
        const app_phone = import.meta.env.VITE_APP_PHONE
        const app_email = import.meta.env.VITE_APP_EMAIL
        const app_address = import.meta.env.VITE_APP_ADDRESS

        const banner = base + 'img/contact-1.png'
        const form = reactive({
            email: '',
            name: '',
            message: ''
        })

        const rules = computed(() => {
            return {
                email: { required, email },
                name: { required },
                message: { required }
            }
        })

        const { displayLoader, destroyLoader, isLoading } = useGlobalLoader()
        const v$ = useVuelidate(rules, form)

        const handleForm = async () => {
            const validation = await v$.value.$validate()
            if(!validation) {
                destroyLoader()
                toast.info('Input Error', 'Please check the form properly')
                
                return
            } else {
                displayLoader()
                const network = useNetwork()
                const formData: ContactForm = {
                    name: form.name,
                    email: form.email,
                    message: form.message
                }
                network.push<Boolean, ContactForm>('submit_contact', formData).then((resp) => {
                    destroyLoader()
                    toast.success('Your message has been received')
                    
                    setTimeout(() => {
                        window.location.href = import.meta.env.VITE_APP_URL + 'company/contact'
                    }, 4000)
                }).catch((error) => {
                    destroyLoader()
                    toast.info('Error', error.message)
                    
                })
            }
        }
        // const margins = base + 'img/marijuana1.png'
        // const leverage = base + 'img/choose4.svg'
        // const liquidity = base + 'img/choose3.svg'

        return { banner, app_phone, app_email, app_address,form, v$, appName, handleForm }
    },
    components: {
        Button, PageHeader, InputText
    }
})
</script>
<!-- 
<style scoped>
    #contact {
        background-image: url( var(--banner) );
    }
</style> -->