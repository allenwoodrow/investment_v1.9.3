import type { RouteRecordRaw } from "vue-router";
import { AppLayout } from "@/layout";
import { 
    Dashboard,
    Settings,
    AccountSettings,
    WithdrawSettings,
    Deposit,
    Invest,
    PortFolio,
    Payouts,
    WithdrawPage,
    BankWithdraw,
    WalletWithdraw,
    InvestHistory,
    Withdrawals,
    HistoryView,
    DepositHistory,
    ValidateWithdraw
} from '@/views'

  
export const clientRoutes: RouteRecordRaw =  {
    path: '/account',
    name: 'App',
    component: AppLayout,
    meta: { requiresAuth: true, isAdmin: false },
    children: [
      {
        path: 'dashboard',
        name: 'Account',
        component: Dashboard
      },
      {
        path: 'deposit',
        name: 'Deposit',
        component: Deposit
      },
      {
        path: 'invest',
        name: 'Invest',
        component: Invest
      },
      {
        path: 'portfolio',
        name: 'Portfolio',
        component: PortFolio
      },
      {
        path: 'history',
        name: 'History',
        component: HistoryView,
        children: [
          {
            path: 'payouts',
            name: 'PayoutHistory',
            component: Payouts
          },
          {
            path: 'investment_history',
            name: 'InvestHistory',
            component: InvestHistory
          },
          {
            path: 'withdrawal_history',
            name: 'withdrawal_history',
            component: Withdrawals
          },
          {
            path: 'deposit_history',
            name: 'deposit_history',
            component: DepositHistory
          }
        ]
      },
      {
        path: 'withdraw',
        name: 'Withdrawal',
        component: WithdrawPage,
        children: [
          {
            path: 'bank',
            name: 'BankWithdraw',
            component: BankWithdraw
          },
          {
            path: 'wallet',
            name: 'WalletWithdraw',
            component: WalletWithdraw
          }
        ]
      },
      {
        path: 'validate_withdraw/:request_id',
        name: 'ValidateWithdraw',
        component: ValidateWithdraw,
        props: true
      },
      {
        path: 'settings',
        name: 'Settings',
        component: Settings,
        children: [
          { 
            path: 'account',
            name: 'AccountSettings',
            component: AccountSettings
          },
          { 
            path: 'withdrawal',
            name: 'withdrawal_settings',
            component: WithdrawSettings,
            props: false
          }
        ]
      }
    ]
  }