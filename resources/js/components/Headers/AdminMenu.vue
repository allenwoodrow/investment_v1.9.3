<template>
    <ul class="layout-menu">
        <template v-for="(item, i) in items" :key="item">
            <menu-item v-if="!item.separator" :item="item" :index="i"></menu-item>
            <li v-if="item.separator" class="menu-separator"></li>
        </template>
    </ul>


    <Dialog v-model:visible="passwordModal" modal :pt="{ root: 'border-none', mask: { style: 'backdrop-filter: blur(2px)'}}">
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
                    <label for="password1" class="block text-900 font-medium text-xl">Password</label>
                    <Password id="password1" v-model="passwordForm.old_password" placeholder="Old Password" :toggleMask="true" class="w-full mb-2" inputClass="w-full" :inputStyle="{ padding: '1rem' }" :feedback="false"></Password>
                    <InlineMessage class="w-full" severity="warn" v-if="v$.old_password.$error"> {{ v$.old_password.$errors[0].$message }} </InlineMessage> 

                </div>  
                
                <div class="inline-flex flex-column gap-2">
                    <label for="username" class="text-primary-50 font-semibold">New Password</label>
                    <Password id="password2" v-model="passwordForm.password" placeholder="New Password" :toggleMask="true" class="w-full mb-2" inputClass="w-full" :inputStyle="{ padding: '1rem' }" :feedback="true"></Password>
                    <InlineMessage class="w-full" severity="warn" v-if="v$.password.$error"> {{ v$.password.$errors[0].$message }} </InlineMessage> 
                </div>  

                <div class="inline-flex flex-column gap-2">
                    <label for="username" class="text-primary-50 font-semibold">Repeat Password</label>
                    <Password id="password3" v-model="passwordForm.password_confirmation" placeholder="Confirm Password" :toggleMask="true" class="w-full mb-2" inputClass="w-full" :inputStyle="{ padding: '1rem' }" :feedback="false"></Password>
                    <InlineMessage class="w-full" severity="warn" v-if="v$.password_confirmation.$error"> {{ v$.password_confirmation.$errors[0].$message }} </InlineMessage> 
                </div>  

                <div class="flex align-items-center gap-3">
                    <Button label="Close" severity="danger" @click="closeCallback" class="p-3 w-full text-primary-50 border-1 border-white-alpha-30 hover:bg-white-alpha-10"></Button>
                    <Button label="Save" @click="changePass" severity="success" class="p-3 w-full text-primary-50 border-1 border-white-alpha-30 hover:bg-white-alpha-10"></Button>

                </div>

            </div>
        </template>
    </Dialog>

    <Dialog v-model:visible="betaModal" modal :pt="{ root: 'border-none', mask: { style: 'backdrop-filter: blur(2px)'}}">
        <template #container="{ closeCallback }">
            <div class="flex flex-column px-5 py-5 gap-4" style="border-radius: 12px; background-image: radial-gradient(circle at left top, var(--primary-400), var(--primary-700))">
                
                <div class="inline-flex flex-column gap-2">
                    <InlineMessage severity="info">This feature is currently in beta development and will be available soon. </InlineMessage>
                </div>
            </div>
        </template>
    </Dialog>
    
</template>


<script lang="ts">
import { defineComponent, ref, reactive, computed } from 'vue';
import { type MenuGroup } from '@/types/UI';
// @ts-ignore
import { default as MenuItem } from './AppMenuItem.vue'
import { useVuelidate } from '@vuelidate/core'
import { required, minLength, sameAs, helpers } from '@vuelidate/validators'
import { useGlobalLoader } from 'vue-global-loader'
import { useNetwork } from '@/lib/request'
import { toast } from '@/lib/notifications'
import { useAdmin } from '@/lib/auth'

// @ts-ignore-file
export default defineComponent({
    name: 'AdminMenu',
    setup() {
        const homeMenu: MenuGroup = {
            label: 'Home',
            items: [
                { label: 'Dashboard', icon: 'pi pi-fw pi-home', to: {name: 'AdminDashboard'} },
            ]
        }
        const UserMenu: MenuGroup = {
            label: 'User Management',
            items: [
                {label: 'Active Users', icon: 'pi pi-users', to: {name: 'Users', params: { option: 'active'} }},
            ]
        }

        const AccountMenu: MenuGroup = {
            label: 'Plans Management',
            items: [
                {label: 'View Plans', icon: 'pi pi-star', to: {name: 'ViewPlans'} },
            ]
        }

        const fundingMenu: MenuGroup = {
            label: 'Account Funding',
            items: [
                {label: 'Credit Account', icon: 'pi pi-upload', to: { name: 'CreditAccount'}},
                {label: 'Debit Account', icon: 'pi pi-download', to: { name: 'DebitAccount' }}
            ]
        }
        const depositMenu: MenuGroup = {
            label: 'Deposits Management',
            icon: 'pi pi-database',
            items: [
                {label: 'Pending Deposits', icon: 'pi pi-hourglass', to: {name: 'ViewDeposits', params: { type: 'pending'}} },
                {label: 'Approved Deposits', icon: 'pi pi-check', to: {name: 'ViewDeposits', params: { type: 'approved'}} },
                {label: 'Cancelled Deposits', icon: 'pi pi-times-circle', to: {name: 'ViewDeposits', params: {type: 'cancelled'}} }
            ],
        }

        const investmentMenu: MenuGroup = {
            label: 'Investments Management',
            items: [
                {label: 'Pending', icon: 'pi pi-hourglass', to: {name: 'ViewInvestments', params: { type: 'pending'}} },
                {label: 'Approved', icon: 'pi pi-check', to: {name: 'ViewInvestments', params: { type: 'approved'}} },
                {label: 'Cancelled', icon: 'pi pi-times-circle', to: {name: 'ViewInvestments', params: {type: 'cancelled'}} }
            ],
        }

        const withDrawMenu: MenuGroup = {
            label: 'Withdrawals Management',
            items: [
                {
                    label: 'Wallet Withdrawals', 
                    icon: 'pi pi-wallet', 
                    items:[
                        {label: 'Pending', icon: 'pi pi-hourglass', to: {name: 'ViewWalletWithdrawals', params: { type: 'pending'}} },
                        {label: 'Approved', icon: 'pi pi-check', to: {name: 'ViewWalletWithdrawals', params: { type: 'approved'}} },
                        {label: 'Cancelled', icon: 'pi pi-times-circle', to: {name: 'ViewWalletWithdrawals', params: {type: 'cancelled'}} }
                    ] 
                },
                {
                    label: 'Bank Withdrawals',
                    icon: 'pi pi-building',
                    items: [
                        {label: 'Pending', icon: 'pi pi-hourglass', to: {name: 'ViewBankWithdrawals', params: { type: 'pending'}} },
                        {label: 'Approved', icon: 'pi pi-check', to: {name: 'ViewBankWithdrawals', params: { type: 'approved'}} },
                        {label: 'Cancelled', icon: 'pi pi-times-circle', to: {name: 'ViewBankWithdrawals', params: {type: 'cancelled'}} }
                    ]
                }
            ],
        }

        const billingCodes: MenuGroup = {
            label: 'Billing Code Management',
            items: [
                {
                    label: 'Withdrawal OTP',
                    icon: 'pi pi-key',
                    to: { name: 'AuthManagement'}
                },
                {
                    label: 'Billing Types', 
                    icon: 'pi pi-list', 
                    to: { name: 'ViewBillingTypes' }
                },
                {
                    label: 'Billing Codes', 
                    icon: 'pi pi-file', 
                    to: { name: 'ViewBillingCodes'}
                }
            ]
        }

        const contactForm: MenuGroup = {
            label: 'Contact Form',
            items: [
                {
                    label: 'Messages', 
                    icon: 'pi pi-envelope', 
                    to: { name: 'ViewMessages' }
                }
            ]
        }

        const settingsMenu: MenuGroup = {
            label: 'Settings',
            items: [
                {label: 'Change Password', icon: 'pi pi-lock', callback: () => showChange() },
                {label: 'Live Chat', icon: 'pi pi-comments', to: {name: 'LiveChat'} },
                {label: 'Payment Gateway', icon: 'pi pi-bolt', to: {name: 'PaymentGateway'}},
            ]
        }

        const notificationsMenu: MenuGroup = {
            label: 'Notifications',
            items: [
                {label: 'Telegram ', icon: 'pi pi-telegram', beta: true, class: 'p-overlay-badge'},
                {label: 'Email', icon: 'pi pi-email', beta: true, class: 'p-overlay-badge'}
            ]
        }
        
        const items = ref([
            homeMenu,
            UserMenu,
            AccountMenu,
            fundingMenu,
            depositMenu,
            investmentMenu,
            withDrawMenu,
            billingCodes,
            contactForm,
            settingsMenu,
            notificationsMenu
        ]);

        const passwordModal = ref<boolean>(false)
        const betaModal = ref<boolean>(false)
        const passwordForm = reactive({
            old_password: '',
            password: '',
            password_confirmation: '',
        })
        const network = useNetwork(true)

        const rules = computed(() => {
            return {
                old_password: { required },
                password: { required, minLength: minLength(8) },     
                password_confirmation: { required, sameAs: helpers.withMessage('Passwords do not match', sameAs(passwordForm.password)) },
            }
        })

        const { displayLoader, destroyLoader } = useGlobalLoader()
        const v$ = useVuelidate(rules, passwordForm)

        const showChange = () => {
            passwordModal.value = true
        }

        
        const changePass = async () => {
            const validate = await v$.value.$validate()

            if(!validate) {
                toast.info('Error', 'Invalid password provided')
                console.log('validation failed')
                return
            }
            displayLoader()
            network.push<string, {}>('office/change_password', passwordForm).then((response) => {
                toast.success(response.data!)
                passwordModal.value = false
                // authHander.lo
                // authHandler.updateToken(response.data.token)
            }).catch((error) => {
                toast.error(error.message!)
            }).finally(() => destroyLoader())
        }

        return { v$, items, changePass, showChange, passwordModal, passwordForm, betaModal }
    },
    components: {
        MenuItem
    }
})
</script>