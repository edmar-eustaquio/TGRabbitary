<template>
    <GuestLayout>
        <div class="px-4">
            <el-card class="mt-4" shadow="always">
                <div class="text-lg mb-5">
                    Delivery Address: {{ order.address }}
                </div>
    
                <el-table
                    :data="order.details"
                    row-key="id"
                    header-row-class-name="thead-light"
                >
                    <el-table-column
                        min-width="50px"
                        label="Qty"
                    >
                        <template v-slot="{ row }">
                            {{row.qty}}
                        </template>
                    </el-table-column>
    
                    <el-table-column
                        min-width="100px"
                        label="Product"
                    >
                        <template v-slot="{ row }">
                            {{row.product.name}}
                        </template>
                    </el-table-column>
    
                    <el-table-column 
                        min-width="70px" 
                        label="Price"
                    >
                        <template v-slot="{ row }">
                            {{ row.product.price }}
                        </template>
                    </el-table-column>
    
                    <el-table-column 
                        min-width="100px" 
                        label="Total"
                    >
                        <template v-slot="{ row }">
                            {{ getTotal(row) }}
                        </template>
                    </el-table-column>

                    <el-table-column 
                        v-if="order.status == 'Delivered'"
                        width="80px" 
                        label="ACTION" 
                        fixed="right">
                        <template v-slot="{ row }">
                            <el-tooltip
                                v-if="!row.rate"
                                class="box-item"
                                effect="dark"
                                content="Rate"
                                placement="top-start"
                            >
                                <el-button
                                    type="warning"
                                    size="small"
                                    @click="addRating(row.id)"
                                >
                                    <i class="fas fa-star"></i>
                                </el-button>
                            </el-tooltip>
                        </template>
                    </el-table-column>
                </el-table>
    
                <div class="flex justify-between gap-2 mt-5 flex-wrap">
                    <div class="w-full md:w-[49%]">
                        <div>
                            Payment Method: {{ order.payment_method }}
                        </div>
                        
                        <div class="mt-3">
                            Payment Status: 
                            <el-tag v-if="order.paid" type="success">Paid</el-tag>
                            <el-tag v-else type="danger">Unpaid</el-tag>
                        </div>
                    </div>
                    <div class="w-full md:w-[49%] md:text-right">
                        <div>
                            Order Status: 
                            <el-tag :type="getTagType(order.status)">{{ order.status }}</el-tag>
                        </div>
                        <el-button 
                            v-if="order.status == 'Pending'" 
                            class="mt-1" type="danger"
                            @click="onCancel"
                        >Cancel Order</el-button>
                    </div>
                </div>
            </el-card>
        </div>
    </GuestLayout>

    <!-- show rating modal -->
    <el-dialog 
        :width="isPC() ? '50%' : '70%'" 
        v-model="reviewModal" 
        :title="'Add Ratings'"
        :close-on-click-modal="false">
        <div class="mt-3">
            <label for="">Ratings<span class="text-red-600">*</span></label><br>
            <el-rate
                v-model="rating_form.rate"
                enable
                size="large"
                show-score
                text-color="#ff9900"
                score-template="{value} points"
            />
            <div
                v-if="form_errors.value.rate"
                class="text-red-600"
            >{{form_errors.value.rate[0]}}</div>
        </div>

        <div class="pt-5">
            <label for="">Comments<span class="text-red-600">*</span></label>
            <el-input
                v-model="rating_form.comment"
                placeholder="Enter Comments."
                type="text"
                clearable
                autosize
            />
            <div
                v-if="form_errors.value.comment"
                class="text-red-600"
            >{{form_errors.value.comment[0]}}</div>
        </div>

        <div class="mt-4">
            <el-upload
                ref="imgUpload"
                action="#"
                :auto-upload="false"
                class="upload-demo"
                :on-change="onChangeImage"
                accept="image/*,video/*"
                :on-remove="handleRemove"
                drag
                multiple
                list-type="picture"
            >
                <div class="flex justify-center">

                    <i class="el-icon--upload w-[100px] fas fa-cloud-arrow-up"></i>
                </div>

                <div class="el-upload__text">
                    Drop image or video here or <em>click to upload</em>
                </div>

                <template #tip>
                    <div class="el-upload__tip">
                        image with a size less than 2.048mb
                    </div>
                </template>
            </el-upload>
            <span
                v-if="form_errors.value['images.0']"
                class="text-red-600"
            >{{form_errors.value['images.0'][0]}}</span>
        </div>

        <template #footer>
            <span class="flex justify-end">
                <el-button 
                    type="warning" 
                    @click="reviewModal = false" 
                    plain
                >Cancel</el-button>
                <el-button 
                    type="primary" 
                    @click="onSubmitRatings"
                >Send</el-button>
            </span>
        </template>
    </el-dialog>
</template>

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { ref, reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from "axios";
import { ElMessage } from 'element-plus';

    const {order} = defineProps(['order'])
    console.log(order);

    const reviewModal = ref(false);
    const form_errors = reactive({value:{}})
    const rating_form = reactive({
        rate: 0,
        comment: null,
        order_details_id: null,
        images: []
    })

    function addRating(id){
        form_errors.value = {}
        rating_form.rate = 0
        rating_form.order_details_id= id
        rating_form.comment= null
        reviewModal.value = true;
    }

    function isPC(){
        return window.innerWidth > 960
    }

    function onCancel(){
        axios.post(route('cart.cancel', order.id))
            .then(function(){
                router.reload()
            }).catch(function(error){
                ElMessage.error('Something went wrong')
            })
    }

    function onSubmitRatings(){
        if (rating_form.rate === 0)
            rating_form.rate = null

        axios.post(route('rating.add'),rating_form, {
            headers: {
                'content-type': 'multipart/form-data'
            }
        })
            .then(function(){
                reviewModal.value = false;
                router.reload()
            }).catch(function(error){
                form_errors.value = error.response.data.errors
            })
            .finally(()=>{
                if (rating_form.rate == null)
                    rating_form.rate = 0
            })
    }

    function onChangeImage(img){
        rating_form.images.push(img.raw)
    }

    function handleRemove(img){
        rating_form.images = rating_form.images.filter(i=> i != img.raw)
    }

    function getTotal(d){
        return parseInt(d.qty) * parseInt(d.product.price)
    }

    function getTagType(status){
        if (status == 'Pending') return 'warning'
        if (status == 'Packed') return 'primary'
        if (status == 'Out for Delivery') return 'info'
        if (status == 'Delivered') return 'success'
        return 'danger'
    }

    
</script>