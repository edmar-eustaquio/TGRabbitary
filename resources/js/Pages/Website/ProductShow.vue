<template>
    <input 
        type="file" 
        style="display: none;" 
        id="up-img" 
        accept="image/*" 
        multiple
        @change="onSendImg"
    >

    <GuestLayout>
        <div class="flex px-5 justify-center md:justify-between flex-wrap gap-2">
            <div class="w-full md:w-[48%]">
                <img loading="lazy" class="w-full h-[60svh] object-contain border border-red-600" :src="$page.props.base_img_path + activeImg" alt="">
                <div class="grid grid-cols-3 min-[530px]:grid-cols-4  md:grid-cols-3 gap-2 mt-1">
                    <img 
                        v-for="img in imgs" 
                        @click="() => activeImg = img"
                        :class="'w-full h-[16svh] cursor-pointer rounded-lg object-contain border ' + (img == activeImg ? 'border-red-700' : 'border-black')" 
                        :src="$page.props.base_img_path + img" 
                        alt=""
                    >
                </div>
            </div>
            <div class="w-full md:w-[48%] flex items-center">
                <div>
                    <div class="text-[25px] w-full">{{ product.name }}</div>
                    <div class="flex gap-4 items-center flex-wrap mt-1">
                        <div class="flex items-center gap-2">
                            <el-rate
                                :model-value="product.rating ?? 0"
                                disabled
                                show-score
                                text-color="#ff9900"
                            />
                            
                            <div class="text-gray-600">({{ product.rating_count }})</div>
                        </div>
                        <div class="text-gray-600">
                            Sold: {{ product.sold }}
                        </div>
                        <div class="text-blue-500">
                            Stock: {{product.stock ?? 0}}
                        </div>
                    </div>
                    <div class="text-[27px] mt-2">₱{{ product.price }}.00</div>
    
                    <div class="flex gap-4 items-center mt-5">
                        <el-input-number 
                            v-model="qty" 
                            type="number" 
                            :disabled="product.stock < 1"
                            :min="1" 
                            :max="getMax()"
                            width="w-[60px]" 
                        />
                        <el-button 
                            type="success" 
                            :disabled="product.stock < 1"
                            class="px-2 h-full bg-teal-500"
                            @click="onAdd"
                        >
                            Add to Cart
                        </el-button>
                    </div>

                    <div 
                        v-if="$page.props.user"
                        @click="onBuyNow"
                        :class="`px-5 py-1 rounded-md bg-transparent w-fit ${product.stock < 1 ? 'cursor-not-allowed text-blue-400' : 'cursor-pointer text-blue-600 hover:bg-blue-600 hover:text-white'} font-semibold border border-blue-600 mt-2`">
                        Buy Now
                    </div>
    
                    <div class="mt-5 text-gray-600">
                        {{ product.desc }}
                    </div>

                    <div class="mt-5">
                        <Link :href="route('rating.index', product.id)">
                            <el-button 
                                type="primary" 
                                class="px-2 h-full text-black font-bold"
                            >
                                Reviews
                            </el-button>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>

    <el-dialog 
        width="70%" 
        v-model="showModal" 
        :title="'Payment Method'"
        :close-on-click-modal="false">
        <div>
            <label for="">Address <span class="text-red-600">*</span></label>
            <el-input
                v-model="form_data.address"
                placeholder="Enter address"
                type="text"
                clearable
            />
            <span
                v-if="form_errors.value.address"
                class="text-red-600"
            >{{form_errors.value.address[0]}}</span>
        </div>

        <div class="pt-2">
            <label for="">Payment Method <span class="text-red-600">*</span></label>
            <el-select class="w-full" v-model="form_data.payment_method" placeholder="Select category">
                <el-option label="Cash On Delivery" value="COD" />
                <el-option label="G-Cash" value="G-Cash" />
            </el-select>
            <span
                v-if="form_errors.value.payment_method"
                class="text-red-600"
            >{{form_errors.value.payment_method[0]}}</span>
        </div>

        <template v-if="form_data.payment_method === 'G-Cash'">
            <div class="mt-3">
                <i>Note: You can only cancel a order before an hour</i>
                <br>
                <div class="mt-1">
                    Number: 
                    <b>
                        {{ $page.props.setting.number }}
                    </b>
                </div>

                <el-button @click="onClickImg" type="success" class="mt-2">
                    Send Reference Number
                </el-button>
            </div>
            
            <div class="flex justify-center mt-2">
                <img loading="lazy" :src="$page.props.base_img_path + 'website/number.jpeg'" class="w-[90%] sm:w-[250px]" alt="">
            </div>
        </template>

        <template #footer>
            <span class="flex justify-end">
                <el-button 
                    type="warning" 
                    @click="showModal = false" 
                    plain
                >Cancel</el-button>
                <el-button 
                    type="primary" 
                    @click="onSubmit"
                >Save</el-button>
            </span>
        </template>
    </el-dialog>

</template>

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import axios from 'axios';
import { ref, reactive } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import {
    ElMessage,
} from 'element-plus'
import {refreshCartCount} from '../../Methods/CartCount.js'

    const {product, user} = defineProps(['product', 'user'])

    const imgs = product.images.split('|')
    const activeImg = ref(imgs[0])
    const qty = ref(1)
    const showModal = ref(false);
    const form_errors = reactive({value:[]})
    const form_data = reactive({
        address:user?.address,
        payment_method:null,
        message_id:null,
        qty: 1,
        product_id: product.id
    })

    function getMax(){
        const stock = parseInt(product.stock ?? '1')
        return stock > 0 ? stock : 1
    }

    function onBuyNow(){
        if (product.stock > 0)
            showModal.value = true
    }

    function onAdd(){
        axios.post(route('cart.add'), {
            product_id: product.id,
            qty: qty.value
        })
        .then(()=>{
            refreshCartCount()
            qty.value = 1

            ElMessage({
                type: 'success',
                message: 'Added to cart successfully.',
            })
        })
        .catch(err => {
            if (err.response.status == 401){
                ElMessage({
                    type: 'error',
                    message: 'You need to login first',
                })
                router.get(route('login'))
                return;
            }
            if (err.response.data?.message)
                ElMessage({
                    type: 'error',
                    message: err.response.data.message,
                })
        })
    }
    
    function onClickImg(){
        document.querySelector('#up-img').click();
    }

    function onSendImg(e){
        axios.post(route('message.send-image'), {
            images:e.target.files,
            send_reference: 1
        }, {
            headers: {
                'content-type': 'multipart/form-data'
            }
        })
            .then(res => {
                form_data.message_id = res.data
            })
            .finally(() => {
                const f = document.querySelector('#up-img')
                f.value = null
                f.files = new DataTransfer().files

                ElMessage.success('Image has been sent!');
            })
    }

    function onSubmit(){
        form_data.qty = qty.value
        axios.post(route('cart.buy-now'), form_data)
            .then(()=>{
                router.reload()
                showModal.value = false;
                ElMessage({
                    type: 'success',
                    message: 'Buy item successfully.',
                 })
            })
    }

</script>