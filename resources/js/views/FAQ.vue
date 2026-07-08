<template>
<page-header title="Frequently Asked Questions" page="Company" class="w-full lg:w-full md:w-full"></page-header>
<div class="surface-section px-1 py-4 md:px-6 lg:px-6 text-">

<div class="grid mx-6 pt-6 md:pt-8 lg:pt-8">
    <div class="col-12 md:col-8 lg:col-8 my-auto flex flex-column text-center md:text-left lg:text-left lg:align-items-start md:align-items-start">
        <div class="card w-full">
            <Panel class="w-full mb-2 mt-2" header="What is Bitcoin" :collapsed="true" toggleable>
                <p class="m-0 text-justify">
                    Bitcoin is a form of digital currency which is based on an open source code that was created and is held electronically. Bitcoin is a decentralized form of currency, meaning that it does not belong to any form of government and is not controlled by anyone.    
                </p>
            </Panel>

            <Panel class="w-full mb-2 mt-2" header="Who Developed Bitcoin?" :collapsed="true" toggleable>
                <p class="m-0 text-justify">
                    The original Bitcoin code was designed by Satoshi Nakamoto under MIT open source credentials. In 2008 Nakamoto outlined the idea behind Bitcoin in his White Paper, which scientifically described how the cryptocurrency would function. Bitcoin is the first successful digital currency designed with trust in cryptography over central authorities. Satoshi left the Bitcoin code in the hands of developers and the community in 2010. Thus far hundreds of developers have added to the core code throughout the years.
                </p>
            </Panel>

            <Panel class="w-full mb-2 mt-2" header="What is Bitcoin Mining?" :collapsed="true" toggleable>
                <p class="m-0 text-justify">
                    Bitcoin mining is analogous to the mining of gold, but its digital form. The process involves specialized computers solving algorithmic equations or hash functions. These problems help miners to confirm blocks of transactions held within the network. Bitcoin mining provides a reward for miners by paying out in Bitcoin in turn the miners confirm transactions on the blockchain. Miners introduce new Bitcoin into the network and also secure the system with transaction confirmation. They are also rewarded network fees for when they harvest new coin and a time when the last bitcoin is found mining will continue.
                </p>
            </Panel>

            <Panel class="w-full mb-2 mt-2" header="Is Bitcoin Used For Only Illegal Activities?" :collapsed="true" toggleable>
                <p class="m-0">
                    This is a yet another controversial topic. Because of the freedom and the degree of anonymity that the use of Bitcoin offers, many users who were seeking to purchase or solicit illegal goods or services initially turned to the use of Bitcoin as a method of payment. But no, Bitcoin is fully transaparent and every transaction is trackable on the blockchain and the destination of all funds sent over the network are always public records. This makes bitcoin a the most suitable means of payment for legitimate and even government/public transactions.
                </p>
            </Panel>

            <Panel class="w-full mb-2 mt-2" header="Can Bitcoin Be Regulated In Any Way?" :collapsed="true" toggleable>
                <p class="m-0">
                    Again, when a user decides to use a specific type of software for their Bitcoin wallet, they are deciding what direction the Bitcoin network is heading towards. In other words, you need the cooperation of nearly every single user in order to modify any aspect of the Bitcoin protocol, this make bitcoin censorship resistant.
                </p>
            </Panel>

            <Panel class="w-full mb-2 mt-2" header="Is Bitcoin Anonymous?" :collapsed="true" toggleable>
                <p class="m-0">
                    Participants in Bitcoin transactions are identified by public addresses – those are the long strings of around 30 characters you see in a person’s Bitcoin address, usually starting with the numerals ‘1’ or ‘3’. For every transaction, the sending and receiving addresses are publicly-viewable.
                </p>
            </Panel>

            <Panel class="w-full mb-2 mt-2" header="How Can I Sell Bitcoins?" :collapsed="true" toggleable>
                <p class="m-0">
                    Bitcoins can be sold locally using LocalBitcoins, on Bitcoin brokerages / exchanges, using two-way Bitcoin Teller Machines (BTM’s) or you can pay for a good or service with them. Bitcoins can be sold to just about anyone as long as they have a Bitcoin address, and can be sold for any fiat currency in the world or traded for a physical good. Feel free to check out our recommended list of exchanges and brokerage services to sell your bitcoins online.
                </p>
            </Panel>
        </div>
    </div>

    <div class="flex justify-content-start sm:flex-order-2 col-12 md:col-4 lg:col-4 p-0" style="border-radius: 8px">
        <Panel header="Quick Facts">
            <p class="m-0">
                <i class="pi pi-chevron-right"></i> US-based, CFTC-regulated exchange 
            </p>

            <p class="mt-3">
                <i class="pi pi-chevron-right"></i> Member funds held in segregated US bank accounts 
            </p>
            <p class="mt-3">
                <i class="pi pi-chevron-right"></i> Trade multiple global markets from one account 
            </p>
            <p class="mt-3">
                <i class="pi pi-chevron-right"></i> Limited risk with predefined maximum possible loss 
            </p>

            <p class="mt-3">
                <i class="pi pi-chevron-right"></i> Low minimum opening account balance
            </p>
        </Panel>
    </div>
</div>
</div>
<div class="surface-section px-4 py-8 md:px-6 lg:px-8">
<div class="text-700 text-center">
    <!-- <div class="text-blue-600 font-bold mb-3"><i class="pi pi-discord"></i>&nbsp;POWERED BY DISCORD</div> -->
    <div class="text-900 font-bold text-5xl mb-3">Join Our Awesome Community</div>
    <div class="text-700 text-2xl mb-5">Ready to be part of the market moves with a global leader in online currency trading?</div>
    <router-link :to="{name: 'Register'}">
        <Button :label="$t('common.joinNow')" class="font-bold px-5 py-3 p-button-raised p-button-rounded white-space-nowrap"></Button>
    </router-link>
</div>
</div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { PageHeader } from '../components'
import Panel from 'primevue/panel';

export default defineComponent({
    setup() {
        const appName = import.meta.env.VITE_APP_NAME
        return { appName }
    },
    components: {
        PageHeader,
        Panel
    }
})
</script>