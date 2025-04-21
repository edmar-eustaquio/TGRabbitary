<template>
    <template v-if="admin">
        <div v-if="!showBlogForm" @click="showBlogForm = true" class="mt-4 mx-[1rem] lg:mx-[5rem] px-3 bg-cyan-500 w-fit py-1 text-white rounded-md hover:bg-cyan-800 cursor-pointer">
            Create Blog
        </div>
    
        <div v-if="showBlogForm" class="text-black mt-4 mx-[1rem] lg:mx-[5rem] rounded-md p-5 shadow-lg bg-white">
            <div class="font-semibold text-lg">
                New Blog
            </div>
            
            <div class="mt-2">
                <label for="">Title<span class="text-red-600">*</span> </label>
                <el-input
                    v-model="form_data.title"
                    placeholder="Enter title"
                    type="text"
                    clearable
                />
                <span
                    v-if="form_errors.value.title"
                    class="text-red-600"
                >{{form_errors.value.title[0]}}</span>
            </div>
    
            <div class="mt-2">
                <label for="">Body<span class="text-red-600">*</span> </label>
                <el-input
                    v-model="form_data.body"
                    placeholder="Enter body"
                    type="textarea"
                    autosize
                    clearable
                />
                <span
                    v-if="form_errors.value.body"
                    class="text-red-600"
                >{{form_errors.value.body[0]}}</span>
            </div>
            
            <div class="mt-4">
                <el-upload
                    ref="imgUpload"
                    action="#"
                    :auto-upload="false"
                    class="upload-demo"
                    :on-change="onChangeImage"
                    accept="image/*"
                    :on-remove="handleRemove"
                    drag
                    list-type="picture"
                >
                    <div class="flex justify-center">
    
                        <i class="el-icon--upload w-[100px] fas fa-cloud-arrow-up"></i>
                    </div>
    
                    <div class="el-upload__text">
                        Drop image here or <em>click to upload</em>
                    </div>
    
                    <template #tip>
                        <div class="el-upload__tip">
                            image with a size less than 2.048mb
                        </div>
                    </template>
                </el-upload>
                <span
                    v-if="form_errors.value['image']"
                    class="text-red-600"
                >{{form_errors.value['image'][0]}}</span>
            </div>
    
            <span class="flex justify-end mt-3">
                <el-button 
                    type="warning" 
                    @click="onCancel" 
                    plain
                >Cancel</el-button>
                <el-button 
                    type="primary" 
                    @click="onSubmit"
                >Post</el-button>
            </span>
        </div>
    </template>

    <div class="mx-[2rem] lg:mx-[5rem] mt-2 flex justify-center lg:justify-between gap-x-4 gap-y-8 flex-wrap">
        <div class="w-full lg:w-[65%]">
            
            <InfScroll 
                v-if="blogs.value.length > 0"
                :loading="loading" 
                :isDisabled="isDisabled"
                :on-scroll="onLimit"
            >
                <div
                    v-for="item in blogs.value"
                    class="flex flex-wrap gap-4 mt-5 items-center"
                >
                    <img 
                        class="object-center object-contain h-[190px] sm:h-[260px] w-full md:w-[50%]" 
                        :src="`${$page.props.base_img_path}blog/${item.image}`" 
                        alt=""
                    >

                    <div class="flex-1">
                        <div>
                            {{item.created_at.replace('T', ' ').substring(0, 15)}}
                            <span class="text-gray-400 text-[12px] ml-2">
                                <i class="fas fa-message text-gray-400 mr-1"></i>
                                {{item.comments ?? 0}}
                            </span>
                        </div>
                        <div class="mt-4 text-[25px] hover:text-lime-600">
                            {{item.title}}
                        </div>
                        <div v-html="item.body" class="text-gray-500 mt-5"></div>

                        <Link :href="route(admin ? 'blog.show' : 'sblog', item.id)">
                            <button class="bg-lime-600 border border-lime-600 text-white py-2 px-4 rounded-[2rem] mt-3 text-[19px] hover:text-lime-600 hover:bg-transparent">
                                Read more
                            </button>
                        </Link>
                    </div>
                </div>
            </InfScroll>

            <el-card 
                shadow="always"
                v-else
            >
                <el-empty description="No blogs yet" />
            </el-card>
        </div>

        <div class="w-full lg:flex-1">
            <div>
                <div class="text-[1.3rem] mb-4">Categories</div>
                <div class="block">
                    <template v-for="product in products">
                        <div class="flex justify-between my-3">
                            <div>{{product.name}}</div>
                            <div class="text-gray-400">({{product.stock}})</div>
                        </div>
                        <hr>
                    </template>
                </div>
            </div>

            <div class="mt-5">
                <div class="text-[1.3rem] mb-1">Recent Blog</div>
                <div class="block">
                    <div v-for="recent_blog in recent_blogs" class="flex justify-between gap-4 mt-3">
                        <img 
                            :src="`${$page.props.base_img_path}blog/${recent_blog.image}`" 
                            class="object-contain object-center h-[80px] w-[80px]"
                            alt=""
                        >
                        <div class="flex-1">
                            <div class="text-[1.3rem] hover:text-lime-600">
                                {{recent_blog.title}}
                            </div>
                            <div class="text-gray-500 mt-2 text-[13px] flex lg:block">
                                <div>
                                    <i class="fas fa-calendar-days mr-1"></i>
                                    {{recent_blog.created_at.replace('T', ' ').substring(0, 16)}}
                                </div>
                                <div class="ml-4 lg:ml-0 lg:mt-1">
                                    <i class="fas fa-user mr-1"></i>
                                    {{recent_blog.owner_name}}
                                    <i class="fas fa-message mr-1 ml-2"></i>
                                    {{recent_blog.comments}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-[1.3rem] mt-8 mb-4">
                    BUNNYPRO'S BLOG SECTION
                </div>
                <div class="text-gray-400">
                    Your go-to source for insightful articles about everything rabbit-related! Explore a treasure trove of knowledge, from caring for your furry companions to tips on rabbit breeding and health. This articles are curated to provide valuable information and guidance to rabbit enthusiasts of all levels. Dive into the world of rabbits and discover a wealth of fascinating insights and practical advice!
                </div>
            </div>
        </div>
    </div>
</template>


<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref, reactive, onBeforeMount } from 'vue';
import {
    ElMessage,
} from 'element-plus'
import InfScroll from '@/Components/InfScroll.vue';
import axios from 'axios';

    const {admin, products, recent_blogs} = defineProps(['admin', 'products', 'recent_blogs'])
    
    const imgUpload = ref(null)
    const showBlogForm = ref(false)
    const form_errors = reactive({value:{}})
    const form_data = reactive({
        body: null,
        title: null,
        image:null,
    })
    const limit = ref(10)
    const loading = ref(false)
    const isDisabled = ref(false)
    const blogs = reactive({value:[]})

    onBeforeMount(()=>{
        fetchData()
    })

    async function onLimit(lmt){
        limit.value = lmt
        return await fetchData()
    }

    async function fetchData (){
        loading.value = true
        let d = false

        await axios.get(route('blog.get-scroll', {limit: limit.value}))
            .then(res => {
                if (blogs.value.length >= res.data.length){
                    d = true
                }
                
                blogs.value = res.data
            })
            .finally(() => {
                loading.value = false
            })
        
        return d
    }

    function onCancel(){
        form_errors.value ={}
        if (imgUpload.value)
            imgUpload.value.clearFiles();
        form_data.image = null
        form_data.title = null
        form_data.body = null

        showBlogForm.value = false
    }
    
    function onChangeImage(img){
        form_data.image = img.raw
    }

    function handleRemove(){
        form_data.image = null
    }

    function onSubmit(){
        if (form_data.body)
            form_data.body = form_data.body.replaceAll('\n', '<br>')
        
        axios.post(route('blog.store'), form_data,{
            headers: {
                'content-type': 'multipart/form-data'
            }
        })
            .then(() => {
                fetchData()
                ElMessage({
                    type: 'success',
                    message: 'Posted a blog successfully.',
                })
                onCancel()
            })
            .catch(err => {
            if (err.response.status == 401){
                ElMessage({
                    type: 'error',
                    message: 'You need to login first',
                })
                router.get(route('login'))
                return;
            }
                form_errors.value = err.response.data.errors
            })
    }

</script>