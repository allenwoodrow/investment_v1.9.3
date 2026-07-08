<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <router-view></router-view>
            </div>
        </div>
        
    </div>
    
</template>


<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
    setup() {


        return {}
    }
})
</script>