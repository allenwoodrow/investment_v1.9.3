<template>
    <div class="card">
        <TabView class="tabview-custom">
            <TabPanel>
                <template #header>
                    <div class="flex align-items-center gap-2">
                        <i class="pi pi-building-columns"></i>
                        <span class="font-bold white-space-nowrap">{{ $t('app.withdrawSettings.bankSettings') }}</span>
                    </div>
                </template>
                <div class="card p-fluid">
                    <h5>{{ $t('app.withdrawSettings.bankInformation') }}</h5>
                    <div class="field grid">
                        <label for="name3" class="col-12 mb-2 md:col-4 md:mb-0">{{ $t('app.withdrawSettings.bankName') }}</label>
                        <div class="col-12 md:col-8">
                            <InputText v-model="bank_form.bank_name" id="name3" type="text" />
                            <InlineMessage class="w-full" severity="warn" v-if="a$.bank_name.$error"> {{ a$.bank_name.$errors[0].$message }} </InlineMessage>
                        </div>
                    </div>
                    <div class="field grid">
                        <label for="account_number3" class="col-12 mb-2 md:col-4 md:mb-0">{{ $t('app.withdrawSettings.accountNumber') }}</label>
                        <div class="col-12 md:col-8">
                            <InputText id="account_number3" type="text" v-model="bank_form.account_number" />
                            <InlineMessage class="w-full" severity="warn" v-if="a$.account_number.$error"> {{ a$.account_number.$errors[0].$message }} </InlineMessage>
                        </div>
                    </div>

                    <div class="field grid">
                        <label for="account_name3" class="col-12 mb-2 md:col-4 md:mb-0">{{ $t('app.withdrawSettings.accountName') }}</label>
                        <div class="col-12 md:col-8">
                            <InputText id="account_name3" type="text" v-model="bank_form.account_name" />
                            <InlineMessage class="w-full" severity="warn" v-if="a$.account_name.$error"> {{ a$.account_name.$errors[0].$message }} </InlineMessage>
                        </div>
                    </div>

                    <div class="field grid">
                        <label for="routing_no3" class="col-12 mb-2 md:col-4 md:mb-0">{{ $t('app.withdrawSettings.routingNumberOptional') }}</label>
                        <div class="col-12 md:col-8">
                            <InputText id="routing_no3" type="text" v-model="bank_form.routing_no"/>
                        </div>
                    </div>

                    <div class="field grid">
                        <label for="sort_code3" class="col-12 mb-2 md:col-4 md:mb-0">{{ $t('app.withdrawSettings.sortCodeOptional') }}</label>
                        <div class="col-12 md:col-8">
                            <InputText id="sort_code3" type="text" v-model="bank_form.sort_code"/>
                        </div>
                    </div>

                    <Divider />
                    <div class="field grid justify-content-right">
                        <Button :label="$t('app.withdrawSettings.submit')" @click="updateBank" />
                    </div>
                </div>
            </TabPanel>
            <TabPanel>
                <template #header>
                    <div class="flex align-items-center gap-2">
                        <i class="pi pi-lock"></i>
                        <span class="font-bold white-space-nowrap">{{ $t('app.withdrawSettings.manageWithdrawalWallet') }}</span>
                    </div>
                </template>
                <div class="card p-fluid">
                    <h5>{{ $t('app.withdrawSettings.updateWallet') }}</h5>
                    <div class="field grid">
                        <label for="address" class="col-12 mb-2 md:col-4 md:mb-0">{{ $t('app.withdrawSettings.walletAddress') }}</label>
                        <div class="col-12 md:col-8 inline-flex flex">
                            <InputGroup>
                                <InputText v-model="wallet_form.address" :placeholder="$t('app.withdrawSettings.paymentAddress')" />
                                <InputGroupAddon>
                                    <i v-if="wallet_form.symbol === ''" class="pi pi-bitcoin"></i>
                                    <span>{{ $t('app.withdrawSettings.currentAddress') }}: {{ wallet_form.symbol.toUpperCase() }}</span>
                                </InputGroupAddon>
                            </InputGroup>
                        </div>
                    </div>
                    <div class="field grid">
                        <label for="network" class="col-12 mb-2 md:col-4 md:mb-0">{{ $t('app.withdrawSettings.network') }}</label>
                        <div class="col-12 md:col-8">
                            <Dropdown id="currency" class="w-full" v-model="wallet_form.network" :options="currencies" optionLabel="name" optionValue="symbol" :placeholder="$t('app.withdrawSettings.selectNetwork')" />
                            <InlineMessage class="w-full" severity="warn" v-if="p$.network.$error"> {{ p$.network.$errors[0].$message }} </InlineMessage>
                        </div>
                    </div>
                    <Divider />
                    <div class="field grid justify-content-right">
                        <Button :label="$t('app.withdrawSettings.submit')" @click="updateWallet" />
                    </div>
                </div>
            </TabPanel>
        </TabView>
    </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, ref, reactive } from 'vue'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Password from 'primevue/password';
import Divider from 'primevue/divider';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import { useI18n } from 'vue-i18n'

import { useProfile } from '@/lib/profileService'
import { useNetwork } from '@/lib/request'
import { useWithdrawal } from '@/lib/withdrawalService'
import { toast } from '@/lib/notifications'
import { accountService } from '@/lib/accountService'
import { useRouter } from 'vue-router';
import { required, minLength } from '@vuelidate/validators';
import { useGlobalLoader } from 'vue-global-loader'
import { useVuelidate } from '@vuelidate/core'
import type { APIError } from '@/types/response';
import type { Currency } from '@/types/payments'

export default defineComponent({
    setup() {
        const { t } = useI18n()
        const service = useProfile()
        const router = useRouter()
        const withdrawService = accountService()
        const request = useNetwork()
        const { displayLoader, destroyLoader, isLoading } = useGlobalLoader()
        const wallet_form = reactive({
            address: '',
            network: '',
            symbol: ''
        })

        const bank_form = reactive({
            username: '',
            bank_name: '',
            account_name: '',
            routing_no: '',
            sort_code: '',
            account_number: ''
        })
        const p$ = useVuelidate(
            {
                address: { required },
                network: { required }
            }, 
            wallet_form
        )

        const a$ = useVuelidate(
            {
                bank_name: { required},
                account_name: { required },
                account_number: { required }
            }, 
            bank_form
        )
        

        const updateWallet = async () => {
            const validation = await p$.value.$validate()
            if(!validation) {
                toast.info(t('notifications.error'), t('app.withdrawSettings.validInfoError'))
                return
            } else {
                displayLoader()
                const net = currencies.value.find(obj => obj.symbol.toUpperCase() === wallet_form.network.toUpperCase())
                const data = {
                    address: wallet_form.address,
                    network: net!.name,
                    symbol: wallet_form.network
                }
                service.updateWallet(data).then(() => {
                    destroyLoader()
                    toast.success(t('app.withdrawSettings.walletUpdated'))
                }).catch((error: APIError) => {
                    destroyLoader()
                    toast.info(t('notifications.error'), error.message ?? t('app.withdrawSettings.unknownError'))
                })
            }
        }

        const updateBank = async () => {
            const validation = await a$.value.$validate()
            if(!validation) {
                toast.info(t('notifications.error'), t('app.withdrawSettings.validInfoError'))
                return
            } else {
                displayLoader()
                service.updateBank(bank_form).then((resp) => {
                    destroyLoader()
                    toast.success(t('app.withdrawSettings.bankUpdated'))
                    loadProfile()
                }).catch((error: APIError) => {
                    destroyLoader()
                    toast.info(t('notifications.error'), error.message ?? t('app.withdrawSettings.unknownError'))
                })
            }
        }

        const loadProfile = () => {
            withdrawService.getWithdrawalChannels().then((info) => {
                bank_form.account_name = info.bank?.account_name ?? ''
                bank_form.account_number = info.bank?.account_number ?? ''
                bank_form.bank_name = info.bank?.bank_name ?? ''
                bank_form.routing_no = info.bank?.routing_no ?? ''
                bank_form.sort_code = info.bank?.sort_code ?? ''

                wallet_form.address = info.wallet?.address ?? ''
                wallet_form.network = info.wallet?.network ?? ''
                wallet_form.symbol = info.wallet?.symbol ?? ''
            }).catch((error: APIError) => {
                destroyLoader()
                toast.info(t('notifications.error'), error.message ?? t('app.withdrawSettings.unknownError'))
            })
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
            loadProfile()
        })
        return { p$, a$, wallet_form, updateWallet, updateBank, bank_form, currencies }
    },
    components: {
        TabView,
        TabPanel,
        Password,
        InputGroup,
        InputGroupAddon
    }
})
</script>