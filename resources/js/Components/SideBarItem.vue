<template>
    <Link :href="href" @click="closeIfMobile">
        <div class="w-full hover:bg-slate-400 px-5 py-2 cursor-pointer font-semibold text-lg">
            <i v-if="icon" :class="`${icon} mr-2`"></i> {{label}}
        </div>
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

    const {href, icon, label, closeIfMobile} = defineProps(['href', 'icon', 'label', 'closeIfMobile'])
</script>