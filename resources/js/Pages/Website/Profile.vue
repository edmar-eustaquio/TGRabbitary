<template>
    <GuestLayout>
        <ImageBlg
            title="Profile"
            :desc="$page.props.user.name"
            image="Many2.jpg"
        />
        
        <div class="flex justify-end flex-wrap text-lg gap-1 pr-4 pt-3">
            <el-button 
                type="primary"
                @click="showProfileModal = true"
            >Update Profile</el-button>
            <el-button 
                type="info"
                @click="showPasswordModal = true"
            >Update Password</el-button>
            <el-button 
                type="danger"
                @click="logout"
            >Logout</el-button>
        </div>

        <div class="px-4">
            <el-card class="mt-4" shadow="always">
                <template v-if="pagination_total > 0">
                    <el-table
                        :data="data.value"
                        row-key="id"
                        header-row-class-name="thead-light"
                    >
                        <el-table-column
                            min-width="220px"
                            label="Date Order"
                        >
                            <template v-slot="{ row }">
                                {{row.date}}
                            </template>
                        </el-table-column>
        
                        <el-table-column 
                            min-width="200px" 
                            label="Total Amount"
                        >
                            <template v-slot="{ row }">
                                {{row.amount}}
                            </template>
                        </el-table-column>
        
                        <el-table-column 
                            min-width="200px" 
                            label="Paid"
                        >
                            <template v-slot="{ row }">
                                <el-tag v-if="row.paid" type="success">Yes</el-tag>
                                <el-tag v-else type="danger">No</el-tag>
                            </template>
                        </el-table-column>
        
                        <el-table-column 
                            min-width="200px" 
                            label="Status"
                        >
                            <template v-slot="{ row }">
                                <el-tag :type="getTagType(row.status)">{{row.status}}</el-tag>
                            </template>
                        </el-table-column>
        
                        <el-table-column 
                            width="80px" 
                            label="ACTION" 
                            fixed="right">
                            <template v-slot="{ row }">
                                <el-tooltip
                                    class="box-item"
                                    effect="dark"
                                    content="View Details"
                                    placement="right"
                                >
                                    <Link class="mr-2" :href="route('order.details-user', row.id)">
                                        <el-button
                                            type="primary"
                                            size="small"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </el-button>
                                    </Link>
                                </el-tooltip>
                            </template>
                        </el-table-column>
                    </el-table>
        
                    <div class="flex justify-end pt-2">
                        <el-pagination
                            v-model:currentPage="pagination.page"
                            v-model:page-size="pagination.page_size"
                            :page-sizes="[5, 10, 50, 100]"
                            background
                            layout="total, sizes, prev, pager, next"
                            :total="pagination_total"
                            @size-change="fetchData"
                            @current-change="fetchData"
                        />
                    </div>
                </template>
                
                <el-empty v-else description="No transactions yet." />
            </el-card>
        </div>

    </GuestLayout>

    <UpdateProfile v-if="showProfileModal" :on-close="() => showProfileModal = false" />

    <UpdatePassword v-if="showPasswordModal" :on-close="() => showPasswordModal = false" />

</template>

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import axios from 'axios';
import { ref, reactive, onBeforeMount } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import UpdateProfile from '@/Components/UpdateProfile.vue';
import UpdatePassword from '@/Components/UpdatePassword.vue';
import ImageBlg from '@/Components/ImageBlg.vue';

    const pagination = reactive({
        search: null,
        page_size:10,
        page:1,
    })
    const  showProfileModal = ref(false);
    const  showPasswordModal = ref(false);
    const pagination_total = ref(0)
    const data = reactive({value:[]})

    onBeforeMount(()=>{
        fetchData()
    })

    function getTotal(data){
        let total = 0
        for(let i in data){
            const d = data[i]
            total += parseInt(d.qty) * parseInt(d.product.price)
        }

        return total
    }

    function getTagType(status){
        if (status == 'Pending') return 'warning'
        if (status == 'Packed') return 'primary'
        if (status == 'Out for Delivery') return 'info'
        if (status == 'Delivered') return 'success'
        return 'danger'
    }

    function fetchData(){
        axios.get(route('order.get-user', pagination))
            .then(res => {
                console.log(res.data.data);
                data.value = res.data.data.map(d => {
                    return{
                        id: d.id,
                        date: d.created_at.replace('T', ' ').substring(0, 10),
                        paid: d.paid,
                        status: d.status,
                        amount: getTotal(d.details),
                    }
                })
                pagination_total.value = res.data.total
            })
    }

    function logout(){
        router.post(route('logout'))
    }

</script>