<template>
    <Carousel :value="testimonials" :numVisible="1" :numScroll="1" :responsiveOptions="responsiveOptions" circular :autoplayInterval="3000">
        <template #item="slotProps">
                <p class="text-gray-900 sm:line-height-2 md:line-height-4 text-2xl mt-4" style="max-width: 800px">
                    {{ slotProps.data.testimony }}
                </p>
                <span class="text-gray-600 text-xl">~ {{slotProps.data.name }}, {{ slotProps.data.location }}</span>
        </template>
    </Carousel>


</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from 'vue';
import Carousel from 'primevue/carousel'
import Panel from 'primevue/panel'
import Avatar from 'primevue/avatar';
import { useNetwork } from '@/lib/request'
import type { Testimonial } from '../../types/UI'

export default defineComponent({
    setup() {
        const testimonials = ref<Testimonial[]>([])
        const responsiveOptions = ref([
            {
                breakpoint: '1400px',
                numVisible: 1,
                numScroll: 1
            },
            {
                breakpoint: '1199px',
                numVisible: 1,
                numScroll: 1
            },
            {
                breakpoint: '767px',
                numVisible: 1,
                numScroll: 1
            },
            {
                breakpoint: '575px',
                numVisible: 1,
                numScroll: 1
            }
        ]);
        
        onMounted(() => {
            const network = useNetwork()
            network.fetch<Testimonial[], {}>('testimonials').then((data) => {
                testimonials.value = data.data!
            }).catch((error) => {

            })
        });


        return { testimonials, responsiveOptions }
    },
    components: {
        Carousel, Panel, Avatar
    }
})
</script>