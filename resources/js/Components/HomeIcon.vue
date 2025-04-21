<template>
    <div class="w-full lg:w-fit">
        <center>
            <div :class="bg + ' rounded-[50%] hover:bg-lime-500 flex items-center justify-center w-[120px] h-[120px] text-[50px] text-white'">
                <div class="border border-gray-300 rounded-[50%] flex items-center justify-center w-[100px] h-[100px]">
                    <i class="fas fa-truck-fast"></i>
                </div>
            </div>
            <div class="uppercase font-semibold">{{title}}</div>
            <div class="uppercase text-gray-600">{{desc}}</div>
        </center>
    </div>
</template>

<script setup>
    const {bg, title, desc} = defineProps(['bg', 'title', 'desc'])
</script>