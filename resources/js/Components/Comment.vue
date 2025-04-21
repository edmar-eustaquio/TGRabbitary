<template>
    <div :class="`mt-6 flex gap-4 ${className}`">
        <div class="rounded-full size-[3rem] flex items-center justify-center bg-orange-400 text-gray-100">
            {{getUserProfileAbbr()}}
        </div>

        <!-- <img
            loading="lazy"  
            class="w-[3rem] h-[2.8rem] rounded-full"
            :src="`${$page.props.base_img_path}website/2.jpg`"
            alt=""
        > -->
        <div>
            <div class="text-[20px]">
                {{owner_name}}
            </div>
            <div class="text-gray-400 text-[12px]">
                {{ date.replace('T', ' ').substring(0, 16) }}
            </div>
            <div class="text-gray-500">
                {{ body }}
            </div>

            <ReplyForm :reply_to_id="reply_to_id"/>
        </div>
    </div>
</template>

<script setup>
import ReplyForm from './ReplyForm.vue';

    const {owner_name, date, body, reply_to_id, className} = defineProps(['owner_name', 'date', 'body', 'reply_to_id', 'className'])
    
    function getUserProfileAbbr(){
        let abbr = ''
        owner_name.trim().split(' ').forEach(n => {
            abbr += n.substring(0, 1)
        });
        return abbr.toUpperCase()
    }
</script>