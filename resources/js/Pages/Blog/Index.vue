<template>
    <AuthLayout>
        <div class="flex justify-between flex-wrap">
            <div class="text-lg font-semibold">Blogs</div>
        </div>

        <BlogContent
            admin="yes"
            :products="products"
            :recent_blogs="recent_blogs"
        />
    </AuthLayout>

</template>

<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import BlogContent from '@/Components/BlogContent.vue';

    const {products, recent_blogs} = defineProps(['products', 'recent_blogs'])
    
</script>