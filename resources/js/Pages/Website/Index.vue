<template>
    <GuestLayout>
        <el-carousel 
            :interval="2000"
            height="70svh"
        >
            <el-carousel-item v-for="item in ['1.jpg', '2.jpg', '3.jpg']" :key="item">
                <img loading="lazy" :src="'images/website/' + item" class="object-cover" alt="">
            </el-carousel-item>
        </el-carousel><br><br><br><br>

        <div class=" flex flex-wrap justify-center md:justify-between gap-3 md:px-[7rem] mt-4 ml-28 mr-28">
            <HomeIcon
                bg="bg-purple-400"
                title="free shipping"
                desc="ON ORDER OVER $"
            />
            <HomeIcon
                bg="bg-amber-300"
                title="ALWAYS FRESH"
                desc="PRODUCT WELL PACKAGE"
            />
            <HomeIcon
                bg="bg-blue-300"
                title="SUPERIOR QUALITY"
                desc="QUALITY PRODUCTS"
            />
            <HomeIcon
                bg="bg-orange-300"
                title="SUPPort"
                desc="24/7 support"
            />
        </div><br><br><br><br>

        <div class="flex flex-wrap px-4 gap-2 justify-center lg:px-16 mt-10">
            <div class="w-fit relative">
                <img loading="lazy" src="images/website/2.jpg" class="h-[300px]" alt="">
                <div class="absolute bottom-[60px] left-[20px] text-white">
                    <div class="text-[30px]">Rabbit</div>
                    <div 
                        @click="onShop('Rabbit')"
                        class="bg-transparent border border-white px-3 py-1 w-fit text-lg cursor-pointer hover:bg-white hover:text-black"
                    >Shop Now</div>
                </div>
            </div>
            <div class="w-fit relative">
                <img loading="lazy" src="images/website/food.png" class="h-[300px]" alt="">
                <div class="absolute bottom-[60px] left-[20px] text-black">
                    <div class="text-[30px]">Food</div>
                    <div 
                        @click="onShop('Food')"
                        class="bg-transparent border border-black px-3 py-1 w-fit text-lg cursor-pointer hover:bg-black hover:text-white"
                    >Shop Now</div>
                </div>
            </div>
            <div class="w-fit relative">
                <img loading="lazy" src="images/website/acc.jpg" class="h-[300px]" alt="">
                <div class="absolute bottom-[60px] left-[20px] text-black">
                    <div class="text-[30px]">Accesories</div>
                    <div 
                        @click="onShop('Accesories')"
                        class="bg-transparent border border-black px-3 py-1 w-fit text-lg cursor-pointer hover:bg-black hover:text-white"
                    >Shop Now</div>
                </div>
            </div>
            <div class="w-fit relative">
                <img loading="lazy" src="images/website/1.jpg" class="h-[300px]" alt="">
                <div class="absolute bottom-[60px] left-[20px] text-black">
                    <div class="text-[30px]">Package Bundle</div>
                    <div 
                        @click="onShop('Bundle Package')"
                        class="bg-transparent border border-black px-3 py-1 w-fit text-lg cursor-pointer hover:bg-black hover:text-white"
                    >Shop Now</div>
                </div>
            </div>
        </div>
        <!-- <div class="flex flex-wrap px-4 gap-4 justify-center lg:justify-between lg:px-16 mt-10">
            <div class="abosolute w-fit">
                <img loading="lazy" src="images/website/2.jpg" class="h-[300px]" alt="">
                <div class="relative bottom-[44px] bg-lime-700 text-white px-4 py-2 w-fit text-lg">Rabbit</div>
            </div>
            <div class="abosolute w-fit">
                <img loading="lazy" src="images/website/food.png" class="h-[300px]" alt="">
                <div class="relative bottom-[44px] bg-lime-700 text-white px-4 py-2 w-fit text-lg">Food</div>
            </div>
            <div class="abosolute w-fit">
                <img loading="lazy" src="images/website/acc.jpg" class="h-[300px]" alt="">
                <div class="relative bottom-[44px] bg-lime-700 text-white px-4 py-2 w-fit text-lg">Accesories</div>
            </div>
            <div class="abosolute w-fit">
                <img loading="lazy" src="images/website/1.jpg" class="h-[300px]" alt="">
                <div class="relative bottom-[44px] bg-lime-700 text-white px-4 py-2 w-fit text-lg">Package Bundle</div>
            </div>
        </div> -->

        <div class="text-center px-5 md:px-[17rem] mt-10">
            <div class="text-lime-700 italic text-lg">
                Featured Products
            </div>
            <div class="font-semibold text-[50px]">
                Our Products
            </div>
            <div class="text-gray-600">
                Did you know that rabbits sometimes “binky” or “dance” when they are really happy? It’s true! A binky is when they jump into the air and twist around!
            </div>
        </div>

        <div class="grid sm-[650px]:grid-cols-3 lg:grid-cols-4 px-7 gap-x-4 gap-y-6 mt-5">
        <!-- <div class="flex justify-center md:justify-evenly flex-wrap px-7 gap-2 mt-5"> -->
            <!-- class="rounded-lg shadow-lg text-center cursor-pointer w-full md:w-[32%] overflow-hidden bg-white h-fit pb-2" -->
            <div 
                class="rounded-lg shadow-lg text-center cursor-pointer w-full overflow-hidden bg-white h-fit pb-2"
                v-for="product in products"
                @click="() => onClickProduct(product.id)"
            >
                <img 
                    class="w-full aspect-[3/2] object-center object-cover transition duration-300 transform hover:scale-110" 
                    :src="'images/' + (product.images ?? '').split('|')[0]" 
                    alt=""
                    loading="lazy" 
                >
                <div class="uppercase text-lg mt-2">{{ product.name }}</div>
                <div class="text-lime-700 text-lg mt-1">₱{{ product.price }}.00</div>
            </div>
        </div>

    </GuestLayout>
</template>

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import HomeIcon from '@/Components/HomeIcon.vue';
import { router } from '@inertiajs/vue3';

    const {products} = defineProps(['products'])

    function onClickProduct(id){
        router.get(route('product.show', id))
    }
    function onShop(category){
        router.get(route('shop.per-category', category))
    }
</script>

<style scoped>
.el-carousel__item img {
  color: #475669;
  opacity: 0.75;
  line-height: 200px;
  height: 100% !important;
  width: 100% !important;
  margin: 0;
  text-align: center;
}

/* .el-carousel__item:nth-child(2n) {
  background-color: #99a9bf;
}

.el-carousel__item:nth-child(2n + 1) {
  background-color: #d3dce6;
} */
</style>