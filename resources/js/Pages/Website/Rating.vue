<template>
    <GuestLayout>
        <div class="m-5">
            <div class="text-[30px] font-semibold text-gray-700 mb-2">{{ product.name }}</div>
    
            <div class="bg-gray-200 flex justify-between gap-2 items-center flex-wrap rounded-md shadow-md px-5 py-3 mb-2">
                <el-rate
                    :model-value="(isNaN(rating) ? 0 : rating)"
                    show-score
                    text-color="#ff9900"
                    score-template="{value}"
                />
                <div>{{ ratings.length }} Reviews</div>
            </div>


            <div class="max-h-[60svh] overflow-y-auto">
                <el-card v-for="rate in _ratings" class="mt-2">
                    <div class="flex justify-between flex-wrap">
                        <div class="text-lg">{{ rate.user.name }}</div>
    
                        <el-rate
                            :model-value="parseInt(rate.rate)"
                            show-score
                            text-color="#ff9900"
                            score-template=""
                        />
                    </div>
                    <div class="text-gray-600 text-sm">
                        {{ rate.created_at.replace('T', '').substring(0, 10) }} 
                    </div>
    
                    <div class="mt-4">
                        {{ rate.comment }}
                    </div>

                    <div v-if="rate.images" class="mt-2">
                        <hr>
                        <div class="flex gap-2 mt-3 flex-wrap">
                            <template v-for="img in rate.images">
                                <div
                                    v-if="img.is_video"
                                    @click="previewSrc = $page.props.base_img_path + 'reviews/' + img.name"
                                    class="relative p-0 w-[24svw] h-[11svh] sm:w-[100px] sm:h-[85px] min-w-[70px] rounded-lg border border-red-700 transition duration-300 transform hover:scale-90 cursor-pointer overflow-hidden"
                                >
                                    <video 
                                        autoplay="false"
                                        class="preview-video size-full m-0"
                                    >
                                        <source :src="$page.props.base_img_path + 'reviews/' + img.name" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>

                                    <div class="absolute flex justify-center items-center inset-0">
                                        <i class="fas fa-circle-play text-[7svw] sm:text-[30px] rounded-full text-red-600 bg-white"></i>
                                    </div>
                                </div>
                                <img 
                                    v-else 
                                    loading="lazy" 
                                    @click="previewSrc = $page.props.base_img_path + 'reviews/' + img.name"
                                    class="w-[24svw] h-[11svh] sm:w-[100px] sm:h-[85px] min-w-[70px] rounded-lg object-contain border border-red-700 transition duration-300 transform hover:scale-90 cursor-pointer" 
                                    :src="$page.props.base_img_path + 'reviews/' + img.name" 
                                    alt=""
                                >
                            </template>
                        </div>
                    </div>
                </el-card>
            </div>
        </div>
    </GuestLayout>

    <ImagePreview v-if="previewSrc" :src="previewSrc" :on-close="() => previewSrc = null"/>
</template>

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { computed, ref, onMounted } from 'vue';
import ImagePreview from '@/Components/ImagePreview.vue';

    const {product, ratings } = defineProps(['product', 'ratings'])
    
    const rating = computed(() => ratings.reduce((total, val) => total + parseInt(val.rate), 0) / ratings.length)
    const _ratings = computed(() => ratings.map(val => {
            let imgs = []
            
            if (val.images)
                val.images.split('|').forEach(name => {
                    imgs.push({
                        name: name,
                        is_video: isVideo(name)
                    })
                });

            return {
                ...val,
                images: imgs
            }
        }))
    const previewSrc = ref(null)

    onMounted(() => {
        const videoElements = document.querySelectorAll('.preview-video');
        videoElements.forEach(video => {
            video.pause();
        });
    })
 
    function isVideo(filename) {
        const extension = filename.split('.').pop().toLowerCase();
        return extension === 'mp4' || extension === 'mov' || extension === 'avi'
    }

</script>