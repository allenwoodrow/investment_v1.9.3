import { ref, onMounted } from 'vue'
import { type Ref } from 'vue'

export interface Options {
    container_id: string
}

export interface ReturnedObject {
    container: Ref<string>
    tradingview: Ref<HTMLDivElement | undefined>
}


export default (options: unknown, widgetContainer: string, widgetScriptID: string, src: string): ReturnedObject => {
    const container = ref(widgetContainer)
    const scriptID = ref(widgetScriptID)
    const tradingview = ref<HTMLDivElement>()

    const canUseDOM = () => {
        return typeof window !== 'undefined' && window.document && window.document.createElement
    }

    const getScriptElement = () => {
        return document.getElementById(scriptID.value)
    }

    const scriptExists = () => {
        return getScriptElement() !== null
    }

    const appendScript = () => {
        if (!canUseDOM()) return
        if (scriptExists()) return

        const script = document.createElement('script')
        script.id = scriptID.value
        script.type = 'text/javascript'
        script.async = true
        script.src = src
        script.textContent = JSON.stringify(options)
        if (tradingview.value) tradingview.value.appendChild(script)
    }
    const appendStyle = () => {
        var iframe = document.getElementsByName('iframe')[0];
        var style = document.createElement('style');
        style.textContent =
        '#tradingview-forex-cross-rates-script {' +
        '    overflow: hidden !important;' +
        '}';
        iframe.appendChild(style);
    }
    onMounted(() => {
        setTimeout(() => {
            appendScript()
        }, 300)

        setTimeout(() => {
            appendStyle()
        }, 1000)
    })
    return { container, tradingview }
}