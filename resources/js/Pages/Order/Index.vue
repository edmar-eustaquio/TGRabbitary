<template>
    <AuthLayout>
        <div class="flex justify-between flex-wrap">
            <div class="text-lg font-semibold">List of Oders</div>
        </div>

        <el-card class="mt-4" shadow="always">
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
                    min-width="130px" 
                    label="Client"
                >
                    <template v-slot="{ row }">
                        {{row.name}}
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
                            content="Edit"
                            placement="top"
                        >
                            <Link class="mr-2" :href="route('order.details.show', row.id)">
                                <el-button
                                    type="primary"
                                    size="small"
                                >
                                    <i class="fas fa-pencil"></i>
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
        </el-card>
    </AuthLayout>

</template>

<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { onBeforeMount, reactive, ref } from 'vue';
import axios from 'axios';
import { Link } from '@inertiajs/vue3';

    const pagination = reactive({
        search: null,
        page_size:10,
        page:1,
    })
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
        axios.get(route('order.get', pagination))
            .then(res => {
                data.value = res.data.data.map(d => {
                    return{
                        id: d.id,
                        name: d.user.name,
                        date: d.created_at.replace('T', ' ').substring(0, 10),
                        paid: d.paid,
                        status: d.status,
                        amount: getTotal(d.details),
                    }
                })
                pagination_total.value = res.data.total

            })
    }
    
</script>