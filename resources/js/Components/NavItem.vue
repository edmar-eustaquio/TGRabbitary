<template>
    <Link 
        :href="href"  
        :class="'nav-item h-full hover:border-b border-b-white flex text-white items-center px-1 cursor-pointer' + (isActive() ? ' border-b' : '')"
    >
        <span class="mr-1">
            {{label}}
        </span>
        <slot/>
    </Link>
    <!-- <Link :href="href"  :class="'h-full hover:bg-gray-200 flex items-center px-4 cursor-pointer' + (isActive() ? ' text-lime-700' : '')">
        <span class="mr-1">
            {{label}}
        </span>
        <slot/>
    </Link> -->
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

    const {href, label} = defineProps(['href', 'label'])

    function isActive(){
        return href == window.location.href || href + '/' == window.location.href
    }
</script>