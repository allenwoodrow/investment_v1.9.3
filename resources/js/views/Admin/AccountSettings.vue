<template>
    <div class="px-4 py-8 md:px-6 lg:px-8">
        <div class="surface-card p-4 shadow-2 border-round">
            <div class="font-medium text-3xl text-900 mb-3">
                Admin Profile
            </div>
            <div class="text-500 mb-5">
                You are the investment lord.
            </div>
            <div class="grid grid-nogutter border-top-1 surface-border pt-2">
                <div class="col-12 md:col-6 p-3">
                    <div class="text-500 font-medium mb-2">Name</div>
                    <div class="text-900">Elliot Alderson</div>
                </div>
                <div class="col-12 md:col-6 p-3">
                    <div class="text-500 font-medium mb-2">Email</div>
                    <div class="text-900">elliot.alderson@email.com</div>
                </div>
                <div class="col-12 md:col-6 p-3">
                    <div class="text-500 font-medium mb-2">
                        Manage Password
                    </div>
                    <Button label="Change Password" severity="info" @click="showChange"></Button>
                </div>
                <div class="col-12 md:col-6 p-3">
                    <div class="text-500 font-medium mb-2">
                        Salary Expectation
                    </div>
                    <div class="text-900">$150,000</div>
                </div>
                <div class="col-12 p-3">
                    <div class="text-500 font-medium mb-2">Bio</div>
                    <div class="text-900 line-height-3">
                        Faucibus pulvinar elementum integer enim neque volutpat
                        ac tincidunt vitae. Commodo odio aenean sed adipiscing
                        diam donec adipiscing tristique. Eget felis eget nunc
                        lobortis mattis aliquam faucibus purus in.
                    </div>
                </div>
                <div class="col-12 p-3">
                    <div class="text-500 font-medium mb-3">Files</div>
                    <div
                        class="flex md:align-items-center md:justify-content-between border-top-1 surface-border p-3 flex-column md:flex-row"
                    >
                        <div class="flex align-items-center">
                            <span class="block pi pi-file mr-2"></span
                            ><span class="text-900">resume_en.pdf</span>
                        </div>
                        <button
                            class="p-button p-component p-button-outlined mt-2 md:mt-0"
                            type="button"
                            aria-label="Download"
                            data-pc-name="button"
                            data-pc-section="root"
                            data-pd-ripple="true"
                        >
                            <span
                                class="p-button-icon p-button-icon-left pi pi-download"
                                data-pc-section="icon"
                            ></span
                            ><span
                                class="p-button-label"
                                data-pc-section="label"
                                >Download</span
                            ><!----><span
                                role="presentation"
                                aria-hidden="true"
                                data-p-ink="true"
                                data-p-ink-active="false"
                                class="p-ink"
                                data-pc-name="ripple"
                                data-pc-section="root"
                            ></span>
                        </button>
                    </div>
                    <div
                        class="flex md:align-items-center md:justify-content-between border-top-1 border-bottom-1 surface-border p-3 flex-column md:flex-row"
                    >
                        <div class="flex align-items-center">
                            <span class="block pi pi-file mr-2"></span
                            ><span class="text-900">cover_letter_en.pdf</span>
                        </div>
                        <button
                            class="p-button p-component p-button-outlined mt-2 md:mt-0"
                            type="button"
                            aria-label="Download"
                            data-pc-name="button"
                            data-pc-section="root"
                            data-pd-ripple="true"
                        >
                            <span
                                class="p-button-icon p-button-icon-left pi pi-download"
                                data-pc-section="icon"
                            ></span
                            ><span
                                class="p-button-label"
                                data-pc-section="label"
                                >Download</span
                            ><!----><span
                                role="presentation"
                                aria-hidden="true"
                                data-p-ink="true"
                                data-p-ink-active="false"
                                class="p-ink"
                                data-pc-name="ripple"
                                data-pc-section="root"
                            ></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Dialog v-model:visible="paymentModal" modal :pt="{ root: 'border-none', mask: { style: 'backdrop-filter: blur(2px)'}}">
        <template #container="{ closeCallback }">
            <div class="flex flex-column px-5 py-5 gap-4" style="border-radius: 12px; background-image: radial-gradient(circle at left top, var(--primary-400), var(--primary-700))">
                <svg width="35" height="40" viewBox="0 0 35 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="block mx-auto">
                    <path
                        d="M25.87 18.05L23.16 17.45L25.27 20.46V29.78L32.49 23.76V13.53L29.18 14.73L25.87 18.04V18.05ZM25.27 35.49L29.18 31.58V27.67L25.27 30.98V35.49ZM20.16 17.14H20.03H20.17H20.16ZM30.1 5.19L34.89 4.81L33.08 12.33L24.1 15.67L30.08 5.2L30.1 5.19ZM5.72 14.74L2.41 13.54V23.77L9.63 29.79V20.47L11.74 17.46L9.03 18.06L5.72 14.75V14.74ZM9.63 30.98L5.72 27.67V31.58L9.63 35.49V30.98ZM4.8 5.2L10.78 15.67L1.81 12.33L0 4.81L4.79 5.19L4.8 5.2ZM24.37 21.05V34.59L22.56 37.29L20.46 39.4H14.44L12.34 37.29L10.53 34.59V21.05L12.42 18.23L17.45 26.8L22.48 18.23L24.37 21.05ZM22.85 0L22.57 0.69L17.45 13.08L12.33 0.69L12.05 0H22.85Z"
                        fill="var(--primary-700)"
                    />
                    <path
                        d="M30.69 4.21L24.37 4.81L22.57 0.69L22.86 0H26.48L30.69 4.21ZM23.75 5.67L22.66 3.08L18.05 14.24V17.14H19.7H20.03H20.16H20.2L24.1 15.7L30.11 5.19L23.75 5.67ZM4.21002 4.21L10.53 4.81L12.33 0.69L12.05 0H8.43002L4.22002 4.21H4.21002ZM21.9 17.4L20.6 18.2H14.3L13 17.4L12.4 18.2L12.42 18.23L17.45 26.8L22.48 18.23L22.5 18.2L21.9 17.4ZM4.79002 5.19L10.8 15.7L14.7 17.14H14.74H15.2H16.85V14.24L12.24 3.09L11.15 5.68L4.79002 5.2V5.19Z"
                        fill="var(--primary-200)"
                    />
                </svg>
                <div class="inline-flex flex-column gap-2">
                    <InlineMessage severity="info">Change Admin Password</InlineMessage>
                </div>


                <div class="inline-flex flex-column gap-2">
                    <label for="username" class="text-primary-50 font-semibold">Authorization Code</label>
                    <InputText v-model="passwordForm.code" placeholder="eg: 6331AR4"></InputText>

                </div>  
                
                <div class="inline-flex flex-column gap-2">
                    <label for="username" class="text-primary-50 font-semibold">Authorization Type</label>
                    <Dropdown v-model="passwordForm.codeType" :options="codeTypes" optionValue="id" optionLabel="name" placeholder="Select a Code Type" class="w-full md:w-14rem" /> 

                </div>  

                <div class="inline-flex flex-column gap-2">
                    <label for="username" class="text-primary-50 font-semibold">Authorization User</label>
                    <Dropdown v-model="passwordForm.user" :options="customers"  filter optionLabel="username" optionValue="id" placeholder="Select a User" class="w-full md:w-14rem" /> 

                </div>  

                
                
                <div class="flex align-items-center gap-3">
                    <Button label="Close" severity="danger" @click="closeCallback" class="p-3 w-full text-primary-50 border-1 border-white-alpha-30 hover:bg-white-alpha-10"></Button>
                    <Button label="Save" @click="createCode" severity="success" class="p-3 w-full text-primary-50 border-1 border-white-alpha-30 hover:bg-white-alpha-10"></Button>

                </div>

            </div>
        </template>
    </Dialog>
</template>

<script lang="ts">
import { defineComponent, ref, reactive } from "vue";
import Button from 'primevue/button'

export default defineComponent({
    name: "AccountSettings",
    setup() {
        const paymentModal = ref(false)
        const passwordForm = reactive({
            old_password: '',
            new_password: '',
            repeat_password: ''
        });



        
        return { paymentModal, passwordForm };
    },
    components: {
        Button
    },
});
</script>
