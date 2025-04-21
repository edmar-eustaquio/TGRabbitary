<template>
    <GuestLayout>
        <input 
            type="file" 
            style="display: none;" 
            id="up-img" 
            accept="image/*" 
            multiple
            @change="onSendImg"
        >

        <div class="h-[var(--nav-height)] bg-black"></div>

        <div class="p-5">
            <div class="flex gap-3 items-center mb-2">
                <img 
                    class="rounded-full size-[3rem] shadow-lg"
                    :src="`${$page.props.base_img_path}website/logo.jpeg`" 
                    alt=""
                >
                <!-- <div class="rounded-full size-[3rem] flex items-center justify-center bg-orange-400 text-gray-100">
                    A
                </div> -->

                <div>
                    <div class="font-semibold">
                        TGRabbitry Seller
                    </div>
                    <div class="text-gray-500 text-[13px] flex gap-1">
                        <div v-if="last_online_at === 'Online'" class="size-[11px] bg-green-600 rounded-full mt-[4px]"></div>
                        {{ last_online_at }}
                    </div>
                </div>
            </div>
            <hr>

            <div ref="messageRef" class="h-[60svh] overflow-y-auto pr-2">
                <template v-for="mess in data.value" >
                    <div :class="`my-2 flex ${mess.admin_id ? '' : 'justify-end'}`">
                        <el-card :class="`max-w-[70svw] ${getGridWidth(mess.images.length)}`" style="overflow-wrap: break-word;">
                            <template v-if="mess.body">
                                {{ mess.body }}
                            </template>

                            <div v-else :class="`grid gap-1 mb-2 ${getGridCount(mess.images.length)}`" >
                            <!-- <div v-else class="flex flex-wrap gap-2"> -->
                                    <!-- class="rounded-lg w-[32%]"  -->
                                <img 
                                    v-for="img in mess.images" 
                                    loading="lazy" 
                                    @click="previewSrc = $page.props.base_img_path + 'messages/' + img"
                                    :src="$page.props.base_img_path + 'messages/' + img" 
                                    class="rounded-lg w-full transition duration-300 transform hover:scale-90 cursor-pointer" 
                                    alt=""
                                >
                            </div>

                            <div v-if="mess.created_at" class="text-[12px] text-gray-500">
                                {{mess.created_at.replace('T', ' ').substring(0,19)}}
                            </div>
                        </el-card>
                    </div>
                </template>
            </div>
    
            <div class="flex justify-between gap-3 items-center mt-2">
                <i @click="onClickImg" class="fas fa-image text-[30px] text-teal-600 hover:text-teal-400 cursor-pointer"></i>

                <el-input 
                    v-model="body"
                    type="textarea" 
                    placeholder="Enter your message here"
                    @keyup.enter="onSend" 
                    autosize 
                    clearable 
                />

                <el-button v-if="body && body.trim() !== ''" type="success" @click="onSend">
                    <i class="fas fa-paper-plane"></i>
                </el-button>
            </div>
        </div>
    </GuestLayout>

    <ImagePreview v-if="previewSrc" :src="previewSrc" :on-close="() => previewSrc = null"/>
</template>

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import axios from 'axios';
import { ref, reactive, onBeforeMount, watch, nextTick } from 'vue';
import ImagePreview from '@/Components/ImagePreview.vue';

    const pusher = new Pusher('428b6a4d7d3fa0415c84', {cluster: 'ap1'});
    const channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function() {
        fetchData()
    });

    const data = reactive({value: []})
    const body = ref(null)
    const previewSrc = ref(null)
    const messageRef = ref(null) 
    const last_online_at = ref(null) 

    onBeforeMount(fetchData)

    watch(data, () => {
        nextTick(scrollToBottom);
    }, {deep: true})

    function getLastOnline(last_online){
        const last_online_date = new Date(last_online)
        const current_date = new Date()
        const timediff = current_date - last_online_date

        const years = Math.floor(timediff / (1000 * 60 * 60 * 24 * 30 * 365));
        if (years > 0) return `Active ${years} year${years > 1 ? 's' : ''} ago`

        const months = Math.floor(timediff / (1000 * 60 * 60 * 24 * 30));
        if (months > 0) return `Active ${months} month${months > 1 ? 's' : ''} ago`

        const days = Math.floor(timediff / (1000 * 60 * 60 * 24));
        if (days > 0) return `Active ${days} day${days > 1 ? 's' : ''} ago`

        const hours = Math.floor(timediff / (1000 * 60 * 60))
        if (hours > 0) return `Active ${hours} hour${hours > 1 ? 's' : ''} ago`
        
        const minutes = Math.floor(timediff / (1000 * 60));
        if (minutes > 0) return `Active ${minutes} minute${minutes > 1 ? 's' : ''} ago`

        return 'Online'
    }

    function fetchData(){
        axios.get(route('message.get'))
            .then(res => {
                last_online_at.value = getLastOnline(res.data.last_online_at)
                
                data.value = res.data.messages.map(val =>{
                    return {
                        ...val,
                        images: val.images ? val.images.split('|') : []
                    }
                })
            })
    }

    function onClickImg(){
        document.querySelector('#up-img').click();
    }

    function onSendImg(e){
        axios.post(route('message.send-image'), {images:e.target.files}, {
            headers: {
                'content-type': 'multipart/form-data'
            }
        })
            // .then(fetchData)
            .finally(() => {
                const f = document.querySelector('#up-img')
                f.value = null
                f.files = new DataTransfer().files

                body.value = null;
            })
    }

    function getGridCount(count){
        if (count > 2) return 'grid-cols-3'
        if (count > 1) return 'grid-cols-2'
        return ''
    }

    function getGridWidth(count){
        if (count == 2) return 'w-[50svw]'
        if (count == 1) return 'w-[40svw]'
        return ''
    }

    function onSend(){
        if(!body.value || body.value.trim() == '') return;

        axios.post(route('message.send-message'), {body:body.value})
            // .then(fetchData)
            .finally(() => {
                body.value = null;
            })
    }

    function scrollToBottom(){
        if (!messageRef.value) return
        
        messageRef.value.scrollTop = messageRef.value.scrollHeight + 1500
    }

</script>