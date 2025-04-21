<template>
    <AuthLayout>
        <div class="flex justify-between flex-wrap">
            <div class="text-lg font-semibold">Blog Information</div>
        </div>

        <ShowBlogContent
            :blog="blog"
            :products="products"
            :recent_blogs="recent_blogs"
        />
    </AuthLayout>

</template>

<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import ShowBlogContent from '@/Components/ShowBlogContent.vue';

    const {blog, products, recent_blogs} = defineProps(['blog', 'products', 'recent_blogs'])
    
</script>