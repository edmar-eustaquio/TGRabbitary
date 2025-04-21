<template>
    <div :class="`${className ?? 'w-[300px]'} shadow-lg bg-white rounded-md px-3 pb-2 pt-4 mt-[50px] relative`">
        <div class="absolute top-[-10px] size-[70px] flex text-[1.5rem] justify-center items-center text-white bg-cyan-600 rounded-md shadow-lg" >
            <i :class="icon"></i>
        </div>

        <div class="ml-[75px] flex justify-end text-gray-600">
            {{title}}
        </div>
        <div class="flex justify-end text-black mb-5">
            {{ value }}
        </div>

    </div>
    <!-- <div :class="`${className ?? 'w-[300px]'} shadow-lg rounded-md px-3 pb-2 pt-4`" :style="`background-color: ${backColor};`">
        <div :class="`flex justify-between items-center text-black`">
            <div class="font-semibold">
                <div class="text-[16px]">{{ title }}</div>
                <div class="text-[30px]">{{ value }}</div>
            </div>
    
            <div :class="`rounded-full w-[50px] h-[50px] flex justify-center items-center`" :style="`background-color: ${iconColor};`">
                <i :class="icon + ' text-[17px]'" :style="`color: white;`"></i>
            </div>
        </div>
    </div> -->
</template>

<script setup>
    const {icon, title, value, iconColor, className} = defineProps(['icon', 'title', 'value', 'iconColor', 'className'])
    // const {icon, title, value, number, iconColor, backColor, className} = defineProps(['icon', 'title', 'value', 'number', 'iconColor', 'backColor', 'className'])
</script>