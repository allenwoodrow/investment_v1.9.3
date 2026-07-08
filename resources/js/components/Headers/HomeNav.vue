
<template>
    <Menubar :model="items" class="w-full">
        <template #start>
            <img class="h-3rem" :src="logo" />
        </template>
        <template #item="{ item, props, hasSubmenu }">
            <router-link v-if="item.route" v-slot="{ href, navigate }" :to="item.route" custom>
                <a v-ripple :href="href" v-bind="props.action" @click="navigate" class="">
                    <span :class="item.icon" />
                    <span class="ml-2">{{ item.label }}</span>
                </a>
            </router-link>
            <a v-else v-ripple :href="item.url" :target="item.target" v-bind="props.action">
                <span :class="item.icon" />
                <span class="ml-2">{{ item.label }}</span>
                <span v-if="hasSubmenu" class="pi pi-fw pi-angle-right ml-2" />
            </a>
        </template>
            <template #end>
                <div class="flex align-items-center gap-2">
                    <router-link :to="{name: 'Login'}">
                        <Button severity="info" label="Login" icon="pi pi-key"></Button>
                    </router-link>

                    <router-link :to="{name: 'Register'}">
                        <Button severity="success" label="Register" icon="pi pi-user"></Button>
                    </router-link>
                </div>
            </template>
        </Menubar>
</template>

<script lang="ts">
import { ref, defineComponent } from "vue";
import Button from 'primevue/button'
import Menubar from 'primevue/menubar'
import { useConfig } from '@/lib/appConfig';

export default defineComponent({
    setup() {

        const items = ref([
            {
                label: 'Home',
                route: {name: 'Home'}
            },
            {
                label: 'Markets',
                root: true,
                items: [
                    {label: 'Crude oil', route: {name: 'CrudeOil'}},
                    {label: 'Roth IRA', route: {name: 'Ira'}},
                    {label: 'Comodities', route: {name: 'Commodities'}},
                    {label: 'Marijuana', route: {name: 'Marijuana'}},
                    {label: 'Stock Indices', route: {name: 'Indices'}},
                    {label: 'Bitcoin', route: {name: 'Crypto'}},
                    {label: 'Agriculture', route: {name: 'Agriculture'}}
                ]
            },
            {
                label: "Platforms",
                root: true,
                items: [
                    {label: 'Premium Traders', route: {name: 'Premium'}},
                    {label: 'MetaTrader 4', route: {name: 'Meta4'}},
                    {label: 'MetaTrader 5', route: {name: 'Meta5'}}
                ]
            },
            {
                label: 'Resources',
                root: true,
                items: [
                    {
                        label: 'Trader Courses',
                        root: true,
                        items: [
                            {label: 'Beginner', route: {name: 'Beginner'}},
                            {label: 'Intermediate', route: {name: 'Intermediate'}},
                            {label: 'Advanced', route: {name: 'Advanced'}}
                        ]
                    },
                    {
                        label: 'Trading Strategies',
                        root: true,
                        items: [
                            {label: 'Technical Analysis'},
                            {label: 'Fundamental Analysis'}
                        ]
                    }
                ]
            },
            {
                label: 'Company',
                root: true,
                items: [
                    {
                        label: 'About',
                        route: {name: 'About'},
                    },
                    {
                        label: 'Contact Us',
                        route: { name: 'Contact'}
                    },
                    {
                        label: 'FAQ',
                        route: {name: 'Faq'}
                    },
                ]
                
            },
            
        ]);
        const { logo } = useConfig()
        const title = import.meta.env.VITE_APP_NAME

        return { items, logo, title }
    },
    components: {
        Menubar
    }
})
</script>




<!-- <template>
    <div class="bg-transparent py-3 px-2 mx-0 md:mx-4 lg:mx-6 lg:px-8 flex align-items-center justify-content-between relative lg:static mb-3" style="background: transparent !important;">
                
                <a class="cursor-pointer block lg:hidden text-700 p-ripple" v-ripple v-styleclass="{ selector: '@next', enterClass: 'hidden', leaveToClass: 'hidden', hideOnOutsideClick: true }">
                    <i class="pi pi-bars text-4xl"></i>
                </a>
                <div class="align-items-center surface-0 flex-grow-1 justify-content-between hidden lg:flex absolute lg:static w-full left-0 px-6 lg:px-0 z-2" style="top: 120px">
                    <ul class="list-none p-0 m-0 flex lg:align-items-center select-none flex-column lg:flex-row cursor-pointer">
                        <li>
                            <a class="flex m-0 md:ml-5 px-0 py-3 text-900 font-medium line-height-3 p-ripple" v-ripple>
                                <span>Home</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex m-0 md:ml-5 px-0 py-3 text-900 font-medium line-height-3 p-ripple" v-ripple>
                                <span>Features</span>
                            </a>
                        </li>
                        <li>
                            <a  class="flex m-0 md:ml-5 px-0 py-3 text-900 font-medium line-height-3 p-ripple" v-ripple>
                                <span>Highlights</span>
                            </a>
                        </li>
                        <li>
                            <a  class="flex m-0 md:ml-5 px-0 py-3 text-900 font-medium line-height-3 p-ripple" v-ripple>
                                <span>Pricing</span>
                            </a>
                        </li>
                    </ul>
                    
                </div>
            </div>
</template>

-->