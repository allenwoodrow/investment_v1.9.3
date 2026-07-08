import type { RouteRecordRaw } from 'vue-router'
import { AdminLayout } from '../layout'

import { 
    AdminLogin,
    AdminDashboard,
    Users,
    UserTransactions,
    ViewPlans,
    ViewDeposits,
    ViewInvestments,
    ViewBankWithdrawals,
    ViewWalletWithdrawals,
    ViewBillingTypes,
    ViewBillingCodes,
    ViewMessages,
    AccountSettings,
    LiveChat,
    PaymentGateway,
    Currencies,
    AuthManagement,
    AddCreditTransaction,
    AddDebitTransaction
} from '@/views/Admin'

export const adminRoutes: RouteRecordRaw = {
    path: '/admin/dashboard/',
    name: 'Manager',
    component: AdminLayout,
    meta: { requiresAuth: true, isAdmin: true },
    children: [
        {
            path: '',
            name: 'AdminDashboard',
            component: AdminDashboard
        },
        {
            path: 'users/:option/:id?',
            name: 'Users',
            component: Users
        },
        {
            path: 'user/:id/transactions',
            name: 'UserTransactions',
            component: UserTransactions,
            props: true
        },
        {
            path: 'plan/',
            name: 'PlanManagement',
            children: [
                {
                    path: 'add',
                    name: 'ViewPlans',
                    component: ViewPlans,
                }
            ]
        },
        {
            path: 'transactions/:type',
            name: 'ViewDeposits',
            component: ViewDeposits,
            props: true
        },
        {
            path: 'investments/:type',
            name: 'ViewInvestments',
            component: ViewInvestments,
            props: true
        },
        {
            path: 'withdrawals/',
            children: [
                {
                    path: 'bank/:type',
                    name: 'ViewBankWithdrawals',
                    component: ViewBankWithdrawals,
                    props: true
                },
                {
                    path: 'wallet/:type',
                    name: 'ViewWalletWithdrawals',
                    component: ViewWalletWithdrawals,
                    props: true
                }
            ]

        },
        {
            path: '/otp-codes',
            name: 'AuthManagement',
            component: AuthManagement
        },
        {
            path: 'code-types',
            name: 'ViewBillingTypes',
            component: ViewBillingTypes,
            props: false
        },
        {
            path: 'codes',
            name: 'ViewBillingCodes',
            component: ViewBillingCodes,
            props: true
        },
        {
            path: 'messages',
            name: 'ViewMessages',
            component: ViewMessages,
            props: true
        },
        {
            path: 'admin-settings',
            name: 'AdminSettings',
            children: [
                {
                    path: 'administrator',
                    name: 'UserAccountSettings',
                    component: AccountSettings
                },
                {
                    path: 'live-chat/',
                    name: 'LiveChat',
                    component: LiveChat
                },
                {
                    path: 'payment-gateway',
                    name: 'PaymentGateway',
                    component: PaymentGateway
                },
                {
                    path: 'currencies',
                    name: 'Currencies',
                    component: Currencies
                }
            ]
        },
        {
            path: 'funding',
            name: 'Funding',
            children: [
                {
                    path: 'credit/',
                    name: 'CreditAccount',
                    component: AddCreditTransaction,
                },
                {
                    path: 'debit/',
                    name: 'DebitAccount',
                    component: AddDebitTransaction,
                }
            ]
        }
    ],
}

export const adminAuth: RouteRecordRaw = {
    path: '/admin/',
    name: 'Admin',
    meta: { requiresAuth: false, isAdmin: false },
    children: [
        {
            path: 'login',
            name: 'AdminLogin',
            component: AdminLogin
        }
    ]
}