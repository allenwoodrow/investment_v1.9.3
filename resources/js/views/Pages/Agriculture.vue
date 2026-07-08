<template>
    <page-header title="Agriculture" page="Markets" class="w-full lg:w-full md:w-full"></page-header>
    
    <div id="features" class="py-4 px-4 lg:px-8 mt-5 mx-0 lg:mx-8">
        <div class="grid justify-content-center">

            <div class="col-12 mt-8 mb-8 p-2 md:p-8" style="border-radius: 20px; background: linear-gradient(0deg, rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0.6)), radial-gradient(77.36% 256.97% at 77.36% 57.52%, #efe1af 0%, #c3dcfa 100%)">
                <div class="flex flex-column justify-content-center align-items-center text-center px-3 py-3 md:py-0">
                    <h3 class="text-gray-900 mb-2">We are lowering the entry bar for investors</h3>
                    <span class="text-gray-600 text-2xl">All over the world</span>
                    <p class="text-gray-900 sm:line-height-2 md:line-height-4 text-2xl mt-4" style="max-width: 800px">
                        Our vision is to change the way the world invests by creating a global platform that enables anyone to be a farmer. Our platform facilitates direct investment in agriculture in rural areas and created local economies which results in job and wealth creation for both our investors and vetted farmers. We believe our actions will result in creating a world with no inequality.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="surface-section px-1 py-4 md:px-6 lg:px-6">

        <div class="text-center">
            <h2 class="text-900 font-normal mb-2">Why Invest in Agriculture</h2>
            <span class="text-600 text-2xl"></span>
        </div>

        <div class="grid mx-6 pt-4 md:pt-7 mb-5">
            <div class="col-12 md:col-6 lg:col-6 my-auto flex flex-column text-center md:text-left lg:text-left lg:align-items-start md:align-items-start">
                <h2 class="line-height-1 text-900 text-4xl font-normal">Own real farm assets</h2>
                <span class="text-700 line-height-3 mr-0 md:mr-2" style="max-width: 650px">Our platform helps you invest with approved farmers who already have cows, macadamia trees, and other assets.</span>
                <p>We’re excited to announce a Farmland Ownership Opportunity where you own a portion of a farm and earn profits from its operations. {{ name }} invests funds from customers in operational farms in several regions around the world.</p>
            </div>

            <div class="flex justify-content-end flex-order-1 sm:flex-order-2 col-12 md:col-6 lg:col-6 p-0" style="border-radius: 1.5em">
                <img :src="margins" class="w-11" alt="mockup" style="border-radius: 2em;" />
            </div>
        </div>

        <div class="grid mx-1 pt-4 md:pt-7 mb-5">
            <Card class="md:col-2 sm:col-12 lg:col-3 bg-gold-500" style="overflow: hidden">
                <template #header>
                    
                </template>
                <template #title>1. Own</template>
                <template #subtitle>
                    You buy one of the 4,121 portions of the farm and become the shareholder in the Farmland Asset. Each portion equates to one share.
                </template>
                <template #content>
                    <p class="m-2 text-center text-900 text-white text-8xl"><i class="pi pi-money-bill text-8xl"></i></p>
                </template>
                <template #footer>
                    <div class="mt-1">
                        <span>MIN. INVESTMENT AMOUNT</span>
                        <h5>*Variable</h5>
                    </div>
                </template>
            </Card>

            <Card class="md:col-2 sm:col-12 lg:col-3 bg-gold-500" style="overflow: hidden">
               
                <template #title>2. Growth</template>
               
                <template #content className="mt-2">
                    <p class="text-justify">{{ name }} will perform the job of the managing agent on your behalf, the owner, with the aim of growing the value of the farm.</p>
                    <p class="m-2 text-center text-900 text-white text-8xl"><i class="pi pi-chart-bar text-8xl"></i></p>

                </template>
                <template #footer>
                    <div class="mt-1">
                        <span>MIN. HOLDING PERIOD</span>
                        <h5>2 Years</h5>
                    </div>
                </template>
            </Card>

            <Card class="md:col-2 sm:col-12 lg:col-3 bg-gold-500" style="overflow: hidden">
                <template #title>3. Profit</template>
                <template #content className="mt-2">
                    <p class="text-justify">
                        Annual rental profit earned by land owners will be based on the farm profitability per year. Our role is to maximise farm performance.
                    </p>
                    <p class="m-2 text-center text-900 text-white text-8xl"><i class="pi pi-chart-line text-8xl"></i></p>

                </template>
                <template #footer>
                    <div class="mt-1">
                        <span>TARGETED PROFIT</span>
                        <h5>19% pa</h5>
                    </div>
                </template>
            </Card>

            <Card class="md:col-2 sm:col-12 lg:col-3 bg-gold-500" style="overflow: hidden">
                <template #title>4. Sell</template>
                <template #content className="mt-2">
                    <p class="text-justify">
                        If the value of the land increases such that it makes sense to sell, owners can earn a higher payout from the sale.
                    </p>
                    <p class="m-2 text-center text-900 text-white text-8xl"><i class="pi pi-flag-fill text-8xl"></i></p>

                </template>
                <template #footer>
                    <div class="mt-1">
                        <span>MAX RETURN</span>
                        <h5>* Up to 900% return on infrastructure and land</h5>
                    </div>
                </template>
            </Card>
        </div>

        <div class="grid mx-6 pt-5 md:pt-7 lg:pt-7">
            <div class="flex justify-content-end flex-order-2 col-12 md:col-6 lg:col-6 p-0 rounded" style="border-radius: 1.5em; height: 32em;">
                <img :src="liquidity" class="w-full" alt="mockup" style="border-radius: 2em;" />
            </div>
            <div class="col-12 sm:flex-order-1 md:flex-order-2 lg:flex-order-2 md:col-6 lg:col-6 my-auto flex flex-column md:text-right lg:text-right lg:align-items-end md:align-items-end">
                <h2 class="line-height-1 text-900 text-4xl font-normal">
                    {{ name }} manages and you benefit as the owner.
                </h2>
                <span class="text-700 line-height-3 mr-0 md:mr-2" style="max-width: 650px">
                    The full benefits of owning the farm rest with you and it is our responsibility as {{ name }} to ensure that the farm performs to its full potential.
                </span>
                <span class="text-700 line-height-3 mr-0 md:mr-2" style="max-width: 650px">
                    We will perform the job of a managing agent on behalf of you, <strong>the owner</strong>. We manage the farm on your behalf including, managing the staff, looking after the orchards
                </span>
            </div>
        </div>
    </div>
    <div class="surface-section px-4 py-8 md:px-6 lg:px-8">
        <div class="text-700 text-center">
            <!-- <div class="text-blue-600 font-bold mb-3"><i class="pi pi-discord"></i>&nbsp;POWERED BY DISCORD</div> -->
            <div class="text-900 font-bold text-5xl mb-3">Join Our Awesome Community</div>
            <div class="text-700 text-2xl mb-5">Register and start earning immediately</div>
            <router-link :to="{name: 'Register'}">
                <Button :label="$t('common.joinNow')" class="font-bold px-5 py-3 p-button-raised p-button-rounded white-space-nowrap"></Button>
            </router-link>
        </div>
    </div>

</template>

<script lang="ts">
import { defineComponent, ref, onMounted, computed } from 'vue';
import Button from 'primevue/button'
import Card from 'primevue/card'
import { PageHeader } from '../../components';

export default defineComponent({
    setup() {
        const base = import.meta.env.VITE_APP_URL
        const name = import.meta.env.VITE_APP_NAME

        const banner = base + 'img/banner.png'
        const margins = base + 'img/citrus.jpg'
        const liquidity = base + 'img/farm.jpg'

        return { banner, margins, liquidity, name }
    },
    components: {
        Button, PageHeader, Card
    }
})
</script>