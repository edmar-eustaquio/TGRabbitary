<template>
    <GuestLayout>
        <ImageBlg
            title="Blog"
            image="2.jpg"
        />

        <ShowBlogContent
            :blog="blog"
            :products="products"
            :recent_blogs="recent_blogs"
        />
    </GuestLayout>
</template>

<script setup>
import ImageBlg from '@/Components/ImageBlg.vue';
import ShowBlogContent from '@/Components/ShowBlogContent.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';

    const {products, blog, recent_blogs} = defineProps(['products', 'blog', 'recent_blogs'])

</script>