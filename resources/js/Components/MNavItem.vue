<template>
    <Link 
        :href="href" 
        :class="'flex pl-10 py-4 text-lg font-semibold hover:bg-lime-400 cursor-pointer border-t border-t-gray-400' + (isActive() ? ' text-lime-700' : '')"
    >
        <div class="mr-1">
            {{label}} 
        </div>
        <slot />
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

    const {href, label} = defineProps(['href', 'label'])

    function isActive(){
        return href == window.location.href || href + '/' == window.location.href
    }
</script>