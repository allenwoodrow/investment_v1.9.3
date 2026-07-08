<template>
    <div class="grid p-1">
        <Card class="xl:col-7 md:col-7 lg:col-6 col-12 mx-1 my-1">
            <template #title>{{ $t('app.deposit.title') }}</template>
            <template #subtitle>{{ $t('app.deposit.subtitle') }}</template>
            <template #content>
                <Stepper orientation="vertical" :activeStep="step" :linear="true">
                    <StepperPanel :header="$t('app.deposit.enterAmountCurrency')">
                        <template #content="">

                            <div class="flex flex-column">
                                <div class="w-full col-12 mt-2">
                                    <FloatLabel>
                                        <InputText id="amount" v-model="form.amount" style="height: 100%; width: 100%;" />
                                        <label for="amount">{{ $t('app.deposit.amountInUSD') }}</label>
                                    </FloatLabel>
                                    <InlineMessage class="w-full" severity="warn" v-if="v$.amount.$error"> {{ v$.amount.$errors[0].$message }} </InlineMessage>
                                    
                                </div>

                                <div class="w-full col-12 mt-2">

                                    <FloatLabel>
                                        <Dropdown id="currency" @change="currencySelected" class="w-full" v-model="form.currency" :options="currencies" optionLabel="symbol" optionValue="name" :placeholder="$t('common.select')" />
                                        <label for="amount">{{ $t('app.deposit.selectCurrency') }}</label>
                                    </FloatLabel>
                                    <InlineMessage class="w-full" severity="warn" v-if="v$.currency.$error"> {{ v$.currency.$errors[0].$message }} </InlineMessage> 
                                </div>
                            </div>
                            <div class="flex pt-4 justify-content-end">
                                <Button class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" :label="$t('common.next')" icon="pi pi-arrow-right" iconPos="right" @click="validate()" />
                            </div>
                        </template>
                    </StepperPanel>

                    <StepperPanel :header="$t('app.deposit.sendCrypto')">
                        <template #content="{ prevCallback, nextCallback }">
                        <div class="flex flex-column">
                            <div class="w-full">

                            <vue-qrcode :value="generateAddress" :options="{ width: 200 }"></vue-qrcode>
                            <p>{{ $t('app.deposit.pleaseSend', { amount: transaction?.pay_amount, currency: transaction?.pay_currency.toUpperCase() }) }}</p>
                            <InputGroup class="mt-5">
                                <InputText :value="transaction?.pay_address" class="w-full" />
                                <Button @click="copyAddress" icon="pi pi-copy" severity="success" class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" />
                            </InputGroup>

                            <InputGroup class="mt-5">
                                <InputText :value="transaction?.pay_amount" class="w-full"/>
                                <Button @click="copyAmount" icon="pi pi-copy" severity="info" class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" />
                            </InputGroup>
                            </div>
                            </div>

                            <div class="mt-3 w-full">
                            
                            
                            </div>
                            <div class="flex pt-4 justify-content-between">
                                <Button :label="$t('common.back')" severity="secondary" icon="pi pi-arrow-left" @click="prevCallback" />
                                <Button :label="$t('app.deposit.iHavePaid')" icon="pi pi-arrow-right" iconPos="right" @click="nextCallback" />
                            </div>
                        </template>
                    </StepperPanel>

                    <StepperPanel :header="$t('common.finish')">
                        <template #content="{ }">
                            <div class="flex flex-column">
                            <div class="w-full">
                                
                                <Message :closable="false" icon="pi pi-check-circle" class="uppercase mb-1 text-lg font-semibold" severity="success">{{ $t('app.deposit.depositQueuedSuccess') }}</Message>
                            </div>

                            </div>
                            <div class="flex pt-4 justify-content-center">
                            <router-link to="/account/dashboard" class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150">
                                {{ $t('common.finish') }}
                            </router-link>
                            </div>
                        </template>
                    </StepperPanel>
                </Stepper>
            </template>
        </Card>

        <Card class="xl:col-4 md:col-4 lg:col-3 col-12 mx-1 my-1 flex">
            <template #title>{{ $t('app.deposit.converterTitle') }}</template>
            <template #subtitle>{{ $t('app.deposit.converterSubtitle') }}</template>
            <template #content>
                <crypto-converter-widget v-prevent-unwanted-link style="width: 100%;" amount="1" shadow="true" symbol="true" live="true" fiat="united-states-dollar" crypto="bitcoin" font-family="inherit" background-color="transparent" decimal-places="2" border-radius="0.5rem" class="w-full"></crypto-converter-widget>
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
                        <label for="username" class="text-primary-50 font-semibold">{{ $t('app.deposit.depositAmount') }}</label>
                        <InputText :value="form.amount" id="username" class="bg-white-alpha-20 border-none p-3 text-primary-50 w-20rem" aria-readonly="true" readonly></InputText>
                    </div>
                    <div class="inline-flex flex-column gap-2">
                        <label for="password" class="text-primary-50 font-semibold">{{ $t('app.deposit.amountInCurrencyFees', { currency: form.currency.toUpperCase() }) }}</label>
                        <InputText :value="paymentAmount" id="password" class="bg-white-alpha-20 border-none p-3 text-primary-50 w-20rem" type="text" aria-readonly="true" readonly></InputText>
                    </div>
                    <div class="flex align-items-center gap-3">
                        <Button :label="$t('common.cancel')" @click="closeCallback" text class="p-3 w-full text-primary-50 border-1 border-white-alpha-30 hover:bg-white-alpha-10"></Button>
                        <Button :label="$t('common.confirm')" @click="handleDeposit" text class="p-3 w-full text-primary-50 border-1 border-white-alpha-30 hover:bg-white-alpha-10"></Button>
                    </div>
                </div>
            </template>
    </Dialog>
</template>

<script lang="ts">
import { defineComponent, ref, reactive, computed, watch, onMounted } from 'vue'
import { useGlobalLoader } from 'vue-global-loader'
import { useVuelidate } from '@vuelidate/core'
import { required, numeric, minValue} from '@vuelidate/validators'
// @ts-ignore
import { v4 as uuidv4 } from 'uuid'
import Stepper from 'primevue/stepper';
import StepperPanel from 'primevue/stepperpanel';
import { toast } from "@/lib/notifications";
import { useNetwork } from '@/lib/request'
import { useGateway } from '@/lib/gateway'
import type { CreateOrder, GatewayTransaction, TransactionForm } from "../../types/payments";
import VueQrcode from '@chenfengyuan/vue-qrcode';
import { useI18n } from 'vue-i18n'

import FloatLabel from 'primevue/floatlabel';
import Button from 'primevue/button';
import Dropdown from 'primevue/dropdown';
import InputGroup from 'primevue/inputgroup'
import Message from 'primevue/message';
import { RouterLink } from "vue-router";
import Dialog from 'primevue/dialog';
import Splitter from 'primevue/splitter';
import SplitterPanel from 'primevue/splitterpanel'
import type { Currency } from '@/types/payments'

export default defineComponent({
    setup() {
        const { t } = useI18n()
        const { displayLoader, destroyLoader, isLoading } = useGlobalLoader()
        const paymentAmount = ref<number>(0.00);
        const transaction = ref<GatewayTransaction | null>(null)
        const expiry = ref<string>('0:00')
        const status = computed(() => {
            return transaction.value?.payment_status ?? 'waiting'
        })

        const form = reactive({
            amount: '',
            currency: ''
        })

        const rules = computed(() => {
            return {
            amount: { 
                required, 
                numeric: { decimal: true}, 
                minValue: 20
            },
            currency: { required },       
            }
        })

        const visible = ref(false)
        const selectedCurrency = ref<Currency>({
            name: '',
            symbol: '',
            address: ''
        })

        const v$ = useVuelidate(rules, form)
        const request = useNetwork()

        const handleDeposit = () => {
            visible.value = false
            displayLoader()
            const id = uuidv4().replace(/[^0-9]/g, '')
            const date = new Date()
            const trans_data: GatewayTransaction = {
                payment_id: generate(16),
                payment_status: 'waiting',
                pay_address: selectedCurrency.value.address,
                price_amount: Number(form.amount),
                price_currency: 'USD',
                pay_amount: paymentAmount.value,
                pay_currency: selectedCurrency.value.symbol,
                order_id: generate(10),
                order_description: t('app.deposit.title'),
                created_at: date.toISOString(),
                updated_at: date.toISOString(),
                purchase_id: generate(10),
                amount_received: 0,
                payin_extra_id: null,
                network: selectedCurrency.value.symbol,
                network_precision: 8,
                expiration_estimate_date: date.toISOString()
            }
            transaction.value = trans_data
            submitTransaction()
        }

        const generate: any = (n: number) => {
            var add = 1, max = 12 - add;
            if ( n > max ) {
                return generate(max) + generate(n - max);
            }
            max        = Math.pow(10, n+add);
            var min    = max/10;
            var number = Math.floor( Math.random() * (max - min + 1) ) + min;
            return ("" + number).substring(add); 
        }

        const submitTransaction = () => {
            const data = {
                payment_id: transaction.value!.payment_id,
                price_amount: transaction.value!.price_amount,
                pay_amount: transaction.value!.pay_amount,
                price_currency: transaction.value!.price_currency,
                pay_currency: transaction.value!.pay_currency,
                order_description: transaction.value!.order_description,
                order_id: transaction.value!.order_id, 
                sub_id: null,
                payment_status: transaction.value!.payment_status,
                pay_address: transaction.value!.pay_address,
                expiration_estimate_date: transaction.value!.expiration_estimate_date,
                network: transaction.value!.network
            }

            request.push('new-deposit', data).then((response) => {
                // @ts-ignore
                toast.success(response.data.message)
                step.value++
            }).catch((error) => {
                console.log(error)
                const message = error.message || t('app.deposit.unknownError')
                toast.error(message)
            }).finally(() => {
                destroyLoader()
            })
        }

        const step = ref(0)

        const showPayment = () =>  {
            displayLoader()
            
            request.fetch<{ amount: string; rate: string }, {}>(`exchange-rate/${selectedCurrency.value.name}/${form.amount}`, {}).then((response) => {
                paymentAmount.value = Number(response.data!.amount)
                visible.value = true
            }).catch((error) => {
                toast.error(t('app.deposit.rateError'))
            }).finally(() => {
                destroyLoader()
            })
        }
    
        const validate = async () => {
            displayLoader()
            const validation = await v$.value.$validate()
            if(!validation) {
                destroyLoader()
                toast.info(t('notifications.error'), t('app.deposit.validAmountCurrencyError'))
                return
            } else {
                showPayment()    
            }
        }

        const copyAddress = async () => {
            const trans = transaction.value
            if(!trans || trans == null) {
                toast.info(t('notifications.error'), t('app.deposit.copyError'))
                return
            }
            try {
                await navigator.clipboard.writeText("" + trans.pay_address +"");
                toast.info(t('app.deposit.copied'), t('app.deposit.addressCopied'))
            } catch (error) {
                toast.error(t('app.deposit.copyError'))
            }
            return
        }

        const copyAmount = async () => {
            const trans = transaction.value
            if(!trans || trans == null) {
                toast.error(t('app.deposit.copyError'))
                return
            }
            try {
                await navigator.clipboard.writeText("" + trans.pay_amount +"");
                toast.info(t('app.deposit.copied'), t('app.deposit.amountCopied'))
            } catch (error) {
                toast.error(t('app.deposit.copyError'))
            }
        }

        const isRunning = ref(false);
        let loopInterval: ReturnType<typeof setInterval> | null = null;

        const startLoop = () => {
            isRunning.value = true;
            loopInterval = setInterval(() => {
                const id = transaction.value?.payment_id
                if(!id) {
                return
                }
            }, 10000);
        };

        const stopLoop = () => {
            isRunning.value = false;
            // @ts-ignore
            clearInterval(loopInterval);
            loopInterval = null;
        };

        const generateAddress = computed(() => {
            if(transaction.value === null || transaction.value === undefined) {
                return 'null'
            }
            const trans = transaction.value
            const full = `${trans.pay_currency.toUpperCase()}:${trans.pay_address}?amount=${trans.pay_amount}`
            return full
        })

        const updateTimer = (current: number) => {
            if(current !== step.value) {
                step.value = current
            }
            if(step.value === 1) {
                const targetDate = new Date(transaction!.value!.expiration_estimate_date).getTime();
                const timer = setInterval(() => {
                    const now = new Date().getTime();
                    const distance = targetDate - now;
                    if (distance <= 0) {
                        clearInterval(timer);
                        toast.info(t('app.deposit.expired'), t('app.deposit.expiredMessage'))
                        window.location.reload();
                    } else {
                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                        expiry.value = `${hours}:${minutes}:${seconds}`
                    }
                }, 1000)
            }
        }

        const currencies = ref<Currency[]>([])

        const loadCurrencies = () => {
            displayLoader()
            request.fetch<Currency[], {}>('currencies', {}).then((response) => {
                currencies.value = response.data!
            }).catch((error) => {
                toast.error(error.message!)
            }).finally(() => destroyLoader())
        }

        onMounted(() => {
            loadCurrencies()
        })

        const currencySelected = (value: any) => {
            const selected = currencies.value.filter((val: any) => {
                return val.name.toLowerCase() == value.value.toLowerCase()
            })
            // @ts-ignore
            selectedCurrency.value = selected[0]
        }
    
        return {
            currencySelected,
        isLoading, form, validate, v$, 
        currencies, step, paymentAmount, 
        visible, handleDeposit, transaction, 
        status, copyAddress, copyAmount, generateAddress, expiry, updateTimer
        }
    },
    components: {
        Stepper, StepperPanel,
        FloatLabel,
        Button, 
        Dropdown, 
        InputGroup,
        VueQrcode,
        Message,
        RouterLink,
        Dialog,
        Splitter,
        SplitterPanel
    },
})
</script>