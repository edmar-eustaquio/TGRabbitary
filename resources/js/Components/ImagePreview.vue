<template>
    <div class="flex fixed inset-0 items-center justify-center z-[1000] bg-[rgba(0,0,0,.85)]">
        <video 
            v-if="video"
            autoplay
            class="w-[80svw] lg:w-[65svw]"
            controls
        >
            <source :src="src" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <img 
            v-else
            :src="src" 
            loading="lazy"
            class="w-[80svw] lg:w-[65svw]"
            alt=""
        >

        <i 
            class="fas fa-x fixed text-white top-[20px] right-[20px] cursor-pointer hover:bg-red-400 w-[30px] h-[30px] text-center pt-[6px] rounded-full bg-red-700" 
            @click="onClose"
        ></i>
    </div>
</template>

<script setup>
    const {src, onClose} = defineProps(['src', 'onClose'])

    const video = isVideo(src)

    function isVideo(filename) {
        const extension = filename.split('.').pop().toLowerCase();
        return extension === 'mp4' || extension === 'mov' || extension === 'avi'
    }

</script>