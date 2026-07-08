<template>
    <div ref="tradingview" :id="container" style="overflow-x: hidden !important; overflow-y: hidden !important; display:flex;" />
</template>

<script lang="ts">
import { defineComponent, onMounted } from 'vue'
import initWidget from './initWidget'

export default defineComponent({
    name: 'WForexCrossRates',
    props: {
        options: {
            type: Object,
            default: () => ({}),
        },
    },
    setup(props) {
        const options = {
            color: 'black',
            isTransparent: false,
            displayMode: 'regular',
            colorTheme: 'dark',
            width: '100%',
            height: '100%',
            locale: 'br',
            ...props.options,
        }

        const { container, tradingview } = initWidget(
            options,
            'tradingview-forex-cross-rates',
            'tradingview-forex-cross-rates-script',
            'https://s3.tradingview.com/external-embedding/embed-widget-forex-cross-rates.js'
        )
        return { container, tradingview }
    },
})
</script>