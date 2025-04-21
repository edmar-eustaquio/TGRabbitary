<template>
    <GuestLayout>
        <ImageBlg
            title="Blog"
            image="2.jpg"
        />

        <BlogContent
            :products="products"
            :recent_blogs="recent_blogs"
        />
    </GuestLayout>
</template>

<script setup>
import ImageBlg from '@/Components/ImageBlg.vue';
import BlogContent from '@/Components/BlogContent.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';

    const {products, recent_blogs} = defineProps(['products', 'recent_blogs'])
    
</script>