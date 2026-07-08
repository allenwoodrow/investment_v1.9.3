<template>
    <div ref="tradingview" :id="container" />
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import initWidget from './initWidget'
// import initWidget from '../composable/initWidget'

export default defineComponent({
    name: 'WTickerTape',
    props: {
        options: {
            type: Object,
            default: () => ({}),
        },
    },
    setup(props) {
        const options = {
            symbols: [
                {
                    proName: 'FOREXCOM:SPXUSD',
                    title: 'S&P 500',
                },
                {
                    proName: 'FOREXCOM:NSXUSD',
                    title: 'US 100',
                },
                {
                    proName: 'FX_IDC:EURUSD',
                    title: 'EUR/USD',
                },
                {
                    proName: 'BITSTAMP:BTCUSD',
                    title: 'Bitcoin',
                },
                {
                    proName: 'BITSTAMP:ETHUSD',
                    title: 'Ethereum',
                },
            ],
            showSymbolLogo: true,
            colorTheme: 'dark',
            isTransparent: false,
            displayMode: 'adaptive',
            locale: 'en',
            ...props.options,
        }

        const { container, tradingview } = initWidget(
            options,
            'tradingview-ticker-tape',
            'tradingview-ticker-tape-script',
            'https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js'
        )

        return { container, tradingview }
    }
})
</script>