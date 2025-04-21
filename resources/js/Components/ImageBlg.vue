<template>
    <div
        class="bg-center bg-cover h-[400px] flex items-center justify-center show-fade"
        :style="`background-image: url('${$page.props.base_img_path}website/${image}');`"
    > 
        <div 
            style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; animation: slide-up 500ms;"
            class="text-white"
        >
            <div 
                class="flex justify-center gap-4 text-xl"
            >
                <Link :href="route('home')">Home</Link>
                <div>{{title}}</div>
            </div>
            <div class="font-semibold text-[45px]">{{desc ?? title}}</div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

    const {image, title, desc} = defineProps(['image', 'title', 'desc'])
    
</script>