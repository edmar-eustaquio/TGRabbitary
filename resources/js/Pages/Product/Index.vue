<template>
    <AuthLayout>
        <div class="flex justify-between flex-wrap">
            <div class="text-lg font-semibold">List of Products</div>

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
                    min-width="120px"
                    label="Name"
                >
                    <template v-slot="{ row }">
                        {{row.name}}
                    </template>
                </el-table-column>

                <el-table-column 
                    min-width="130px" 
                    label="Category"
                >
                    <template v-slot="{ row }">
                        {{row.category}}
                    </template>
                </el-table-column>

                <el-table-column 
                    min-width="150px" 
                    label="Description"
                >
                    <template v-slot="{ row }">
                        {{row.desc}}
                    </template>
                </el-table-column>

                <el-table-column 
                    min-width="150px" 
                    label="Price"
                >
                    <template v-slot="{ row }">
                        {{row.price}}
                    </template>
                </el-table-column>

                <el-table-column 
                    min-width="150px" 
                    label="Stock"
                >
                    <template v-slot="{ row }">
                        {{row.stock}}
                    </template>
                </el-table-column>

                <el-table-column 
                    min-width="100px" 
                    label="Status"
                >
                    <template v-slot="{ row }">
                        {{row.active ? 'A' : 'Ina'}}ctive
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
        :title="(form_data.id ? 'Update' : 'New') + ' Product'"
        :close-on-click-modal="false"
    >

        <div>
            <label for="">Category <span class="text-red-600">*</span></label>
            <el-select class="w-full" v-model="form_data.category" filterable placeholder="Select category">
                <!-- <el-option label="Rabbit" value="Rabbit" /> -->
                <el-option label="Food" value="Food" />
                <el-option label="Accesories" value="Accesories" />
                <el-option label="Bundle Package" value="Bundle Package" /> 
            </el-select>
            <span
                v-if="form_errors.value.category"
                class="text-red-600"
            >{{form_errors.value.category[0]}}</span>
        </div>

        <div class="pt-2">
            <label for="">Name <span class="text-red-600">*</span></label>
            <el-input
                v-model="form_data.name"
                placeholder="Enter name"
                type="text"
                clearable
            />
            <span
                v-if="form_errors.value.name"
                class="text-red-600"
            >{{form_errors.value.name[0]}}</span>
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
        
        <div class="flex justify-between mt-2 gap-2">
            <div class="flex-1">
                <label for="">Price</label> <br>
                <el-input
                    v-model="form_data.price"
                    class="w-full"
                    placeholder="Enter price."
                    type="number"
                    clearable
                />
                <span
                    v-if="form_errors.value.price"
                    class="text-red-600"
                >{{form_errors.value.price[0]}}</span>
            </div>

            <div class="w-[50%]">
                <label for="">Stock</label> <br>
                <el-input
                    v-model="form_data.stock"
                    placeholder="Enter stock."
                    type="number"
                    clearable
                />
                <span
                    v-if="form_errors.value.stock"
                    class="text-red-600"
                >{{form_errors.value.stock[0]}}</span>
            </div>
        </div>

        <div class="pt-2">
            <label for="">Status <span class="text-red-600">*</span></label>
            <el-select v-model="form_data.active" placeholder="Select status">
                <el-option :value="1" label="Active" />
                <el-option :value="0" label="Inactive" />
            </el-select>
            <span
                v-if="form_errors.value.active"
                class="text-red-600"
            >{{form_errors.value.active[0]}}</span>
        </div>

        <div class="mt-4">
            <el-upload
                ref="imgUpload"
                action="#"
                :auto-upload="false"
                class="upload-demo"
                :on-change="onChangeImage"
                accept="image/*"
                :on-remove="handleRemove"
                drag
                multiple
                list-type="picture"
            >
                <div class="flex justify-center">

                    <i class="el-icon--upload w-[100px] fas fa-cloud-arrow-up"></i>
                </div>

                <div class="el-upload__text">
                    Drop image here or <em>click to upload</em>
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
    const imgUpload = ref(null)
    const data = reactive({value:[]})
    const form_errors = reactive({value:{}})
    const form_data = reactive({
        id:null,
        name:null,
        category:null,
        desc:null,
        price:null,
        stock:null,
        active:null,
        images:[],
    })

    onBeforeMount(()=>{
        fetchData()
    })

    function isPC(){
        return window.innerWidth > 960
    }

    function onNew(){
        form_errors.value ={}
        form_data.id = null
        form_data.name = null
        form_data.category = null
        form_data.desc = null
        form_data.price = null
        form_data.stock = null
        form_data.active = null
        form_data.images = []
        if (imgUpload.value)
            imgUpload.value.clearFiles();
        showModal.value = true
    }

    function onEdit(row){
        form_errors.value ={}
        form_data.id = row.id
        form_data.name = row.name
        form_data.category = row.category
        form_data.desc = row.desc
        form_data.price = row.price
        form_data.stock = row.stock
        form_data.active = row.active
        form_data.images = []
        if (imgUpload.value)
            imgUpload.value.clearFiles();
        showModal.value = true
    }

    function fetchData(){
        axios.get(route('product.get', pagination))
            .then(res => {
                data.value = res.data.data
                pagination_total.value = res.data.total
            })
    }

    function onChangeImage(img){
        form_data.images.push(img.raw)
    }

    function handleRemove(img){
        form_data.images = form_data.images.filter(i=> i != img.raw)
    }

    function onSubmit(){
        const url = form_data.id ? route('product.update', form_data.id) : route('product.store')
        axios.post(url, form_data,{
            headers: {
                'content-type': 'multipart/form-data'
            }
        })
            .then(() => {
                fetchData()
                showModal.value = false
            })
            .catch(err => {
                form_errors.value = err.response.data.errors
            })
    }

    function onDelete(id){
        axios.post(route('product.delete', id))
            .then(() => {
                fetchData()
            })
            .catch(err => {
                console.log(err);
            })
    }
    
</script>