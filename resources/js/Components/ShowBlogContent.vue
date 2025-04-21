<template>
    <div class="px-[1rem] lg:px-[5rem] mt-8 flex justify-center lg:justify-between gap-x-4 gap-y-8 flex-wrap">
        <div class="w-full lg:w-[65%]">
            <div class=" text-[32px]">
                {{blog.title}}
            </div>
            <img
                loading="lazy" 
                class="w-full mt-5 max-h-[60svh] object-contain"
                :src="`${$page.props.base_img_path}blog/${blog.image}`"
                alt=""
            />
            <div v-html="blog.body" class="text-gray-500 mt-5"></div>

            <div class="bg-red-50 p-7 flex gap-4 mt-8">
                <img
                    loading="lazy"  
                    class="w-[7rem] h-[8rem] md:w-[12rem] md:h-[13rem]"
                    :src="`${$page.props.base_img_path}website/${$page.props.setting.quote_image}`"
                    alt=""
                >
                <div>
                    <div class="text-[24px]">
                        {{$page.props.setting.quote_title}}
                    </div>
                    <div class="text-gray-500">
                        {{$page.props.setting.quote_body}}
                    </div>
                </div>
            </div>

            <div class="text-[25px] font-semibold">
                {{ blog.comments.length }} Comments
            </div>

            <template v-for="comment in blog.comments">
                <Comment 
                    :reply_to_id="comment.id"
                    :date="comment.created_at"
                    :body="comment.body"
                    :owner_name="comment.owner.name"
                />

                <template v-for="reply1 in comment.replies">
                    <Comment 
                        :reply_to_id="reply1.id"
                        :date="reply1.created_at"
                        :body="reply1.body"
                        :owner_name="reply1.owner.name"
                        class-name="ml-[2.5rem]"
                    />

                    <template v-for="reply2 in reply1.replies">
                        <Comment 
                            :reply_to_id="reply2.id"
                            :date="reply2.created_at"
                            :body="reply2.body"
                            :owner_name="reply2.owner.name"
                            class-name="ml-[5rem]"
                        />

                        <template v-for="reply3 in reply2.replies">
                            <Comment 
                                :reply_to_id="reply3.id"
                                :date="reply3.created_at"
                                :body="reply3.body"
                                :owner_name="reply3.owner.name"
                                class-name="ml-[7.5rem]"
                            />
                        </template>
                    </template>
                </template>

            </template>
    
            <div class="text-[25px] font-semibold mt-2">
                Leave a comment
            </div>

            <div class="bg-red-50 py-[2.3rem] px-[2rem]">
                <div class="text-gray-400">
                    Message *
                </div>
                <textarea v-model="body" @keyup.enter="onSubmit" :rows="10" class="w-full text-[20px] py-3 border-gray-400 mt-2"></textarea>
                <div @click="onSubmit" class="py-4 px-7 rounded-[2rem] bg-lime-600 text-white w-fit mt-2 hover:bg-lime-800 cursor-pointer">
                    Post Comment
                </div>
            </div>
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
import { ref, reactive } from 'vue';
import {
    ElMessage,
} from 'element-plus'
import axios from 'axios';
import { router } from '@inertiajs/vue3';
import Comment from '@/Components/Comment.vue';

    const {products, blog, recent_blogs} = defineProps(['products', 'blog', 'recent_blogs'])

    const form_errors = reactive({value:{}})
    const body = ref(null)

    function onSubmit(){
        axios.post(route('comment.store'), {
            body: body.value,
            blog_id: blog.id
        })
            .then(() => {
                ElMessage({
                    type: 'success',
                    message: 'Commented successfully.',
                })
                
                form_errors.value ={}
                body.value= null;
                router.reload()
            })
            .catch(err => {
                form_errors.value = err.response.data.errors
            })
    }

</script>