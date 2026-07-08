import {
    Home, 
    FAQ,
    Contact,
    About,
    Terms,
    CrudeOil, 
    IRA,
    CryptoCurrency,
    Commodities,
    Indices,
    Marijuana,
    MetaTrader4,
    Premium,
    MetaTrader5,
    Beginner,
    Intermediate,
    Advanced,
    Agriculture
} from '../views'

import { HomeLayout } from '../layout'
import type { RouteRecordRaw } from 'vue-router'

export const web: RouteRecordRaw = {
    path: "/",
    name: "Landing",
    component: HomeLayout,
    meta: { requiresAuth: false },
    children: [
        {
        path: '/',
        name: 'Home',
        component: Home
        },
        {
        path: '/faq',
        name: 'Faq',
        component: FAQ
        },
        {
        path: '/markets',
        children: [
            {
            path: 'crude-oil',
            name: 'CrudeOil',
            component: CrudeOil
            },
            {
            path: 'individual-retirement-account',
            name: 'Ira',
            component: IRA
            },
            {
            path: 'indices',
            name: 'Indices',
            component: Indices
            },
            {
            path: 'crypto',
            name: 'Crypto',
            component: CryptoCurrency
            },
            {
            path: 'commodities',
            name: 'Commodities',
            component: Commodities
            },
            {
            path: 'marijuana',
            name: 'Marijuana',
            component: Marijuana
            },
            {
            path: 'agriculture',
            name: 'Agriculture',
            component: Agriculture
            }
        ]
        },
        {
        path: '/platforms',
        name: 'Platforms',
        children: [
            {
            path: 'premium-trader',
            name: 'Premium',
            component: Premium
            },
            {
            path: 'meta-trader-4',
            name: 'Meta4',
            component: MetaTrader4
            },
            {
            path: 'meta-trader-5',
            name: 'Meta5',
            component: MetaTrader5
            }
        ]
        },
        {
        path: '/education',
        name: 'Education',
        children: [
            {
            path: 'beginner',
            name: 'Beginner',
            component: Beginner
            },
            {
            path: 'intermediate',
            name: 'Intermediate',
            component: Intermediate
            },
            {
            path: 'advanced',
            name: 'Advanced',
            component: Advanced
            }
        ]
        },
        {
        path: '/company',
        name: 'Company',
        children: [
            {
            path: 'contact',
            name: 'Contact',
            component: Contact
            },
            {
            path: "about",
            name: "About",
            component: About
            },
            {
            path: "terms",
            name: "Terms",
            component: Terms
            }
        ]
        }
    ]
}