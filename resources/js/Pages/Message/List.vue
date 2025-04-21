<template>
    <AuthLayout>
        <div class="font-semibold mx-5 text-[2rem]">
            Messages
        </div>

        <div class="p-5">
            <div v-for="d in data" class="w-full mt-3 flex">
                <el-badge v-if="d.user_messages_count > 0" :value="d.user_messages_count" class="w-full flex-1">
                    <div 
                        class="w-full bg-black font-semibold text-[1.25rem] text-white p-3 rounded-md shadow-lg cursor-pointer hover:bg-gray-700"  
                        @click="() => onClick(d.id)"
                    >
                        {{d.name}}
                    </div>
                </el-badge>
                <div 
                    v-else
                    class="w-full bg-black font-semibold text-[1.25rem] text-white p-3 rounded-md shadow-lg cursor-pointer hover:bg-gray-700"  
                    @click="() => onClick(d.id)"
                >
                    {{d.name}}
                </div>
            </div>
        </div>
    </AuthLayout>
</template>

<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { router } from '@inertiajs/vue3';

    const {data} =defineProps(['data'])

    // const pusher = new Pusher('428b6a4d7d3fa0415c84', {cluster: 'ap1'});
    // const channel = pusher.subscribe('my-channel');
    // channel.bind('my-event', function() {
    //     fetchData()
    // });

    function onClick(id){
        router.get(route('admin.message.index', id))
    }
</script>