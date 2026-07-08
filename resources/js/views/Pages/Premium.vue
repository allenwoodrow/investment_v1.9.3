<template>
    <page-header title="Premium Trader" page="Platforms" class="w-full lg:w-full md:w-full"></page-header>
    
    <div id="features" class="py-4 px-4 lg:px-8 mt-5 mx-0 lg:mx-8">
        <div class="grid justify-content-center">

            <div class="col-12 mt-8 mb-8 p-2 md:p-8" style="border-radius: 20px; background: linear-gradient(0deg, rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0.6)), radial-gradient(77.36% 256.97% at 77.36% 57.52%, #efe1af 0%, #c3dcfa 100%)">
                <div class="flex flex-column justify-content-center align-items-center text-center px-3 py-3 md:py-0">
                    <h3 class="text-gray-900 mb-2">The Premium Trader Platform</h3>
                    <span class="text-gray-600 text-2xl">Trade from your browser direct to the Premium Trading Platform</span>
                    <p class="text-gray-900 sm:line-height-2 md:line-height-4 text-2xl mt-4" style="max-width: 800px">
                        An innovative new way to trade deserves an innovative trading platform. As a major forex broker in the US with its own proprietary software, we lets members place orders directly to the exchange, without the need for a broker or clearing desk. With 5,000 binary option contracts traded daily based on multiple asset classes, we designed software specifically to meet the needs of our members. As with all our platforms you get real-time indicative price data for free. And our unique price ladder charts let you place orders directly from the chart. You can go from selecting your markets to chart analysis to order placement, all from one screen.
                    </p>                
                </div>
            </div>
        </div>
    </div>

    <div class="surface-section px-4 py-8 md:px-6 lg:px-8 text-center">
    <div class="mb-3 font-bold text-2xl">
        <span class="text-900">Premium Access, </span>
        <span class="text-blue-600">Fast Trades</span>
    </div>
    <div class="text-700 text-sm mb-6">Our premium trading platform gives you an edge over other traders.</div>
    <div class="grid text-justify">
        <div class="col-12 md:col-4 mb-4 px-5">
            <Panel header="Free, Powerful Trading Platform">
                <p class="m-0">
                    Our free platforms give you direct access to thePrime Global Investment exchange and multiple global markets with free real-time indicative data. The only fees you ever pay on our platform are the transaction costs of each trade.
                </p>

                <p class="m-0">
                    Log in and trade from any PC (Windows, Mac, or Linux) wherever you are. No download needed, just your email and password.
You can even place an order using the Premium Trader, then manage and exit the trade using your Premium Trader platform.
                </p>
            </Panel>
        </div>
        <div class="col-12 md:col-4 mb-4 px-5">
            <Panel header="Depth of Market & Advanced Orders">
                <p class="m-0">
                    Our Depth of Market view will look familiar, but we designed it to be intuitive and easy to use. With a simple click and drag, you can place and modify your orders.
                </p>

                <p class="m-0">
                    Our unique Market Order with Protection (MOP) lets you enter or exit a trade immediately, but without risking a fill far from your intended price.
                </p>

                <p class="m-0">
                    If you prefer to let the market come to you, you can place a limit order and wait. We designed our platforms to let you trade the way you want to trade, using the strategies you trust.
                </p>
            </Panel>
        </div>
        <div class="col-12 md:col-4 mb-4 px-5">
            <Panel header="Free Real-Time Data & Charts">
                <p class="m-0">
                    On the Premium Trader platform, you’re streaming real-time indicative market data directly from the exchange. See the bid and offer price with the volume of contracts available.
                </p>

                <p class="m-0">
                    As a member of thePrime Global Investment platform, you don't go through a broker. You send your orders direct to the exchange.
                </p>

                <p class="m-0">
                    For technical analysts who use charts, our ladder charts feature a price ladder with buy and sell buttons. Go from analysis to order placement, all in the same window.
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
import { defineComponent, ref, onMounted, computed } from 'vue';
import Button from 'primevue/button'
import { PageHeader } from '../../components';

export default defineComponent({
    setup() {
        const base = import.meta.env.VITE_APP_URL
        const appName = import.meta.env.VITE_APP_NAME

        const banner = base + 'img/choose1.svg'
        const margins = base + 'img/choose2.svg'
        const leverage = base + 'img/choose4.svg'
        const liquidity = base + 'img/choose3.svg'

        return { banner, margins, leverage, liquidity, appName }
    },
    components: {
        Button, PageHeader
    }
})
</script>
<!-- 
<style scoped>
#hero {
    background: url('img/banner.png');
    /* linear-gradient(0deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.2)), radial-gradient(77.36% 256.97% at 77.36% 57.52%, #eeefaf 0%, #c3e3fa 100%); */
    /* height: 200px; */
    overflow: hidden;
}

@media screen and (min-width: 768px) {
    #hero {
        -webkit-clip-path: ellipse(150% 87% at 93% 13%);
        clip-path: ellipse(150% 87% at 93% 13%);
        /* height: 530px; */
    }
}

@media screen and (min-width: 1300px) {
    #hero > img {
        position: absolute;
    }

    #hero > div > p {
        max-width: 450px;
    }
}

@media screen and (max-width: 1300px) {
    #hero {
        height: 600px;
    }

    #hero > img {
        position: static;
        transform: scale(1);
        margin-left: auto;
    }

    #hero > div {
        width: 100%;
    }

    #hero > div > p {
        width: 100%;
        max-width: 100%;
    }
}
</style> -->