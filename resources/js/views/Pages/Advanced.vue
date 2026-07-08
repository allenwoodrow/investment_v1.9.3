<template>
    <page-header title="Intermediate" page="Education" class="w-full lg:w-full md:w-full"></page-header>

    <div class="surface-section px-1 py-4 md:px-6 lg:px-6 text-justify">
        <div class="p-2 col-12">
            <Card>
                <template #title>Opening Range Breakout Trading Strategy</template>
                <template #content>
                    <p class="m-0">
                        Breakouts are one of the most common trading strategies. They involve identifying a key price level you expect the price to break through, and then buying or selling at that price in order to take advantage. Generally breakouts are used when the market is already near the extreme high or low of the recent past. When the market is trending and moving strongly in one direction, breakout trading ensures that you never miss the move. 
                    </p>

                    <p class="mt-3">
                        One breakout strategy is the European Opening Range. This strategy typically focuses on EURUSD (Euro/U.S. Dollar), although it could be applied to any of the European majors.                     
                    </p>

                    <p class="m-0">
                        While the Forex Market is open for 24-hours a day (Sunday evening through Friday evening ET), market activity in a given pair is not necessarily consistent throughout.
                    </p>
                </template>
            </Card>
        </div>

        <div class="p-2 col-12">
            <Card>
                <template #title>Trading the European Opening Range has three steps:</template>
                <template #content>
                    <ol>
                        <li>First, you identify the high and low during the half hour just prior to the London open (2:30-3am ET). </li>
                        <li>Look for a breakout of this range +/- 10 pips, or 1/10th of the daily Average True Range (ATR), to maintain above/below this level for 10-15 minutes. This is an attempt to detect a direction of the ‘flow’ for the remainder of the day. </li>
                        <li>Finally, you attempt to manage this bullish or bearish bias by focusing on 1-, 2- or 5-minute charts and utilizing a combination of moving averages (13-sma, 144-ema & 169-ema) along with oscillators (RSI, Stochastics & CCI). </li>
                    </ol>

                    <p class="mt-3">
                        Other factors to include are major news announcements (usually in efforts of avoidance) as well as the time of day (when major markets open/close, option expirations, fixings, etc). If the Average True Range is achieved earlier in the week, the likelihood of it occurring twice in the same week is dramatically reduced. If this does occur, it’s typically in opposing directions. 
                    </p>

                    <p class="mt-3">
                        As a currency trader, when volatility begins to pick up you usually want to be trading, not sitting on the sidelines. As a result, if this strategy has yet to achieve the Average True Range target on Monday, Tuesday or Wednesday of a particular week, then it may be sensible to pay close attention to this tactic on Thursday and Friday. Conversely, if the ATR is reached earlier in the week it may be prudent to be on the lookout for potential market failures in the latter half of the week as they could be the marking of a false break and/or possible outright reversal.
                    </p>
                </template>
            </Card>
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

        const banner = base + 'img/choose2.svg'
        const liquidity = base + 'img/choose6.svg'
        return { banner, liquidity, appName }
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