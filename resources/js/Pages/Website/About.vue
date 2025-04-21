<template>
    <GuestLayout>
        <ImageBlg
            title="About"
            image="2.jpg"
        />

        <div class="px-5 lg:px-[7rem] flex justify-center lg:justify-between gap-x-2 gap-y-8 flex-wrap">
            <img loading="lazy" :src="`${$page.props.base_img_path}website/acc.jpg`" class="w-[70%] lg:w-[500px]" alt="">
            <div class="flex-1 flex items-center">
                <div>
                    <div class="font-semibold text-[40px]">
                        Welcome to TGRABBITRY an eCommerce website
                    </div>
                    <div class="text-gray-500">
                        Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.
                        <br> <br>
                        But nothing the copy said could convince her and so it didn’t take long until a few insidious Copy Writers ambushed her, made her drunk with Longe and Parole and dragged her into their agency, where they abused her for their.
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import ImageBlg from '@/Components/ImageBlg.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Link } from '@inertiajs/vue3';

</script>