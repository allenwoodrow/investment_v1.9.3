<template>
    <div :id="container" ref="tradingview" />
</template>
<script lang="ts">
import { defineComponent } from 'vue'
import initWidget from './initWidget'

export default defineComponent({
    name: 'WCryptoHeatMap',
    props: {
        options: {
            type: Object,
            default: () => ({}),
        },
    },
    setup(props: any) {
        const options = {
            dataSource: "Crypto",
            width: 770,
            height: 400,
            hasTopBar: false,
            isDataSetEnabled: false,
            colorTheme: "dark",
            locale: 'en',
            ...props.options,
        }

        const { container, tradingview } = initWidget(
            options,
            'tradingview-crypto-heat-map',
            'tradingview-crypto-heat-map-script',
            'https://s3.tradingview.com/external-embedding/embed-widget-crypto-coins-heatmap.js'
        )

        return { container, tradingview }
    },
})
</script>