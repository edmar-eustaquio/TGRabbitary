<template>
    <GuestLayout>
        <ImageBlg
            title="Shop"
            image="Many3.jpg"
        />

        <el-tabs v-model="activeName" class="m-5" @tab-change="handleChange">
            <el-tab-pane v-for="tab in tabs" :label="tab" :name="tab">
                <InfScroll 
                    v-if="products.value.length > 0"
                    :loading="loading" 
                    :isDisabled="isDisabled"
                    :on-scroll="onLimit" 
                    class-name="max-h-[70svh] grid sm-[650px]:grid-cols-3 lg:grid-cols-4 px-7 gap-x-4 gap-y-6 pb-2"
                >
                    <div 
                        class="rounded-lg shadow-lg text-center cursor-pointer w-full overflow-hidden bg-white h-fit pb-2"
                        v-for="product in products.value"
                        @click="() => onClickProduct(product.id)"
                    >
                        <img 
                            loading="lazy" 
                            class="w-full aspect-[3/2] object-center object-cover transition duration-300 transform hover:scale-110" 
                            :src="$page.props.base_img_path + (product.images ?? '').split('|')[0]" 
                            alt=""
                        >
                        <div class="uppercase text-lg mt-2">{{ product.name }}</div>
                        <div class="text-lime-700 text-lg mt-1">₱{{ product.price }}.00</div>
                    </div>
                </InfScroll>

                <el-card 
                    shadow="always"
                    v-else
                >
                    <el-empty :description="`No ${tab.toLowerCase()} found.`" />
                </el-card>
            </el-tab-pane>
        </el-tabs>

    </GuestLayout>
</template>

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { router, Link } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, reactive, onBeforeMount } from 'vue'
import InfScroll from '@/Components/InfScroll.vue';
import ImageBlg from '@/Components/ImageBlg.vue';

    const {category} = defineProps(['category'])
    
    const activeName = ref(category ?? 'All')
    const products = reactive({value:[]})
    const pagination = reactive({
        category: category ?? null,
        limit: 10,
    })

    const loading = ref(false)
    const isDisabled = ref(false)

    const tabs = ['All', 'Rabbit', 'Food', 'Accesories', 'Bundle Package']

    onBeforeMount(() => {
        fetchData()
    })

    async function onLimit(limit){
        pagination.limit = limit
        return await fetchData()
    }

    async function fetchData (){
        loading.value = true
        let d = false

        await axios.get(route('product.get-user-scroll', pagination))
            .then(res => {
                if (products.value.length >= res.data.length){
                    d = true
                }
                products.value = res.data
            })
            .finally(() => {
                loading.value = false
            })
        
        return d
    }

    function handleChange () {
        pagination.category = activeName.value == 'All' ? null : activeName.value
        fetchData()
    }

    function onClickProduct(id){
        router.get(route('product.show', id))
    }

    function home(){
        window.location.href='/home';
    }

</script>