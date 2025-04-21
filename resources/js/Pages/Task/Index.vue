<template>
    <AuthLayout>
        <div class="flex justify-between flex-wrap">
            <div class="text-lg font-semibold">Rabbit Care Task Management</div>

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
                    min-width="130px" 
                    label="Rabbit"
                >
                    <template v-slot="{ row }">
                        {{row.rabbit.name}}
                    </template>
                </el-table-column>

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
                    label="Priority"
                >
                    <template v-slot="{ row }">
                        {{row.priority}}
                    </template>
                </el-table-column>

                <el-table-column 
                    min-width="200px" 
                    label="Due Date"
                >
                    <template v-slot="{ row }">
                        {{row.due_date.substring(0, 10)}}
                    </template>
                </el-table-column>

                <el-table-column 
                    width="155px" 
                    label="ACTION" 
                    fixed="right">
                    <template v-slot="{ row }">
                        <el-tooltip
                            class="box-item"
                            effect="dark"
                            content="Complete Task"
                            placement="top"
                        >
                            <el-button
                                type="success"
                                size="small"
                                @click="onComplete(row.id)"
                            >
                                <i class="fas fa-check"></i>
                            </el-button>
                        </el-tooltip>
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
        :title="(form_data.id ? 'Update' : 'New') + ' Task'"
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

        <div class="pt-2">
            <label for="">Title</label>
            <el-select class="w-full" v-model="form_data.title" placeholder="Select title">
                <el-option v-for="val in ['Feeding', 'Grooming', 'Check Up']" :label="val" :value="val" />
            </el-select>
            <span
                v-if="form_errors.value.title"
                class="text-red-600"
            >{{form_errors.value.title[0]}}</span>
        </div>

        <div class="pt-2">
            <label for="">Description</label>
            <el-input
            v-model="form_data.desc"
            placeholder="Enter description."
            type="text"
            clearable
            autosize
            />
            <span
            v-if="form_errors.value.desc"
            class="text-red-600"
            >{{form_errors.value.desc[0]}}</span>
        </div>

        <div class="pt-2">
            <label for="">Priority <span class="text-red-600">*</span></label>
            <el-select class="w-full" v-model="form_data.priority" placeholder="Select priority">
                <el-option v-for="val in ['Low', 'Medium', 'High']" :label="val" :value="val" />
            </el-select>
            <span
                v-if="form_errors.value.priority"
                class="text-red-600"
            >{{form_errors.value.priority[0]}}</span>
        </div>
            
        <div class="mt-2">
            <label for="">Due Date</label> <br>
            <el-date-picker
                v-model="form_data.due_date"
                placeholder="Enter due date"
                type="date"
                clearable
                style="width: 100%;"
            />
            <span
                v-if="form_errors.value.due_date"
                class="text-red-600"
            >{{form_errors.value.due_date[0]}}</span>
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
import { router } from '@inertiajs/vue3';

    const pagination = reactive({
        page_size:10,
        page:1,
    })
    const pagination_total = ref(0)
    const showModal = ref(false)
    const data = reactive({value:[], rabbits:[]})
    const form_errors = reactive({value:{}})
    const form_data = reactive({
        id:null,
        title:null,
        desc:null,
        priority:null,
        due_date: null,
    })

    onBeforeMount(() => {
        axios.get(route('rabbit.dropdown'))
            .then(res => {
                data.rabbits = res.data
            })

        fetchData()
    })

    function isPC(){
        return window.innerWidth > 960
    }

    function onNew(){
        form_errors.value ={}
        form_data.id = null
        form_data.title = null
        form_data.desc = null
        form_data.priority = null
        form_data.due_date = null

        showModal.value = true
    }

    function onEdit(row){
        form_errors.value ={}
        form_data.id = row.id
        form_data.title = row.title
        form_data.desc = row.desc
        form_data.priority = row.priority
        form_data.due_date = row.due_date

        showModal.value = true
    }

    function fetchData(){
        axios.get(route('task.get', pagination))
            .then(res => {
                data.value = res.data.data
                pagination_total.value = res.data.total
            })
    }

    function onSubmit(){
        const url = form_data.id ? route('task.update', form_data.id) : route('task.store')
        axios.post(url, form_data)
            .then(() => {
                ElMessage.success('Saved a task successfully.')
                // fetchData()
                // showModal.value = false
                router.visit(window.location.href, {preserveScroll: true})
            })
            .catch(err => {
                form_errors.value = err.response.data.errors
            })
    }

    function onComplete(id){
        axios.post(route('task.update-completed', id))
            .then(() => {
                ElMessage.success('Completed a task successfully.')
                // fetchData()
                router.visit(window.location.href, {preserveScroll: true})
            })
            .catch(err => {
                console.log(err);
            })
    }

    function onDelete(id){
        axios.post(route('task.delete', id))
            .then(() => {
                ElMessage.success('Deleted a task successfully.')
                // fetchData()
                router.visit(window.location.href, {preserveScroll: true})
            })
            .catch(err => {
                console.log(err);
            })
    }
    
</script>