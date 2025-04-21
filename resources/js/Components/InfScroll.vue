<template>
    <div :class="`overflow-y-auto ${className}`" @scroll="handleScroll">
        <slot/>

        <div v-if="loading" class="w-full flex justify-center">
            <div class="loader w-full"></div>
        </div>

        <div v-if="d" class="w-full text-center text-lg font-semibold text-gray-700">No more data</div>
    </div>
</template>

<script setup>
import {ref} from 'vue'
    const {loading, onScroll, className} = defineProps(['loading', 'onScroll', 'className'])
    
    let c = 10
    let d = ref(false)
    
    async function handleScroll(event) {
        if (d.value) return

        const scrollContainer = event.target;
        if (scrollContainer.scrollTop + scrollContainer.clientHeight + 5 >= scrollContainer.scrollHeight) {
            c += 10
            d.value = await onScroll(c)
        }
    }
</script>

<style scoped>
    .loader {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3f9ad6;
        border-left: 4px solid #3f9ad6;
        border-right: 4px solid #3f9ad6;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>