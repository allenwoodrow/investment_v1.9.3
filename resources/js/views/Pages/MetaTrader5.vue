<template>
    <page-header title="MetaTrader 5" page="Platforms" class="w-full lg:w-full md:w-full"></page-header>
    
    <div id="features" class="py-4 px-4 lg:px-8 mt-5 mx-0 lg:mx-8">
       
        <div class="grid justify-content-center">

            <div class="col-12 mt-8 mb-8 p-2 md:p-8" style="border-radius: 20px; background: linear-gradient(0deg, rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0.6)), radial-gradient(77.36% 256.97% at 77.36% 57.52%, #efe1af 0%, #c3dcfa 100%)">
                <div class="flex flex-column justify-content-center align-items-center text-center px-3 py-3 md:py-0">
                    <h3 class="text-gray-900 mb-2">MetaTrader5?</h3>
                    <span class="text-gray-600 text-2xl">What is it?</span>
                    <p class="text-gray-900 sm:line-height-2 md:line-height-4 text-2xl mt-4" style="max-width: 800px">
                        MetaTrader 5 is the latest platform for trading Forex, analyzing financial markets and using Expert Advisors. Mobile trading, Trading Signals and the Market are the integral parts of MetaTrader 5 that enhance your Forex trading experience. Millions of traders with a wide range of needs choose MetaTrader 5 to trade in the market. The platform offers ample of opportunities to traders of all skill levels: advanced technical analysis, flexible trading system, algorithmic trading and Expert Advisors, as well as mobile trading applications. Signals and Market additional services extend MetaTrader 5 frontiers. The Signals service allows you to copy trades of other traders, while the Market provides you with various Expert Advisors and Technical indicators that you can buy.
                    </p>                
                </div>
            </div>
        </div>
    </div>

    <div class="surface-section px-1 p-4 md:px-6 lg:px-6 text-center">

        <!-- <div class="text-center">
            <h2 class="text-900 font-normal mb-2">Why MetaTrader</h2>
            <span class="text-600 text-2xl">on {{ appName }} you can trade binary option and spread contracts based on the underlying futures prices of the following stock indices from the U.S. and the rest of the world.</span>
        </div> -->

        <div class="grid justify-content-center">

            <div class="col-12 md:col-6 lg:col-6 my-auto flex flex-column text-center md:text-left lg:text-left lg:align-items-start md:align-items-start">
                <h2 class="line-height-1 text-900 text-4xl font-normal">MetaTrader5 (MT5)</h2>
                <span class="text-700 line-height-3 mr-0 md:mr-2" style="max-width: 650px">
                    Through MT4 you can create your own personal workplace, whether you are trading forex, CFDs or Futures Contracts. Fully equipped with both back-office reporting functions and front-end terminals, this trading platform gives you access to the most essential tools and resources needed to analyze the market and to start trading. The MT4 is the answer to your needs; it's fast and effective, user friendly and flexible and it has been tested worldwide and chosen as a favorite amongst traders and institutions.
                </span>     
            </div>

            <div class="flex justify-content-end flex-order-1 sm:flex-order-2 col-12 md:col-6 lg:col-6 p-0" style="border-radius: 8px">
                <img :src="banner" class="w-11" alt="mockup" />
            </div>
        </div>

    </div>

    <div class="surface-section px-4 py-8 md:px-6 lg:px-8 text-center">
    <div class="mb-3 font-bold text-2xl">
        <span class="text-900">One Product, </span>
        <span class="text-blue-600">Many Advantages</span>
    </div>
    <div class="text-700 text-sm mb-6">Some of the advantages you will enjoy are</div>
    <div class="grid">
        <div class="col-12 md:col-4 mb-4 px-5">
            <span class="p-3 shadow-2 mb-3 inline-block surface-card" style="border-radius: 10px">
                <i class="pi pi-desktop text-4xl text-blue-500"></i>
            </span>
            <div class="text-900 mb-3 font-medium">Flexible and fast trading platform</div>
            <!-- <span class="text-700 text-sm line-height-3">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</span> -->
        </div>
        <div class="col-12 md:col-4 mb-4 px-5">
            <span class="p-3 shadow-2 mb-3 inline-block surface-card" style="border-radius: 10px">
                <i class="pi pi-chart-line text-4xl text-blue-500"></i>
            </span>
            <div class="text-900 mb-3 font-medium">Trading signals from top providers</div>
            <!-- <span class="text-700 text-sm line-height-3">Risus nec feugiat in fermentum posuere urna nec. Posuere sollicitudin aliquam ultrices sagittis.</span> -->
        </div>
        <div class="col-12 md:col-4 mb-4 px-5">
            <span class="p-3 shadow-2 mb-3 inline-block surface-card" style="border-radius: 10px">
                <i class="pi pi-android text-4xl text-blue-500"></i>
            </span>
            <div class="text-900 mb-3 font-medium">Expert advisors for algorithmic trading</div>
            <!-- <span class="text-700 text-sm line-height-3">Ornare suspendisse sed nisi lacus sed viverra tellus. Neque volutpat ac tincidunt vitae semper.</span> -->
        </div>
        <div class="col-12 md:col-4 mb-4 px-5">
            <span class="p-3 shadow-2 mb-3 inline-block surface-card" style="border-radius: 10px">
                <i class="pi pi-globe text-4xl text-blue-500"></i>
            </span>
            <div class="text-900 mb-3 font-medium">Global accessibility from PC, web & mobile</div>
            <!-- <span class="text-700 text-sm line-height-3">Fermentum et sollicitudin ac orci phasellus egestas tellus rutrum tellus.</span> -->
        </div>
        <div class="col-12 md:col-4 mb-4 px-5">
            <span class="p-3 shadow-2 mb-3 inline-block surface-card" style="border-radius: 10px">
                <i class="pi pi-shield text-4xl text-blue-500"></i>
            </span>
            <div class="text-900 mb-3 font-medium">Guardian angel and many analytic tools</div>
            <!-- <span class="text-700 text-sm line-height-3">Nec tincidunt praesent semper feugiat. Sed adipiscing diam donec adipiscing tristique risus nec feugiat. </span> -->
        </div>
        <div class="col-12 md:col-4 md:mb-4 mb-0 px-3">
            <span class="p-3 shadow-2 mb-3 inline-block surface-card" style="border-radius: 10px">
                <i class="pi pi-clock text-4xl text-blue-500"></i>
            </span>
            <div class="text-900 mb-3 font-medium">Multiple timeframes for trading purposes</div>
            <!-- <span class="text-700 text-sm line-height-3">Mattis rhoncus urna neque viverra justo nec ultrices. Id cursus metus aliquam eleifend.</span> -->
        </div>
    </div>
 </div>

    <div class="surface-section px-4 py-8 md:px-6 lg:px-8 text-center">
        <div class="text-900 font-bold text-6xl mb-4 text-center">Setup Process</div>
        <div class="text-700 text-xl mb-6 text-center line-height-3">
            MetaTrader5 is quite easy to setup and begin trading.
        </div>
        <div class="card flex flex-wrap gap-6">
            <Timeline :value="events">
                <template #marker="slotProps">
                    <span class="flex w-2rem h-2rem align-items-center justify-content-center text-white border-circle z-1 shadow-1" :style="{ backgroundColor: slotProps.item.color }">
                        <i :class="slotProps.item.icon"></i>
                    </span>
                </template>
                <template #content="slotProps">
                    <Card class="mt-3">
                        <template #title>
                            {{ slotProps.item.status }}
                        </template>
                        <template #content>
                            <!-- <img v-if="slotProps.item.image" :src="`https://primefaces.org/cdn/primevue/images/product/${slotProps.item.image}`" :alt="slotProps.item.name" width="200" class="shadow-1" /> -->
                            <p>
                                {{  slotProps.item.content }}
                            </p>
                        </template>
                    </Card>
                </template>
            </Timeline>


        </div>
    </div>



    <div class="surface-section px-4 py-8 md:px-6 lg:px-8">
        <div class="text-700 text-center">
            <!-- <div class="text-blue-600 font-bold mb-3"><i class="pi pi-discord"></i>&nbsp;POWERED BY DISCORD</div> -->
            <div class="text-900 font-bold text-5xl mb-3">Trading with MT5?</div>
            <div class="text-700 text-2xl mb-5">The most powerful platform in the world, with everything you need to perform your trades in an advanced area and become the best trader you can be – and more</div>
            <a target="_blank" href="https://www.MetaTrader5.com/en/download">
                <Button label="Download MT4" class="font-bold px-5 py-3 p-button-raised p-button-rounded white-space-nowrap"></Button>
            </a>
        </div>
    </div>
    



</template>

<script lang="ts">
import { defineComponent, ref, onMounted, computed } from 'vue';
import Button from 'primevue/button'
import Timeline from 'primevue/timeline';
import { PageHeader } from '../../components';

export default defineComponent({
    setup() {
        const base = import.meta.env.VITE_APP_URL
        const appName = import.meta.env.VITE_APP_NAME

        const banner = base + 'img/wallstreet.jpeg'
        const margins = base + 'img/choose2.svg'
        const leverage = base + 'img/choose4.svg'
        const liquidity = base + 'img/choose3.svg'
        const events = ref([
            { status: 'Download Software', icon: 'pi pi-download', color: '#9C27B0', content: "Download the platform and follow the quick, simple steps to proceed with installation."},
            { status: 'Launch Software', icon: 'pi pi-desktop', color: '#673AB7', content: "When installation is complete, launch the application" },
            { status: 'Login', date: '15/10/2020 16:15', icon: 'pi pi-lock', color: '#FF9800', content: "Select 'File' from the menu, then 'Login' and enter the MetaTrader login details that were emailed to you to access your trading account." },
            { status: 'Start Trading', icon: 'pi pi-database', color: '#607D8B', content: "Select which server you want from the drop-down menu and start trading. " }
        ]);

        return { banner, margins, leverage, liquidity, appName, events }
    },
    components: {
        Button, PageHeader, Timeline
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