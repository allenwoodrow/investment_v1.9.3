<template>
    <page-header title="IRA" page="Markets" class="w-full lg:w-full md:w-full"></page-header>
    
    <div id="features" class="py-4 px-4 lg:px-8 mt-5 mx-0 lg:mx-8">
        <div class="grid justify-content-center">

            <div class="col-12 mt-8 mb-8 p-2 md:p-8" style="border-radius: 20px; background: linear-gradient(0deg, rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0.6)), radial-gradient(77.36% 256.97% at 77.36% 57.52%, #efe1af 0%, #c3dcfa 100%)">
                <div class="flex flex-column justify-content-center align-items-center text-center px-3 py-3 md:py-0">
                    <h3 class="text-gray-900 mb-2">What is a Roth IRA?</h3>
                    <span class="text-gray-600 text-2xl"></span>
                    <p class="text-gray-900 sm:line-height-2 md:line-height-4 text-2xl mt-4" style="max-width: 800px">
                        A Roth IRA is a type of individual retirement account. When you have a Roth IRA, you contribute after-tax dollars — up to a certain limit every year.
                    </p>                
                    <p class="text-gray-900 sm:line-height-2 md:line-height-4 text-2xl mt-4" style="max-width: 800px">
                        That money stays in your retirement investment account and can potentially earn investment returns as you work your way toward retirement. 
                    </p>
                    <p class="text-gray-900 sm:line-height-2 md:line-height-4 text-2xl mt-4" style="max-width: 800px">
                        Roth IRAs are similar to traditional IRAs in many ways, but they come with some differences. Discover exactly how Roth IRAs work, including how to open one, how much you can contribute, and which potential advantages or disadvantages you should be aware of. 
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="surface-section px-1 py-4 md:px-6 lg:px-6 text-center">

        <div class="text-center">
            <h2 class="text-900 font-normal mb-2"></h2>
            <span class="text-600 text-2xl"></span>
        </div>

        <div class="grid mx-6 pt-4 md:pt-7">
            <div class="col-12 md:col-6 lg:col-6 my-auto flex flex-column text-center md:text-left lg:text-left lg:align-items-start md:align-items-start">
                <h2 class="line-height-1 text-900 text-4xl font-normal">How does a Roth IRA work?</h2>
                <span class="text-700 line-height-3 mr-0 md:mr-2" style="max-width: 650px">
                    In a lot of ways, Roth IRAs work like some other popular retirement savings plans, such as 401(k)s and traditional IRAs.
                    <br>
                    Here are some of the similarities you'll find between these types of plans:
                </span>
                <p>
                    <ul class="list-none p-0 m-0 flex-grow-1">
                        <li class="flex align-items-center mb-3">
                            <i class="pi pi-check-circle text-green-500 mr-2"></i>
                            <span>You choose to put some of your income into these plans now to save for retirement later.</span>
                        </li>

                        <li class="flex align-items-center mb-3">
                            <i class="pi pi-check-circle text-green-500 mr-2"></i>
                            <span>
                                The money is a voluntary amount you can contribute each year, and you can change how much you contribute every year.
                            </span>
                        </li>

                        <li class="flex align-items-center mb-3">
                            <i class="pi pi-check-circle text-green-500 mr-2"></i>
                            <span>
                                You're limited by the Internal Revenue Code on how much you can contribute in any given year.
                            </span>
                        </li>

                        <li class="flex align-items-center mb-3">
                            <i class="pi pi-check-circle text-green-500 mr-2"></i>
                            <span>
                                The money is held in an investment account, so it has the potential for compound growth through the years. In the end, you hopefully end up with more than you contributed over the years.
                            </span>
                        </li>
                    
                    </ul>
                </p>
            </div>

            <div class="flex justify-content-end flex-order-1 sm:flex-order-2 col-12 md:col-6 lg:col-6 p-0" style="border-radius: 8px">
                <img :src="margins" class="w-11" alt="mockup" style="border-radius: 2em;" />
            </div>
        </div>

        <div class="grid mx-6 pt-5 md:pt-7 lg:pt-7">
            <div class="flex justify-content-end flex-order-2 col-12 md:col-6 lg:col-6 p-0 rounded" style="border-radius: 8px; height: 32em;">
                <img :src="liquidity" class="w-11" alt="mockup" />
            </div>
            <div class="col-12 sm:flex-order-1 md:flex-order-2 lg:flex-order-2 md:col-6 lg:col-6 my-auto flex flex-column md:text-left lg:text-left lg:align-items-end md:align-items-start">
                
                <h2 class="line-height-1 text-900 text-4xl font-normal">Basics of Roth IRA accounts</h2>
                <span class="text-700 line-height-3 mr-0 md:mr-2" style="max-width: 650px">
                    We'll cover the details about how to open a Roth IRA account, the contribution and income limits, and how and when you can withdraw your funds.
                    <br>
                    But first, let's take a quick look at how Roth IRA accounts work overall:
                </span>

                <p>
                    <ul class="list-none p-0 m-0 flex-grow-1 md:text-left lg:text-left lg:align-items-start md:align-items-start">
                        <li class="flex align-items-center mb-3">
                            <i class="pi pi-check-circle text-green-500 mr-2"></i>
                            <span>
                                You open a Roth IRA account.
                            </span>
                        </li>

                        <li class="flex align-items-center mb-3">
                            <i class="pi pi-check-circle text-green-500 mr-2"></i>
                            <span>
                                You begin funding the Roth IRA,  a observing annual contribution limits.
                            </span>
                        </li>

                        <li class="flex align-items-center mb-3">
                            <i class="pi pi-check-circle text-green-500 mr-2"></i>
                            <span>
                                Your funds remain in the account and are invested, potentially increasing in total through compound growth.
                            </span>
                        </li>

                        <li class="flex align-items-center mb-3">
                            <i class="pi pi-check-circle text-green-500 mr-2"></i>
                            <span>
                                Depending on how the account is set up, you make decisions about how the money is invested — popular options include bonds, equities, mutual funds and other assets such as commodities.
                            </span>
                        </li>

                        <li class="flex align-items-center mb-3">
                            <i class="pi pi-check-circle text-green-500 mr-2"></i>
                            <span>
                                You can take qualified distributions from your IRA without paying taxes or penalties — see the section below on withdrawals to find out what a qualified distribution is.
                            </span>
                        </li>
                    </ul>
                </p>
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

        const banner = base + 'img/banner.png'
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