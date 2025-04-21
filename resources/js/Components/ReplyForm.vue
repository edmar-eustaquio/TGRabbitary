<template>
    <div 
        @click="showReply"
        class="relative bg-gray-200 py-1 px-3 w-fit text-[12px] rounded-md mt-2 hover:text-white hover:bg-black active:bg-black cursor-pointer">
        REPLY
        
        <div v-show="replyId === reply_to_id" class="p-5 rounded-md shadow-lg bg-white absolute z-[1000] mt-2 left-[-60px]">
            <div class="text-gray-500">
                Reply *
            </div>
            <textarea 
                v-model="body" 
                @keydown.enter="onReply" 
                :rows="5" 
                class="w-full text-black text-[20px] py-3 border-gray-400 mt-2"
            ></textarea>
            <div class="flex gap-2">
                <div 
                    @click="onReply" 
                    class="py-3 px-8 rounded-[2rem] bg-lime-600 text-white w-fit mt-2 hover:bg-lime-800 cursor-pointer"
                >
                    Reply
                </div>
                <div 
                    @click="onCancel" 
                    class="py-3 px-8 rounded-[2rem] bg-red-700 text-white w-fit mt-2 hover:bg-red-400 cursor-pointer"
                >
                    Cancel
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
import {replyId} from '../Methods/ReplyId.js'

    const {reply_to_id} = defineProps(['reply_to_id'])

    const form_errors = reactive({value:{}})
    const body = ref(null)

    function onCancel(e){
        if (e)
            e.stopPropagation()
        
        body.value = null
        form_errors.value = {}
        replyId.value = null
    }

    function showReply(){
        replyId.value = replyId && replyId === reply_to_id ? null : reply_to_id
    }

    function onReply(){
        axios.post(route('comment.reply'), {
            body: body.value,
            reply_to_id: reply_to_id
        })
            .then(() => {
                ElMessage({
                    type: 'success',
                    message: 'Replied successfully.',
                })
                
                onCancel()
                router.reload()
            })
            .catch(err => {
                console.log(err);
                // form_errors.value = err.response.data.errors
            })
    }
</script>