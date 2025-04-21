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
        <ImageBlg
            title="Cart"
            desc="My Cart"
            image="cart.jpg"
        />

        <div v-if="data.carts.length > 0" class="p-7">
            <div 
                v-for="cart in data.carts" 
                class="flex justify-between gap-2 mt-4 items-center shadow-md bg-white rounded-md px-4 py-3"
            >
                <div @click="() => onRemove(cart.id)" class="text-red-600 hover:text-red-400 mr-4 cursor-pointer">
                    <i class="fas fa-trash"></i>
                </div>

                <div class="flex-1">
                    <div class="flex gap-2 flex-wrap items-center">
                        <Link :href="route('product.show', cart.product.id)">
                            <img 
                                :src="$page.props.base_img_path + cart.product.images.split('|')[0]" 
                                alt=""
                                loading="lazy" 
                                class="w-[100px] h-[90px] object-center object-cover transition duration-300 transform hover:scale-110 cursor-pointer"
                            >
                        </Link>
                        <div class="flex-1 flex justify-between gap-3 items-center flex-wrap">
                            <div>
                                <div>{{cart.product.name}}</div>
                                <div><b>Size:</b> {{cart.product.size}}</div>
                                <div><b>Price:</b> {{cart.product.price}}</div>
                                    <!-- :max="parseInt(cart.product.stock)"  -->
                                <el-input-number 
                                    v-model="qty[cart.id]"
                                    :min="1" 
                                    @change="() => onUpdateQty(cart.id)"
                                />
                            </div>
                            <div class="font-semibold text-lg">
                                Total: {{computeTotal(qty[cart.id], cart.product.price)}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end text-lg mt-2">
                Grand Total: 
                <div class="ml-4">{{ getGrandTotal() }}</div>
            </div>

            <div class="flex justify-end mt-2">
                <el-button 
                    size="large" 
                    type="success"
                    :disabled="data.carts.length == 0"
                    @click="onCheckOut"
                >Checkout</el-button>
            </div>

        </div>

        <el-card v-else shadow="always" class="m-7" >
            <el-empty description="No items added to cart yet." />
        </el-card>
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

        <div v-if="form_data.payment_method === 'G-Cash'" class="mt-3 flex justify-between gap-2 flex-wrap">
            <div class="flex-1">
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
            
            <img loading="lazy" :src="$page.props.base_img_path + 'website/number.jpeg'" class="w-[100px]" alt="">
        </div>

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
import { ref, reactive, onBeforeMount } from 'vue';
import { Link } from '@inertiajs/vue3';
import {
    ElMessage,
} from 'element-plus'
import {refreshCartCount} from '../../Methods/CartCount.js'
import ImageBlg from '@/Components/ImageBlg.vue';

    const {user} = defineProps(['user'])
    
    const qty = reactive({})
    const data = reactive({carts: []})
    const showModal = ref(false);
    const form_errors = reactive({value:[]})
    const form_data = reactive({
        address:user.address,
        payment_method:null,
        message_id:null,
    })

    onBeforeMount(() => {
        fetchData()
    })

    function fetchData(){
        axios.get(route('cart.get'))
            .then(res => {
                data.carts = res.data
                for(let i in data.carts)
                    qty[data.carts[i].id] = parseInt(data.carts[i].qty)
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

    function getGrandTotal(){
        let total = 0
        for(let i in data.carts){
            const c = data.carts[i]
            total += qty[c.id] * parseInt(c.product.price)
        }

        return total
    }

    function computeTotal(qty, price){
        return qty * parseInt(price)
    }

    function onRemove(id){
        axios.post(route('cart.delete', id))
            .then(()=>{
                refreshCartCount()
                fetchData()
            })
    }

    function onUpdateQty(id){
        axios.post(route('cart.update-qty', id), {qty: qty[id]})
            .then(()=>{
                fetchData()
            })
            .catch(err => {
                ElMessage({
                    type: 'error',
                    message: err.response.data?.message,
                 })
            })
    }

    function onCheckOut(){
        showModal.value = true;
    }

    function onSubmit(){
        axios.post(route('cart.checkout'), form_data)
            .then(()=>{
                refreshCartCount()
                fetchData()
                showModal.value = false;
                ElMessage({
                    type: 'success',
                    message: 'Checked out successfully.',
                 })
            })
            .catch(err => {
                ElMessage({
                    type: 'error',
                    message: err.response.data?.message,
                 })
            })
    }


</script>