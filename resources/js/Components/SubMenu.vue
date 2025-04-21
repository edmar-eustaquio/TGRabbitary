<template>
    <div :class="`w-full ${className}`" @click="d = !d">
        <div class="flex hover:bg-slate-400 px-5 items-center justify-between py-2 cursor-pointer font-semibold text-lg">
            {{ title }}
            <i class="fas fa-greater-than text-[10px]"></i>
        </div>
        <div v-if="d" class="mx-4">
            <slot/>
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue'
    const {title, className} = defineProps(['title', 'className'])
    
    const d = ref(false)
</script>