<template>
    <AuthLayout>
        <div class="flex justify-between flex-wrap">
            <div class="text-lg font-semibold">Expense Management</div>

            <button 
                class="py-2 px-3 rounded-sm bg-green-500 text-white"
                @click="onNew"
            >
                <i class="fas fa-plus"></i>
                Create
            </button>
        </div>

        <el-card class="mt-4" shadow="always">
            <el-table
                :data="data.value"
                row-key="id"
                header-row-class-name="thead-light"
            >
                <el-table-column
                    min-width="220px"
                    label="Date"
                >
                    <template v-slot="{ row }">
                        {{ row.created_at ? row.created_at.substring(0, 10) : ''}}
                    </template>
                </el-table-column>

                <el-table-column 
                    min-width="130px" 
                    label="Category"
                >
                    <template v-slot="{ row }">
                        {{row.category.name}}
                    </template>
                </el-table-column>

                <el-table-column 
                    min-width="200px" 
                    label="Amount"
                >
                    <template v-slot="{ row }">
                        {{numberWithCommas(row.amount)}}
                    </template>
                </el-table-column>

                <el-table-column 
                    min-width="200px" 
                    label="Remarks"
                >
                    <template v-slot="{ row }">
                        {{row.remarks}}
                    </template>
                </el-table-column>

                <el-table-column 
                    width="107px" 
                    label="ACTION" 
                    fixed="right">
                    <template v-slot="{ row }">
                        <el-tooltip
                            class="box-item"
                            effect="dark"
                            content="Edit"
                            placement="top"
                        >
                            <el-button
                                type="primary"
                                size="small"
                                @click="onEdit(row)"
                            >
                                <i class="fas fa-pencil"></i>
                            </el-button>
                        </el-tooltip>
                        <el-tooltip
                            class="box-item"
                            effect="dark"
                            content="Delete"
                            placement="top-start"
                        >
                            <el-button
                                type="danger"
                                size="small"
                                @click="onDelete(row.id)"
                            >
                                <i class="fas fa-trash"></i>
                            </el-button>
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

    <el-dialog 
        :width="isPC() ? '50%' : '70%'" 
        v-model="showModal" 
        :title="(form_data.id ? 'Update' : 'New') + ' Expense'"
        :close-on-click-modal="false"
    >
        <div class="pt-2">
            <label for="">Category <span class="text-red-600">*</span></label>
            <el-select :disabled="form_data.id ? true : false" @change="onChangeCat" class="w-full" v-model="form_data.category_id" filterable placeholder="Select category">
                <el-option v-for="cat in data.categories" :label="`${cat.name} [${cat.bal}]`" :value="cat.id" />
            </el-select>
            <span
                v-if="form_errors.value.category_id"
                class="text-red-600"
            >{{form_errors.value.category_id[0]}}</span>
        </div>

        <div class="pt-2">
            <label for="">Amount</label>
            <el-input
                v-model="form_data.amount"
                @keyup="onChangeAmount"
                placeholder="Enter amount."
                type="number"
                clearable
                autosize
            />
            <span
                v-if="form_errors.value.amount"
                class="text-red-600"
            >{{form_errors.value.amount[0]}}</span>
        </div>

        <div class="pt-2">
            <label for="">Remarks</label>
            <el-input
                v-model="form_data.remarks"
                placeholder="Enter remarks."
                type="text"
                clearable
                autosize
            />
            <span
                v-if="form_errors.value.remarks"
                class="text-red-600"
            >{{form_errors.value.remarks[0]}}</span>
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
                >{{form_data.id ? 'Update' : 'Save'}}</el-button>
            </span>
        </template>
    </el-dialog>
</template>

<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { onBeforeMount, reactive, ref } from 'vue';
import axios from 'axios';
import { ElMessage } from 'element-plus';

    const pagination = reactive({
        search: null,
        page_size:10,
        page:1,
    })
    const pagination_total = ref(0)
    const showModal = ref(false)
    const bal = ref(0)
    const data = reactive({value:[], categories: []})
    const form_errors = reactive({value:{}})
    const form_data = reactive({
        id:null,
        category_id:null,
        amount:null,
        remarks:null,
        is_budget:0,
    })

    onBeforeMount(()=>{
        fetchData()
    })

    const numberWithCommas = (str) => {
        const number = parseFloat(str.replace(/,/g, ''));
        
        return !isNaN(number)
            ? number.toLocaleString()
            : ''
    };

    function onChangeCat(v) {
        data.categories.forEach(element => {
            if (element.id === v){
                bal.value = element.bal
            }
        });
    }

    function isPC(){
        return window.innerWidth > 960
    }

    function onNew(){
        form_errors.value ={}
        form_data.id = null
        form_data.category_id = null
        form_data.amount = null
        form_data.remarks = null

        showModal.value = true
    }

    function onEdit(row){
        form_errors.value ={}
        form_data.id = row.id
        form_data.category_id = row.category_id
        form_data.amount = row.amount
        form_data.remarks = row.remarks
        bal.value = data.categories.find(v => v.id == row.category_id).bal

        showModal.value = true
    }

    function fetchData(){
        axios.get(route('budget.get', pagination))
            .then(res => {
                data.value = res.data.data
                pagination_total.value = res.data.total
            })
            
        axios.get(route('category.get-all'))
            .then(res => {
                data.categories = res.data
            })
    }

    function onChangeAmount(){
        if (form_data.amount >  bal.value) {
            form_data.amount = null

            ElMessage({
                message: 'Inputted amount is greater than the current balance',
                type: 'error',
            })
        }
    }

    function onSubmit(){
        const url = form_data.id ? route('budget.update', form_data.id) : route('budget.store')
        axios.post(url, form_data)
            .then(() => {
                fetchData()
                showModal.value = false
            })
            .catch(err => {
                form_errors.value = err.response.data.errors
            })
    }

    function onDelete(id){
        axios.post(route('budget.delete', id))
            .then(() => {
                fetchData()
            })
            .catch(err => {
                console.log(err);
            })
    }
    
</script>

