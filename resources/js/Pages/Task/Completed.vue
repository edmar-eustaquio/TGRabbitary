<template>
    <AuthLayout>
        <div class="flex justify-between flex-wrap">
            <div class="text-lg font-semibold">Completed Task</div>
        </div>

        <el-card class="mt-4" shadow="always">
            <el-table
                :data="data.value"
                row-key="id"
                header-row-class-name="thead-light"
            >
                <el-table-column 
                    min-width="130px" 
                    label="Title"
                >
                    <template v-slot="{ row }">
                        {{row.title}}
                    </template>
                </el-table-column>

                <el-table-column 
                    min-width="200px" 
                    label="Description"
                >
                    <template v-slot="{ row }">
                        {{row.desc}}
                    </template>
                </el-table-column>

                <el-table-column 
                    min-width="200px" 
                    label="Completion Date"
                >
                    <template v-slot="{ row }">
                        {{row.completed_at.substring(0, 10)}}
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

    const pagination = reactive({
        page_size:10,
        page:1,
    })
    const pagination_total = ref(0)
    const data = reactive({value:[]})

    onBeforeMount(fetchData)

    function fetchData(){
        axios.get(route('task.get-completed', pagination))
            .then(res => {
                data.value = res.data.data
                pagination_total.value = res.data.total
            })
    }
    
</script>