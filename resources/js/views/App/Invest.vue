<template>
    <div class="grid p-1">
        <Card class="xl:col-7 md:col-7 lg:col-7 sm:col-12 mx-1 my-1">
            <template #title>{{ $t('app.invest.title') }}</template>
            <template #subtitle>{{ $t('app.invest.subtitle') }}</template>
            <template #content>
                <Stepper orientation="vertical" :activeStep="step" :linear="false">
                    <StepperPanel :header="$t('app.invest.selectInvestmentPlan')">
                        <template #content="">

                            <div class="flex flex-column">
                    

                                <div class="w-full col-12 mt-2">

                                    <FloatLabel>
                                        <Dropdown :loading="isLoading" id="plan" class="w-full" v-model="form.plan" :options="plans" optionLabel="name" optionValue="id" :placeholder="$t('common.select')" @update:model-value="accountChanged"/>
                                        <label for="amount">{{ $t('app.invest.selectAccountType') }}</label>
                                    </FloatLabel>
                                    <InlineMessage class="w-full" severity="warn" v-if="pv$.plan.$error"> {{ pv$.plan.$errors[0].$message }} </InlineMessage> 
                                </div>
                            </div>
                            <div class="flex pt-4 justify-content-end">
                                <Button class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" :label="$t('common.next')" icon="pi pi-arrow-right" iconPos="right" @click="validate()" />
                            </div>
                        </template>
                    </StepperPanel>

                    <StepperPanel :header="$t('app.invest.enterAmount')">
                        <template #content="">
                            <div class="flex flex-column">
                                <div class="w-full col-12 mt-2">
                                    <FloatLabel>
                                        <InputText v-model="form.amount" :placeholder="$t('app.invest.amountInUSD')" class="w-full" />
                                        <label for="amount">{{ $t('app.invest.amountInUSDBalance', { balance: availableBalance }) }}</label>
                                    </FloatLabel>
                                    <InlineMessage class="w-full" severity="info" v-if="av$.amount.$error"> {{ av$.amount.$errors[0].$message }} </InlineMessage> 
                                </div>

                                <div class="flex pt-4 justify-content-between">
                                    <Button :label="$t('common.back')" severity="secondary" icon="pi pi-arrow-left" @click="back" />
                                    <Button :label="$t('common.next')" icon="pi pi-arrow-right" iconPos="right" :disabled="!balanceLoaded" @click="validateAmount" />
                                </div>
                            </div>
                        </template>
                    </StepperPanel>

                    <StepperPanel :header="$t('common.finish')">
                        <template #content="{ }">
                            <div class="flex flex-column">
                            <div class="w-full">
                                
                                <Message :closable="false" icon="pi pi-check-circle" class="uppercase mb-1 text-lg font-semibold" severity="success">{{ $t('app.invest.investmentSuccessful') }}</Message>
                            </div>

                            </div>
                            <div class="flex pt-4 justify-content-center">
                            <router-link :to="{name: 'Account'}" class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150">
                                <Button :label="$t('common.finish')" icon="pi pi-check" iconPos="right" @click="$router.push({name: 'Account'})" />
                            </router-link>
                            </div>
                        </template>
                    </StepperPanel>
                </Stepper>
            </template>
        </Card>

        <PricingCard :plan="active_plan" class="xl:col-4 md:col-4 lg:col-4 sm:col-12 mx-1 my-1 flex">

        </PricingCard>
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
                        <label for="username" class="text-primary-50 font-semibold">{{ $t('app.invest.investmentAmount') }}</label>
                        <InputText :value="formatCurrency(Number(form.amount))" id="username" class="bg-white-alpha-20 border-none p-3 text-primary-50 w-20rem" aria-readonly="true" readonly></InputText>
                    </div>
                    <!-- <div class="inline-flex flex-column gap-2">
                        <label for="password" class="text-primary-50 font-semibold">{{ $t('app.invest.amountInCurrencyFees', { currency: form.currency.toUpperCase() }) }}</label>
                        <InputText :value="paymentAmount" id="password" class="bg-white-alpha-20 border-none p-3 text-primary-50 w-20rem" type="text" aria-readonly="true" readonly></InputText>
                    </div> -->
                    <div class="flex align-items-center gap-3">
                        <Button :label="$t('common.cancel')" @click="closeCallback" text class="p-3 w-full text-primary-50 border-1 border-white-alpha-30 hover:bg-white-alpha-10"></Button>
                        <Button :label="$t('common.confirm')" @click="bookInvestment" text class="p-3 w-full text-primary-50 border-1 border-white-alpha-30 hover:bg-white-alpha-10"></Button>
                    </div>
                </div>
            </template>
    </Dialog>
</template>

<script lang="ts">
import { defineComponent, ref, reactive, computed, watch, onMounted } from 'vue'
import { useGlobalLoader } from 'vue-global-loader'
import { useVuelidate } from '@vuelidate/core'
import { required, numeric, minValue } from '@vuelidate/validators'
// @ts-ignore
import { v4 as uuidv4 } from 'uuid'
import Stepper from 'primevue/stepper';
import StepperPanel from 'primevue/stepperpanel';
import { toast } from "@/lib/notifications";
import { formatCurrency } from "@/lib";
import { accountService } from '@/lib/accountService'
// import type { CreateOrder, GatewayTransaction, TransactionForm } from "../../types/payments";
import type { InvestmentPlan, InvestmentBooking } from '../../types/investments'
import { investmentService } from '@/lib/investmentService'

import FloatLabel from 'primevue/floatlabel';
import Button from 'primevue/button';
import Dropdown from 'primevue/dropdown';
import InputGroup from 'primevue/inputgroup'
import Message from 'primevue/message';
import Dialog from 'primevue/dialog';
import Splitter from 'primevue/splitter';
import SplitterPanel from 'primevue/splitterpanel'
import { PricingCard } from '../../components'


export default defineComponent({
    setup() {
        const { displayLoader, destroyLoader } = useGlobalLoader()
        const service = investmentService()
        const account = accountService()
        const paymentAmount = ref<number>(0.00);
        const plans = ref<InvestmentPlan[]>([])
        const isLoading = ref(false)
        const active_plan = ref<InvestmentPlan>({
            id: 0,
            name: '',
            description: '',
            investment_term: '',
            return_percent: '',
            min_amount: '',
            active: false,
            features: []
        })
        const balance = ref<number>(0.00)
        const availableBalance = ref<string>('')
        const form = reactive({
            plan: null as number | null,
            amount: ''
        })

        const rules = computed(() => {
            return {
                plan: {
                    required,
                },
            }
        })

        const rules2 = computed(() => {
            return {
                amount: {
                    required,
                    numeric
                },
            }
        })
        const visible = ref<boolean>(false)

        const pv$ = useVuelidate(rules, form)
        const av$ = useVuelidate(rules2, form)

        const submitTransaction = () => {

            
            // gateway.createPayment(transactionForm).then((response) => {
            //     destroyLoader()
            //     step.value++
            // }).catch(error => {
            //     destroyLoader()
            //     notify({
            //         title: 'Error',
            //         text: error.message
            //     })
            // })
        }

        const step = ref(0)

        const bookInvestment = () =>  {
            displayLoader()
            const data: InvestmentBooking = {
                plan_id: active_plan.value.id,
                amount: Number(form.amount)
            }
            service.bookInvestment(data).then((response) => {
                destroyLoader()
                if(response == true) {
                    visible.value = false
                    step.value++
                    toast.success('Investment Booked Successfully')
                    return
                }
            }).catch((error) => {
                destroyLoader()
                toast.info('Error', 'An unknown error has occured, please refresh and try again')
            })
        }
    
        const validate = async () => {
            const validation = await pv$.value.$validate()
            if(!validation) {
                destroyLoader()
                toast.info('Input Error', 'Please select an account type')
                return
            } else {
                step.value++   
            }
        }

        const validateAmount = async () => {

            const validation = await av$.value.$validate()
            if(!validation) {
                destroyLoader()
                toast.info('Input Error', 'Please enter a valid amount')
                return
            } else {
                if(Number(form.amount) < Number(active_plan.value.min_amount)) {
                    toast.info('Input Error', 'Amount is less than selected investment minimum capital.')
                } else {
                    if(Number(form.amount) > balance.value) {
                        visible.value = false
                        // notify({
                        //     title: 'Unable to complete',
                        //     text: 'Insufficient balance.',
                        //     type: 'info'
                        // })
                    } else {
                        visible.value = true
                    }
                }
                return
            }
        }

        const back = () => {
            step.value--
        }

        onMounted(() => {
            // destroyLoader()
            isLoading.value = true
            displayLoader()
            service.getPlans().then((resp) => {
                isLoading.value = false
                plans.value = resp
                if (resp.length > 0) {
                    form.plan = resp[0].id
                    active_plan.value = resp[0]
                }
            }).catch((error) => {
                toast.error(error.message)
            }).finally(() => {
                destroyLoader()
            })
            account.getWalletBalance().then((wallet_balance: number) => {
                balance.value = wallet_balance
            })
        }) 

        const accountChanged = (value: any) => {
            const selected = service.getPlan(value)
            if(selected !== undefined) {
                active_plan.value = selected
            }
        }
        const balanceLoaded = computed(() => {
            return Number(balance.value) > 0
        })

        watch(balance, (newVal) => {
            availableBalance.value = formatCurrency(Number(newVal))
        })

        return {
        isLoading, form, validate, pv$, av$,
        step, paymentAmount, 
        visible,
        active_plan, plans, validateAmount, accountChanged, back, availableBalance, balanceLoaded, bookInvestment
        }
    },
    components: {
        // DashboardFooter,
        Stepper, StepperPanel,
        FloatLabel,
        Button, 
        Dropdown, 
        InputGroup,
        Message,
        Dialog,
        Splitter,
        SplitterPanel,
        PricingCard
    },
    methods: {
        formatCurrency
    }
})
</script>