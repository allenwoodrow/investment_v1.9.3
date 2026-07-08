<template>
    <ul class="layout-menu">
        <template v-for="(item, i) in items" :key="item.label">
            <menu-item v-if="!item.separator" :item="item" :index="i"></menu-item>
            <li v-if="item.separator" class="menu-separator"></li>
        </template>
    </ul>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue';
import type { MenuGroup } from '@/types/UI';
// @ts-ignore
import { default as MenuItem } from './AppMenuItem.vue'

export default defineComponent({
    setup() {
        const items = computed<MenuGroup[]>(() => [
            {
                label: 'app.menu.home',
                labelKey: 'app.menu.home',
                items: [
                    { label: 'nav.dashboard', labelKey: 'nav.dashboard', icon: 'pi pi-fw pi-home', to: { name: 'Account' } },
                    { label: 'app.menu.buyBitcoin', labelKey: 'app.menu.buyBitcoin', icon: 'pi pi-bitcoin', url: 'https://www.moonpay.com/en-gb/buy', target: '_blank' }
                ]
            },
            {
                label: 'app.menu.fundManagement',
                labelKey: 'app.menu.fundManagement',
                items: [
                    { label: 'nav.deposit', labelKey: 'nav.deposit', icon: 'pi pi-plus', to: { name: 'Deposit' } },
                    { label: 'nav.invest', labelKey: 'nav.invest', icon: 'fas fa-piggy-bank', to: { name: 'Invest' } }
                ]
            },
            {
                label: 'app.menu.portfolioManagement',
                labelKey: 'app.menu.portfolioManagement',
                items: [
                    { label: 'app.menu.investmentPortfolio', labelKey: 'app.menu.investmentPortfolio', icon: 'pi pi-briefcase', to: { name: 'Portfolio' } },
                    { label: 'app.menu.accountPerformance', labelKey: 'app.menu.accountPerformance', icon: 'pi pi-chart-bar', to: { name: 'PayoutHistory' } },
                    { label: 'app.menu.investmentHistory', labelKey: 'app.menu.investmentHistory', icon: 'pi pi-chart-line', to: { name: 'InvestHistory' } }
                ]
            },
            {
                label: 'app.menu.withdrawal',
                labelKey: 'app.menu.withdrawal',
                items: [
                    { label: 'app.menu.withdrawBank', labelKey: 'app.menu.withdrawBank', icon: 'pi pi-building', to: { name: 'BankWithdraw' } },
                    { label: 'app.menu.withdrawWallet', labelKey: 'app.menu.withdrawWallet', icon: 'pi pi-wallet', to: { name: 'WalletWithdraw' } },
                    { label: 'app.menu.withdrawalHistory', labelKey: 'app.menu.withdrawalHistory', icon: 'pi pi-file', to: { name: 'withdrawal_history' } }
                ]
            },
            {
                label: 'app.menu.preferences',
                labelKey: 'app.menu.preferences',
                items: [
                    { label: 'app.menu.accountSettings', labelKey: 'app.menu.accountSettings', icon: 'pi pi-wrench', to: { name: 'AccountSettings' } },
                    { label: 'app.menu.withdrawalSettings', labelKey: 'app.menu.withdrawalSettings', icon: 'pi pi-wallet', to: { name: 'withdrawal_settings' } }
                ]
            },
            {
                label: 'app.menu.aiAssistant',
                labelKey: 'app.menu.aiAssistant',
                items: [
                    { label: 'app.menu.investmentAI', labelKey: 'app.menu.investmentAI', icon: 'pi pi-robot', to: { name: 'ai-dashboard' } }
                ]
            }
        ])

        return { items }
    },
    components: {
        MenuItem
    }
})
</script>

<style lang="scss" scoped></style>
