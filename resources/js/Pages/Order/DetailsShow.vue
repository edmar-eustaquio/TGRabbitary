<template>
    <AuthLayout>
        <el-card class="mt-4" shadow="always">
            <div class="flex justify-between gap-4">
                <div class="text-lg font-semibold">
                    Client Name: {{ order.user.name }}
                </div>

                <Link :href="route('order.invoice', order.id)">
                    <el-button type="success">Create Invoice</el-button>
                </Link>
            </div>
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
                    min-width="220px"
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
            </el-table>

            <div class="flex justify-between gap-2 mt-5">
                <div class="w-[49%]">
                    <div>
                        Payment Method: {{ order.payment_method }}
                    </div>
                    <div class="mt-3">
                        Payment Status: 
                        <el-tag v-if="paid" type="success">Yes</el-tag>
                        <el-tag v-else type="danger">No</el-tag>
                    </div>
                </div>
                <div class="w-[49%]">
                    <div class="text-right">
                        Order Status: 
                        <el-tag :type="getTagType(status)">{{ status }}</el-tag>
                    </div>

                    <el-button 
                        v-if="!paid"
                        type="success" 
                        class="mt-2" 
                        @click="onMarkAsPaid"
                    >Mark as Paid</el-button>

                    <el-button 
                        type="primary" 
                        :class="'mt-2' + (paid ? ' w-full' : '')" 
                        @click="showModal = true"
                    >Update Status</el-button>
                </div>
            </div>
        </el-card>
    </AuthLayout>

    <el-dialog 
        :width="isPC() ? '50%' : '70%'" 
        v-model="showModal" 
        title="Update Status"
        :close-on-click-modal="false"
    >
        <div>
            <label for="">Status <span class="text-red-600">*</span></label>
            <el-select 
                v-model="status" 
                filterable 
                placeholder="Select status"
            >
                <template v-for="st in ['Pending', 'Packed', 'Out for Delivery', 'Delivered', 'Cancelled']">
                    <el-option :label="st" :value="st" />
                </template>
            </el-select>
        </div>

        <template #footer>
            <span class="flex justify-end">
                <el-button 
                    type="warning" 
                    @click="onCancel" 
                    plain
                >Cancel</el-button>
                <el-button 
                    type="primary" 
                    @click="onSubmit"
                >Update</el-button>
            </span>
        </template>
    </el-dialog>
</template>

<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { ref } from 'vue';
import axios from 'axios';
import {
    ElMessage,
    ElMessageBox,
} from 'element-plus'
import { Link } from '@inertiajs/vue3';

    const {order} = defineProps(['order'])

    const showModal = ref(false)
    const status = ref(order.status)
    const paid = ref(order.paid)

    function getTotal(d){
        return parseInt(d.qty) * parseInt(d.product.price)
    }

    function onCancel(){
        status.value = order.status
        showModal.value = false
    }

    function isPC(){
        return window.innerWidth > 960
    }

    function getTagType(status){
        if (status == 'Pending') return 'warning'
        if (status == 'Packed') return 'primary'
        if (status == 'Out for Delivery') return 'info'
        if (status == 'Delivered') return 'success'
        return 'danger'
    }

    function onMarkAsPaid(){
        ElMessageBox.confirm(
            'Are you sure you want to this as paid?',
            'Warning',
            {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning',
            }
        )
            .then(async () => {
                await axios.post(route('order.mark-as-paid', order.id))
                .then(() => {
                    paid.value = 1
                    order.paid = 1
                    ElMessage({
                        type: 'success',
                        message: 'Marked as paid successfully.',
                    })
                })
            })
            .catch(() => {})
    }

    function onSubmit(){
        axios.post(route('order.update-status', order.id), {status: status.value})
            .then(() => {
                order.status = status.value
                showModal.value = false
                    ElMessage({
                        type: 'success',
                        message: 'Updated status successfully.',
                    })
            })
    }

    
</script>