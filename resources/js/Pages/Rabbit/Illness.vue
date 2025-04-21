<template>
    <AuthLayout>
        <div class="flex justify-between flex-wrap">
            <div class="text-lg font-semibold">Health Monitoring Management</div>

            <!-- <button 
                class="py-2 px-3 rounded-sm bg-green-500 text-white"
                @click="onNew"
            >
                <i class="fas fa-plus"></i>
                Create
            </button> -->
        </div>

        <div class="flex items-center gap-2 flex-wrap mt-2">
            <el-select 
                v-model="pagination.rabbit_id" 
                style="width: 200px;" 
                placeholder="Select rabbit"
                filterable    
            >
                <el-option v-for="val in data.rabbits" :label="val.name" :value="val.id" />
            </el-select>

            <el-select 
                v-model="pagination.search" 
                style="width: 200px;" 
                placeholder="Select illness"
                filterable
            >
                <el-option v-for="val in data.illnesses" :label="val" :value="val" />
            </el-select>

            <!-- <el-input
                v-model="pagination.search"
                style="width: 200px;"
                placeholder="Search here"
                type="text"
                clearable
            /> -->

            <el-button 
                type="primary" 
                @click="fetchData"
            >Search</el-button>
        </div>

        <el-card class="mt-4 overflow-auto max-h-[70svh]" shadow="always">
            <el-table
                :data="data.value"
                row-key="id"
                header-row-class-name="thead-light"
            >
                <el-table-column
                    min-width="120px"
                    label="Illness"
                >
                    <template v-slot="{ row }">
                        {{row.illness}}
                    </template>
                </el-table-column>

                <el-table-column 
                    min-width="130px" 
                    label="Rabbit"
                >
                    <template v-slot="{ row }">
                        {{row.rabbit?.name}}
                    </template>
                </el-table-column>

                <el-table-column 
                    min-width="110px" 
                    label="Diagnose Date"
                >
                    <template v-slot="{ row }">
                        {{formatDate(row.date)}}
                    </template>
                </el-table-column>

                <el-table-column 
                    width="150px" 
                    label="Status" >
                    <template v-slot="{ row }">
                        {{ row.status }}
                    </template>
                </el-table-column>

                <el-table-column 
                    width="150px" 
                    label="Update Status" >
                    <template v-slot="{ row }">
                        <el-select 
                            :value="row.status"
                            placeholder="Select status"
                            @change="(e) => onStatus(row.id, e)"
                        >
                            <el-option 
                                v-for="v in ['Cured', 'Under Treatment']"
                                :value="v" 
                                :label="v" 
                            />
                        </el-select>
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
        :width="isPC() ? '50%' : '80%'" 
        v-model="showModal" 
        title="New Illness"
        :close-on-click-modal="false"
    >
        <div>
            <label for="">Rabbit <span class="text-red-600">*</span></label>
            <el-select class="w-full" v-model="form_data.rabbit_id" placeholder="Select rabbit">
                <el-option v-for="val in data.rabbits" :label="val.name" :value="val.id" />
            </el-select>
            <span
                v-if="form_errors.value.rabbit_id"
                class="text-red-600"
            >{{form_errors.value.rabbit_id[0]}}</span>
        </div>

        <div class="mt-2" >
            <label for="">Illness</label>
            <el-input
                v-model="form_data.illness"
                placeholder="Enter illness"
                type="text"
                clearable
            />
            <span
                v-if="form_errors.value.illness"
                class="text-red-600"
            >{{form_errors.value.illness[0]}}</span>
        </div>

        <div class="mt-2">
            <label for="">Date</label> <br>
            <el-date-picker
                v-model="form_data.date"
                placeholder="Enter date"
                type="date"
                clearable
                style="width: 100%;"
            />
            <span
                v-if="form_errors.value.date"
                class="text-red-600"
            >{{form_errors.value.date[0]}}</span>
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

    const pagination = reactive({
        search: null,
        rabbit_id: null,
        page_size:10,
        page:1,
    })
    const pagination_total = ref(0)
    const showModal = ref(false)
    const data = reactive({value:[], rabbits:[], illnesses:[], status:{}})
    const form_errors = reactive({value:{}})
    const form_data = reactive({
        rabbit_id: null,
        illness: null,
        date: null,
    })

    onBeforeMount(()=>{
        fetchData()
        
        axios.get(route('rabbit.dropdown'))
            .then(res => {
                data.rabbits = res.data
            })
    })

    function formatDate(date){
        if (!date) return null
        return date.substring(0, 10)
    }

    function isPC(){
        return window.innerWidth > 960
    }

    function onNew(){
        form_errors.value ={}
        
        form_data.rabbit_id = null,
        form_data.illness = null,
        form_data.date = null,

        showModal.value = true
    }

    function fetchData(){
        axios.get(route('rabbit.illness.get', pagination))
            .then(res => {
                data.value = res.data.data
                pagination_total.value = res.data.total
            })

        axios.get(route('rabbit.illness.dropdown'))
            .then(res => {
                data.illnesses = res.data
            })
    }

    function onSubmit(){
        axios.post(route('rabbit.illness.store'), form_data)
            .then(() => {
                fetchData()
                showModal.value = false
            })
            .catch(err => {
                form_errors.value = err.response.data.errors
            })
    }

    function onStatus(id, status){
        axios.post(route('rabbit.illness.update-status'), {
            id:id,
            status:status,
        })
            .then(() => {
                fetchData()
            })
            .catch(err => {
                console.log(err);
            })
    }
    
</script>