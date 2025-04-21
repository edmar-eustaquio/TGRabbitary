<template>
    <AuthLayout>
        <div class="flex justify-between flex-wrap">
            <div class="text-lg font-semibold">Rabbit Breeding</div>

            <button 
                class="py-2 px-3 rounded-sm bg-green-500 text-white"
                @click="onNew"
            >
                <i class="fas fa-plus"></i>
                Create
            </button>
        </div>

        <el-card class="mt-4 overflow-auto max-h-[70svh]" shadow="always">
            <el-table
                :data="data.value"
                row-key="id"
                header-row-class-name="thead-light"
            >
                <el-table-column
                    min-width="120px"
                    label="Buck"
                >
                    <template v-slot="{ row }">
                        {{row.buck?.name}}
                    </template>
                </el-table-column>

                <el-table-column 
                    min-width="130px" 
                    label="Doe"
                >
                    <template v-slot="{ row }">
                        {{row.doe?.name}}
                    </template>
                </el-table-column>

                <el-table-column 
                    min-width="110px" 
                    label="Date Bred"
                >
                    <template v-slot="{ row }">
                        {{formatDate(row.bred)}}
                    </template>
                </el-table-column>

                <el-table-column 
                    min-width="115px" 
                    label="Date Tested"
                >
                    <template v-slot="{ row }">
                        {{formatDate(row.tested)}}
                    </template>
                </el-table-column>

                <el-table-column 
                    min-width="150px" 
                    label="Fall Out Count"
                >
                    <template v-slot="{ row }">
                        {{row.out_count}}
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
        :width="isPC() ? '50%' : '80%'" 
        v-model="showModal" 
        :title="(form_data.id ? 'Update' : 'New') + ' Breeding'"
        :close-on-click-modal="false"
    >
        
        <div class="grid grid-cols-2 gap-4 mt-2">
            <div>
                <label for="">Buck</label>
                <el-select 
                    v-model="form_data.buck_id" 
                    placeholder="Select buck"
                    clearable
                    filterable
                >
                    <template v-for="v in data.bucks">
                        <el-option 
                            v-if="!form_data.id || form_data.id != v.id" 
                            :value="v.id" 
                            :label="v.name" 
                        />
                    </template>
                </el-select>
                <span
                    v-if="form_errors.value.buck_id"
                    class="text-red-600"
                >{{form_errors.value.buck_id[0]}}</span>
            </div>
    
            <div>
                <label for="">Doe</label>
                <el-select 
                    v-model="form_data.doe_id" 
                    placeholder="Select doe"
                    clearable
                    filterable
                >
                    <template v-for="v in data.does">
                        <el-option 
                            v-if="!form_data.id || form_data.id != v.id" 
                            :value="v.id" 
                            :label="v.name" 
                        />
                    </template>
                </el-select>
                <span
                    v-if="form_errors.value.doe_id"
                    class="text-red-600"
                >{{form_errors.value.doe_id[0]}}</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-2"  >
            <div>
                <label for="">Date Breed </label> <br>
                <el-date-picker
                    v-model="form_data.bred"
                    placeholder="Enter bred"
                    type="date"
                    clearable
                    style="width: 100%;"
                />
            </div>

            <div >
                <label for="">Date Tested </label> <br>
                <el-date-picker
                    v-model="form_data.tested"
                    placeholder="Enter date tested"
                    type="date"
                    clearable
                    style="width: 100%;"
                />
            </div>
        </div>

        <div class="w-full mt-2"  >
            <label for="">Expected Kindle Date </label> <br>
            <el-date-picker
                v-model="form_data.expected_kindle"
                placeholder="Enter expected kindle date"
                type="date"
                clearable
                style="width: 100%;"
            />
            <span
                v-if="form_errors.value.expected_kindle"
                class="text-red-600"
            >{{form_errors.value.expected_kindle[0]}}</span>
        </div> 
        
        <div class="grid grid-cols-2 gap-4 mt-2" >
            <div>
                <label for="">Fall Out Count </label>
                <el-input
                    v-model="form_data.out_count"
                    placeholder="Enter fall out count"
                    type="text"
                    clearable
                />
            </div>

            <div >
                <label for="">Breeding Duration </label>
                <el-input
                    v-model="form_data.breed_duration"
                    placeholder="Enter breeding duration"
                    type="text"
                    clearable
                />
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-2" >
            <div>
                <label for="">Kindled Date</label> <br>
                <el-date-picker
                    v-model="form_data.kindled"
                    class=""
                    placeholder="Enter kindled date"
                    type="date"
                    clearable
                    style="width: 100%;"
                />
            </div>
            
            <div >
                <label for="">Weaning Date</label> <br>
                <el-date-picker
                    v-model="form_data.weaning"
                    class=""
                    placeholder="Enter weaning date"
                    type="date"
                    clearable
                    style="width: 100%;"
                />
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-2" >
            <div>
                <label for="">Born Alive </label>
                <el-input
                    v-model="form_data.alive"
                    placeholder="Enter born alive"
                    type="text"
                    clearable
                />
            </div>

            <div >
                <label for="">Born Dead </label>
                <el-input
                    v-model="form_data.dead"
                    placeholder="Enter born dead"
                    type="text"
                    clearable
                />
            </div>
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
import { onBeforeMount, reactive, ref, computed } from 'vue';
import axios from 'axios';

    const pagination = reactive({
        search: null,
        page_size:10,
        page:1,
    })
    const pagination_total = ref(0)
    const showModal = ref(false)
    const data = reactive({value:[], bucks:[], does:[]})
    const form_errors = reactive({value:{}})
    const form_data = reactive({
        id:null,
        bred: null,
        breed_duration: null,
        buck_id: null,
        doe_id: null,
        tested: null,
        out_count: null,
        kindled: null,
        weaning: null,
        alive: null,
        dead: null,
        expected_kindle: null,
    })

    onBeforeMount(()=>{
        fetchData()
        
        axios.get(route('rabbit.get-bucks'))
            .then(res => {
                data.bucks = res.data
            })
        axios.get(route('rabbit.get-does'))
            .then(res => {
                data.does = res.data
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
        
        form_data.id = null,
        form_data.bred = null,
        form_data.breed_duration = null,
        form_data.expected_kindle = null,
        form_data.buck_id = null,
        form_data.doe_id = null,
        form_data.tested = null,
        form_data.out_count = null,
        form_data.kindled = null,
        form_data.weaning = null,
        form_data.alive = null,
        form_data.dead = null,

        showModal.value = true
    }

    function onEdit(row){
        form_errors.value ={}

        form_data.id = row.id
        form_data.bred = row.bred,
        form_data.breed_duration = row.breed_duration,
        form_data.buck_id = row.buck_id,
        form_data.doe_id = row.doe_id,
        form_data.tested = row.tested,
        form_data.out_count = row.out_count,
        form_data.kindled = row.kindled,
        form_data.expected_kindle = row.expected_kindle,
        form_data.weaning = row.weaning,
        form_data.alive = row.alive,
        form_data.dead = row.dead,

        showModal.value = true
    }

    function fetchData(){
        axios.get(route('rabbit.breeding.get', pagination))
            .then(res => {
                data.value = res.data.data
                pagination_total.value = res.data.total
            })
    }

    function onSubmit(){
        const url = form_data.id ? route('rabbit.breeding.update', form_data.id) : route('rabbit.breeding.store')
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
        axios.post(route('rabbit.breeding.delete', id))
            .then(() => {
                fetchData()
            })
            .catch(err => {
                console.log(err);
            })
    }
    
</script>