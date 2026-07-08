<template>
<div class="grid p-1">
    <Card class="xl:col-9 md:col-9 lg:col-9 col-12 mx-1 my-1">
        <template #title>{{ $t('app.bankWithdraw.title') }}</template>
        <template #subtitle>{{ $t('app.bankWithdraw.subtitle') }}</template>
        <template #content>
            <Stepper orientation="vertical" :activeStep="step" :linear="false">
                <StepperPanel :header="$t('app.bankWithdraw.enterAmount')">
                    <template #content="">
                        <div class="flex flex-column">
                            <div class="w-full col-12 mt-2">
                                <FloatLabel>
                                    <InputText id="amount" v-model="amount_form.amount" style="height: 100%; width: 100%;" />
                                    <label for="amount">{{ $t('app.bankWithdraw.amountInUSD') }}</label>
                                </FloatLabel>
                                <small id="username-help">{{ $t('common.balance') }}: {{ formatCurrency(balance) }}</small>
                                <InlineMessage class="w-full" severity="warn" v-if="v$.amount.$error"> {{ v$.amount.$errors[0].$message }} </InlineMessage>
                                
                            </div>
                            
                        </div>
                        <div class="flex pt-4 justify-content-end">
                            <Button class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" :label="$t('common.next')" icon="pi pi-arrow-right" iconPos="right" @click="validate()" />
                        </div>
                    </template>
                </StepperPanel>

                <StepperPanel v-if="bankInfoSet" :header="$t('app.bankWithdraw.enterBankInfo')">
                    <template #content="">
                        <div class="flex flex-column">
                            <div class="w-full col-12 mt-2">
                                <FloatLabel>
                                    <InputText id="beneficiary_bank" v-model="withdrawChannels.bank!.bank_name" disabled style="height: 100%; width: 100%;" :readonly="true" />
                                    <label for="beneficiary_bank">{{ $t('app.bankWithdraw.bankName') }}</label>
                                </FloatLabel>
                                <InlineMessage class="w-full" severity="warn" v-if="b$.beneficiary_bank.$error"> {{ v$.beneficiary_bank.$errors[0].$message }} </InlineMessage>
                            </div>

                            
                            <div class="w-full col-12 mt-2">
                                <FloatLabel>
                                    <InputText id="beneficiary_name" v-model="withdrawChannels.bank!.account_name" disabled style="height: 100%; width: 100%;" />
                                    <label for="beneficiary_name">{{ $t('app.bankWithdraw.accountHolderName') }}</label>
                                </FloatLabel>
                                <InlineMessage class="w-full" severity="warn" v-if="b$.beneficiary_name.$error"> {{ b$.beneficiary_name.$errors[0].$message }} </InlineMessage>
                            </div>

                            <div class="w-full col-12 mt-2">
                                <FloatLabel>
                                    <InputText id="beneficiary_account" v-model="withdrawChannels.bank!.account_number" disabled style="height: 100%; width: 100%;" />
                                    <label for="beneficiary_account">{{ $t('app.bankWithdraw.accountNumberIban') }}</label>
                                </FloatLabel>
                                <InlineMessage class="w-full" severity="warn" v-if="b$.beneficiary_account.$error"> {{ b$.beneficiary_account.$errors[0].$message }} </InlineMessage>
                            </div>

                            <div class="w-full col-12 mt-2">
                                <FloatLabel>
                                    <InputText id="routing_no" v-model="withdrawChannels.bank!.routing_no" style="height: 100%; width: 100%;" />
                                    <label for="routing_no">{{ $t('app.bankWithdraw.routingNumberOptional') }}</label>
                                </FloatLabel>
                            </div>
                            
                        </div>
                        <div class="flex pt-4 justify-content-between">
                            <Button :label="$t('common.back')" severity="secondary" icon="pi pi-arrow-left" @click="previous" />
                            <Button :label="$t('common.next')" icon="pi pi-arrow-right" iconPos="right" @click="validateBank" />
                        </div>
                    </template>
                </StepperPanel>

                <StepperPanel v-if="!bankInfoSet" :header="$t('app.bankWithdraw.enterBankInfo')">
                    <template #content="">
                        <div class="flex flex-column">
                            <div class="w-full col-12 mt-2">
                                <FloatLabel>
                                    <InputText id="beneficiary_bank" v-model="bank_form.beneficiary_bank" style="height: 100%; width: 100%;" />
                                    <label for="beneficiary_bank">{{ $t('app.bankWithdraw.bankName') }}</label>
                                </FloatLabel>
                                <InlineMessage class="w-full" severity="warn" v-if="b$.beneficiary_bank.$error"> {{ v$.beneficiary_bank.$errors[0].$message }} </InlineMessage>
                            </div>

                            
                            <div class="w-full col-12 mt-2">
                                <FloatLabel>
                                    <InputText id="beneficiary_name" v-model="bank_form.beneficiary_name" style="height: 100%; width: 100%;" />
                                    <label for="beneficiary_name">{{ $t('app.bankWithdraw.accountHolderName') }}</label>
                                </FloatLabel>
                                <InlineMessage class="w-full" severity="warn" v-if="b$.beneficiary_name.$error"> {{ b$.beneficiary_name.$errors[0].$message }} </InlineMessage>
                            </div>

                            <div class="w-full col-12 mt-2">
                                <FloatLabel>
                                    <InputText id="beneficiary_account" v-model="bank_form.beneficiary_account" style="height: 100%; width: 100%;" />
                                    <label for="beneficiary_account">{{ $t('app.bankWithdraw.accountNumberIban') }}</label>
                                </FloatLabel>
                                <InlineMessage class="w-full" severity="warn" v-if="b$.beneficiary_account.$error"> {{ b$.beneficiary_account.$errors[0].$message }} </InlineMessage>
                            </div>

                            <div class="w-full col-12 mt-2">
                                <FloatLabel>
                                    <InputText id="routing_no" v-model="bank_form.routing_no" style="height: 100%; width: 100%;" />
                                    <label for="routing_no">{{ $t('app.bankWithdraw.routingNumberOptional') }}</label>
                                </FloatLabel>
                            </div>
                            
                        </div>
                        <div class="flex pt-4 justify-content-between">
                            <Button :label="$t('common.back')" severity="secondary" icon="pi pi-arrow-left" @click="previous" />
                            <Button :label="$t('common.next')" icon="pi pi-arrow-right" iconPos="right" @click="validateBank" />
                        </div>
                    </template>
                </StepperPanel>

                <StepperPanel :header="$t('common.finish')">
                    <template #content="{ }">
                        <div class="flex flex-column">
                        <div class="w-full">
                            
                            <Message :closable="false" icon="pi pi-check-circle" class="uppercase mb-1 text-lg font-semibold" severity="success">{{ $t('app.bankWithdraw.withdrawalQueuedSuccess') }}</Message>
                        </div>

                        </div>
                        <div class="flex pt-4 justify-content-center">
                        <router-link :to="{name: 'Account'}" class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150">
                            {{ $t('common.finish') }}
                        </router-link>
                        </div>
                    </template>
                </StepperPanel>
            </Stepper>
        </template>
    </Card>
</div>

    <Dialog v-model:visible="visible" modal :pt="{ root: 'border-none', mask: { style: 'backdrop-filter: blur(2px)'}}">
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
                        <InlineMessage severity="info">{{ $t('app.bankWithdraw.withdrawalChannelBank') }}</InlineMessage>
                    </div>

                    <div class="inline-flex flex-column gap-2">
                        <label for="username" class="text-primary-50 font-semibold">{{ $t('app.bankWithdraw.withdrawalAmount') }}</label>
                        <InputText :value="amount_form.amount" id="username" class="bg-white-alpha-20 border-none p-3 text-primary-50 w-20rem" aria-readonly="true" readonly></InputText>
                    </div>
                    

                    
                    <div class="flex align-items-center gap-3">
                        <Button :label="$t('common.cancel')" @click="closeCallback" text class="p-3 w-full text-primary-50 border-1 border-white-alpha-30 hover:bg-white-alpha-10"></Button>
                        <Button :label="$t('common.confirm')" @click="next" text class="p-3 w-full text-primary-50 border-1 border-white-alpha-30 hover:bg-white-alpha-10"></Button>
                    </div>
                </div>
            </template>
    </Dialog>

    <Dialog v-model:visible="showOtp" modal :pt="{ root: 'border-none', mask: { style: 'backdrop-filter: blur(2px)'}}">
        <template #container="{ closeCallback }" className="justify-center" class="justify-center">
            <Fieldset>
                <template #legend>
                    <div class="flex items-center pl-2">
                        <span class="font-bold p-2">{{ $t('app.bankWithdraw.authenticateYourAccount') }}</span>
                    </div>
                </template>
                <div class="m-0 justify-center content-center items-center center w-full">
                    <p class="text-surface-500 dark:text-surface-400 block mb-3">{{ code_request }}</p>
                    <div class="col-12 p-2">
                        <InputOtp v-model="code" :length="6" style="gap: 10" variant="outlined">
                            <template #default="{ attrs, events, index }">
                                <input type="text" v-bind="attrs" v-on="events" class="custom-otp-input" />
                                <div v-if="index === 3" class="px-2">
                                    <i class="pi pi-minus" />
                                </div>
                            </template>
                        </InputOtp>
                    </div>
                    <div class="row">
                        <div class="flex justify-between mt-4 self-stretch">
                            <Button :label="$t('app.bankWithdraw.resendCode')" link class="p-0"></Button>
                            <Button :label="$t('app.bankWithdraw.submitCode')" class="ml-auto" @click="validateCode"></Button>
                        </div>
                    </div>
                </div>
            </Fieldset>
            
        </template>
    </Dialog>

    <NotifyDialog :visible="showNotify" :text="notificationText" />
</template>


<script lang="ts">
import { defineComponent, onMounted, reactive, ref, computed, watch } from 'vue'
import { useGlobalLoader } from 'vue-global-loader'
import { useVuelidate } from '@vuelidate/core'
import { required, numeric, minValue, helpers} from '@vuelidate/validators'
import { useRouter } from 'vue-router'
import { useWithdrawal } from '@/lib/withdrawalService'
import { toast } from '@/lib/notifications'
import { formatCurrency } from "@/lib";
import { accountService } from '@/lib/accountService'
// @ts-ignore
import type { BankWithdraw, WithdrawResponse } from '../../types/Withdrawal'
import type { WithdrawChannels } from '../../types/Account'
import type { APIError } from '../../types/response'
import Stepper from 'primevue/stepper';
import StepperPanel from 'primevue/stepperpanel';
import FloatLabel from 'primevue/floatlabel';
import InputOtp from 'primevue/inputotp';
import { NotifyDialog } from '@/components/Widgets'
import { useI18n } from 'vue-i18n'


export default defineComponent({
    setup() {

        const { t } = useI18n()
        const { displayLoader, destroyLoader, isLoading } = useGlobalLoader()
        const amount_form = reactive({
            amount: '',
        })
        const bank_form = reactive({
            beneficiary_bank: '',
            beneficiary_account: '',
            beneficiary_name: '',
            routing_no: '',
            sort_code: ''
        })
        const step = ref(0)
        const balance = ref<number>(0)
        const sufficient = (value: any) => {
            return Number(value) < balance.value
        }
        const router = useRouter()
        const code = ref('')
        const v$ = useVuelidate({
            amount: { 
                required, 
                numeric: { decimal: true}, 
                minValue: 20,
                sufficient: helpers.withMessage(t('app.bankWithdraw.insufficientBalance'), sufficient)
            }
        }, amount_form)

        const b$ = useVuelidate({
            beneficiary_account: { required },
            beneficiary_bank: { required },
            beneficiary_name: { required }
        }, bank_form)

        const visible = ref(false)
        const showOtp = ref<boolean>(false)
        const service = accountService()
        const withdrawService = useWithdrawal()
        const code_request = ref(t('app.bankWithdraw.codeRequest'))
        const notificationText = ref('')
        const showNotify = ref(false)

        const validate = async () => {
            const validation = await v$.value.$validate()
            if(!validation) {
                toast.info(t('notifications.error'), t('app.bankWithdraw.validAmountError'))
                return
            } else {
                step.value++
            }
        }

        const next = () => {
            displayLoader()
            const data: BankWithdraw = {
                beneficiary_bank: withdrawChannels.value.bank!.bank_name,
                beneficiary_account: withdrawChannels.value.bank!.account_number,
                beneficiary_name: withdrawChannels.value.bank!.account_name,
                amount: Number(amount_form.amount),
                routing_no: withdrawChannels.value.bank!.routing_no ?? '',
                sort_code: withdrawChannels.value.bank!.sort_code ?? ''
            }
            withdrawService.submitBankRequest(data).then((resp: WithdrawResponse) => {
                if(resp.twoFactor) {
                    visible.value = !visible.value
                    showOtp.value = true
                } else {
                    if(resp.checkpoint != undefined) {

                    } else {
                        visible.value = false
                        step.value++
                    }
                    
                }
                return
            }).catch((error: APIError) => {
                toast.info(t('notifications.error'), error.message!)
            }).finally(() => { destroyLoader() })
        }
        const previous = async () =>  {
            step.value--
        }

        const validateBank = async () => {
            if(bankInfoSet.value == true) {
                visible.value = true
                return
            }
            const validation = await b$.value.$validate()
            if(!validation) {
                toast.info(t('notifications.error'), t('app.bankWithdraw.validBankError'))
                
                return
            } else {
                visible.value = true
                return
            }
        }

        const withdrawChannels = ref<WithdrawChannels>({
            bank: {
                bank_name: '', 
                account_name: '', 
                account_number: ''
            },
            wallet: undefined
        })
        onMounted(() => {
            displayLoader()
            service.getWalletBalance().then((bal) => {
                balance.value = bal
            }).catch((err) => {
                toast.info(t('notifications.error'), t('app.bankWithdraw.balanceError'))
            })

            service.getWithdrawalChannels().then((channels) => {
                withdrawChannels.value = channels
            }).catch((error) => {
                console.log(error)
            }).finally(() => destroyLoader())
        })
        const bankInfoSet = computed(() => {
            return withdrawChannels.value.bank?.bank_name !== undefined && withdrawChannels.value.bank?.bank_name !== '' && withdrawChannels.value.bank?.account_number !== ''
        })

        const validateCode = () => {
            displayLoader()
            const amount = amount_form.amount
            withdrawService.validateWithdrawOtp(Number(amount), code.value, 'bank')
            .then((data: WithdrawResponse) => {
                if(data.payment_id != undefined && data.twoFactor == false) {
                    toast.success(t('app.bankWithdraw.withdrawalValidated'))
                    visible.value = false
                    showOtp.value = false
                    step.value++
                    
                    if(data.checkpoint !== undefined) {
                        notificationText.value = t('app.bankWithdraw.furtherVerification')
                        showNotify.value = true
                    }
                } else {
                    toast.error(t('app.bankWithdraw.unableToValidate'))
                }
            })
            .catch((error) => {
                toast.error(error.message!)
            }).finally(() => {
                destroyLoader()
            })
        }

        watch(bank_form, (value) => {
            const bank = {
                bank_name: bank_form.beneficiary_bank,
                account_name: bank_form.beneficiary_account,
                account_number: bank_form.beneficiary_account,
                routing_no: bank_form.routing_no
            }
            withdrawChannels.value.bank = bank
        })
        return {notificationText, showNotify, code_request, validateCode, code, showOtp, amount_form, bank_form, step, v$, b$, validate, next, previous, visible, balance, validateBank, bankInfoSet, withdrawChannels }
    },
    methods: {
        formatCurrency
    },
    components: {
        Stepper, 
        StepperPanel,
        FloatLabel,
        InputOtp,
        NotifyDialog
    }
})
</script>

<style scoped>
.custom-otp-input {
    width: 45px;
    height: 45px;
    font-size: 22px;
    /* appearance: none; */
    text-align: center;
    transition: all 0.2s;
    border-radius: 2px;
    border: 1px solid black;
    background: transparent;
    outline-offset: -2px;
    outline-color: 1px solid var(--p-inputtext-border-color);
    /* border-right: 0 none; */
    transition: outline-color 0.3s;
    color: var(--p-inputtext-color);
}

.custom-otp-input:focus {
    outline: 2px solid var(--p-focus-ring-color);
}

/* .custom-otp-input:nth-child(5) */
.custom-otp-input:first-child {
    border-top-left-radius: 12px;
    border-bottom-left-radius: 12px;
}

/* .custom-otp-input:nth-child(3), */
.custom-otp-input:last-child {
    border-top-right-radius: 12px;
    border-bottom-right-radius: 12px;
    border-right-width: 1px;
    border-right-style: solid;
    border-color: var(--p-inputtext-border-color);
}
</style>