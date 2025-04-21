<template>
    <AuthLayout>
        <div class="flex justify-between flex-wrap">
            <div class="text-lg font-semibold">Rabbitry Management</div>

            <button 
                class="py-2 px-3 rounded-sm bg-green-500 text-white"
                @click="onNew"
            >
                <i class="fas fa-plus"></i>
                Create
            </button>
        </div>

        <el-card class="mt-4" shadow="always">
            <div class=" pb-2">
                <el-table
                    :data="data.value"
                    row-key="id"
                    header-row-class-name="thead-light"
                >

                    <el-table-column 
                        label="Image"
                        min-width="100px" 
                    >
                        <template v-slot="{ row }">
                            <img 
                                loading="lazy" 
                                class="w-full aspect-[1/1] object-center object-cover transition duration-300 transform hover:scale-110" 
                                :src="$page.props.base_img_path + row.images"
                                alt=""
                            >
                        </template>
                    </el-table-column>


                    <el-table-column 
                        min-width="100px" 
                        label="Name"
                    >
                        <template v-slot="{ row }">
                            {{row.name}}
                        </template>
                    </el-table-column>

                    <el-table-column
                        min-width="100px"
                        label="Breed"
                    >
                        <template v-slot="{ row }">
                            {{row.product.name}}
                        </template>
                    </el-table-column>

                    <el-table-column 
                        width="75px" 
                        label="Gender"
                    >
                        <template v-slot="{ row }">
                            {{row.gender}}
                        </template>
                    </el-table-column>

                    <el-table-column 
                        width="100px" 
                        label="Age"
                    >
                    <template v-slot="{ row }">
                        {{ row.birth ? calculateAge(row.birth) : 'Unknown' }}
                        </template>
                    </el-table-column>

                    <el-table-column 
                        min-width="100px" 
                        label="Weight"
                    >
                        <template v-slot="{ row }">
                            {{row.latest_weight?.weight ?? 0}}kg
                        </template>
                    </el-table-column>

                    <el-table-column 
                        min-width="100px" 
                        label="Disease"
                    >
                        <template v-slot="{ row }">
                            {{row.illness?.illness}}
                            <!-- {{getLastIllness(row.illness)}} -->
                        </template>
                    </el-table-column>

                    <el-table-column 
                        width="55px" 
                        label="Sold"
                    >
                        <template v-slot="{ row }">
                            {{row.sold ==1 ? 'Yes' : 'No'}}
                        </template>
                    </el-table-column>

                    <el-table-column 
                        min-width="100px" 
                        label="Status"
                    >
                        <template v-slot="{ row }">
                            <el-tag :type="getStatusTag(row.status)">
                                {{row.status}}
                            </el-tag>
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
            </div>
        </el-card>
    </AuthLayout>

    <el-dialog 
        :width="isPC() ? '50%' : '80%'" 
        v-model="showModal" 
        :title="(form_data.id ? 'Update' : 'New') + ' Rabbit'"
        :close-on-click-modal="false"
    >

        <div class="pt-2">
            <label for="">Status </label>
            <el-select v-model="form_data.status" placeholder="Select status">
                <el-option value="For Breeding" label="For Breeding" />
                <el-option value="For Bundle" label="For Bundle" />
                <el-option value="For Sale" label="For Sale" />
                <el-option value="For Resting" label="For Resting" />
            </el-select>
            <span
                v-if="form_errors.value.status"
                class="text-red-600"
            >{{form_errors.value.status[0]}}</span>
        </div>

        <div class="mt-2" >
            <label for="">Rabbit Breed </label>
            <el-select 
                v-model="form_data.product_id" 
                placeholder="Select rabbit breed" 
                filterable
            >
                <el-option v-for="v in data.rabbits" :value="v.id" :label="v.name" />
            </el-select>
        </div>

        <div class="mt-2">
            <label for="">Name<span class="text-red-600">*</span> </label>
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

        <div class="mt-2">
            <label for="">Gender </label>
            <el-select 
                v-model="form_data.gender" 
                placeholder="Select gender"
            >
                <el-option value="Buck" label="Buck" />
                <el-option value="Doe" label="Doe" />
                <el-option value="Unknown" label="Unknown" />
            </el-select>
            <span
                v-if="form_errors.value.gender"
                class="text-red-600"
            >{{form_errors.value.gender[0]}}</span>
        </div>

        <div class="w-full mt-2"  >
            <label for="">Birthday</label> <br>
            <el-date-picker
                v-model="form_data.birth"
                placeholder="Select birthday"
                type="date"
                clearable
                style="width: 100%;"
            />
            <span
                v-if="form_errors.value.birth"
                class="text-red-600"
            >{{form_errors.value.birth[0]}}</span>
        </div> 

        <div class="mt-2">
            <label for="">Note</label>
            <el-input
                v-model="form_data.note"
                placeholder="Enter note"
                type="textarea"
                rowspan="2"
                clearable
            />
           
        </div>

        <div class="mt-2 flex gap-2">
            <div class="flex-1">
                <label for="">Illness</label>
                <el-input
                    v-model="form_data.illness"
                    placeholder="Enter illness"
                    type="text"
                    clearable
                />
            </div>
            
            <div class="w-[130px]">
                <label for="">Date</label> <br>
                <el-date-picker
                    v-model="form_data.date"
                    placeholder="Select date"
                    type="date"
                    clearable
                    style="width: 100%;"
                />
            </div>
        </div>

        <!-- <div class="mt-2">
            <label for="">Illness History</label>
            <div class="border px-4 py-2 mt-1">
                <div v-for="item in data.illness" class="flex justify-between gap-2">
                    <div>{{ item.illness }}</div>
                    <div>{{ item.date }}</div>
                </div>
            </div>
        </div> -->

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
                v-if="form_errors.value['images']"
                class="text-red-600"
            >{{form_errors.value['images'][0]}}</span>
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
        page_size:10,
        page:1,
    })
    const imgUpload = ref(null)
    // const illness_form = reactive({
    //     illness: null,
    //     date: null,
    // })
    const pagination_total = ref(0)
    const showModal = ref(false)
    const data = reactive({value:[], rabbits:[], bucks:[], does:[], illness: []})
    const form_errors = reactive({value:{}})
    const form_data = reactive({
        id:null,
        product_id: null,
        name: null,
        gender: null,
        images:null,
        birth:null,
        note: null,
        status: null,
        // illness: '',

        illness: null,
        date: null
    })


    onBeforeMount(()=>{
        fetchData()

        axios.get(route('product.rabbits'))
            .then(res => {
                data.rabbits = res.data
            })
    })

    function isPC(){
        return window.innerWidth > 960
    }

    function onNew(){
        form_errors.value ={}

        form_data.id = null
        form_data.product_id = null
        form_data.name = null
        form_data.gender = null
        form_data.images = null
        form_data.birth = null        
        form_data.note = null
        form_data.status = null

        // form_data.illness = ''

        form_data.illness = null
        form_data.date = null
        // data.illness = []

        if (imgUpload.value)
            imgUpload.value.clearFiles();

        showModal.value = true
    }

    function onEdit(row){
        form_errors.value ={}
        
        form_data.id = row.id
        form_data.product_id = row.product_id
        form_data.name = row.name
        form_data.gender = row.gender
        form_data.birth = row.birth
        form_data.note = row.note
        form_data.status = row.status

        // form_data.illness = row.illness

        form_data.illness = null
        form_data.date = null
        // data.illness = []
        // if (row.illness){
        //     const illnesses = row.illness.split('|')
        //     for (const i in illnesses) {
        //         const col = illnesses[i].split('^')
        //         if (col.length == 2)
        //             data.illness.push({
        //                 illness: col[0],
        //                 date: col[1]
        //             })
        //     }
        // }

        if (imgUpload.value)
            imgUpload.value.clearFiles();
        
        showModal.value = true
    }

    function getStatusTag(status) {
        if (status == 'For Sale') return 'success'
        if (status == 'For Breeding') return 'primary'
        return 'warning'
    }
    
    function onChangeImage(img){
        form_data.images = img.raw
    }

    function handleRemove(){
        form_data.images = null
    }

    function fetchData(){
        axios.get(route('rabbit.get', pagination))
            .then(res => {
                data.value = res.data.data
                pagination_total.value = res.data.total
            })
        axios.get(route('rabbit.get-bucks'))
            .then(res => {
                data.bucks = res.data
            })
        axios.get(route('rabbit.get-does'))
            .then(res => {
                data.does = res.data
            })
    }

    function onSubmit(){
        // if (illness_form.illness && illness_form.date){
        //     form_data.illness = form_data.illness ? `${form_data.illness}|` : ''
        //     form_data.illness += `${illness_form.illness}^${illness_form.date.toString().substring(0, 15)}`
        // }

        const url = form_data.id ? route('rabbit.update', form_data.id) : route('rabbit.store')
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
        axios.post(route('rabbit.delete', id))
            .then(() => {
                fetchData()
            })
            .catch(err => {
                console.log(err);
            })
    }

    function getLastIllness(illness){
        const illnesses = (illness ?? '').split('|')
        let ill = null
        for (const i in illnesses) {
            ill = illnesses[i].split('^')[0]
        }

        return ill
    }

    function calculateAge(birthday) {
        const bday = new Date(birthday)
        const current_date = new Date()
        const timediff = current_date - bday

        const years = Math.floor(timediff / (1000 * 60 * 60 * 24 * 30 * 12));
        if (years > 0) return `${years} year${years > 1 ? 's' : ''} old`

        const months = Math.floor(timediff / (1000 * 60 * 60 * 24 * 30));
        if (months > 0) return `${months} month${months > 1 ? 's' : ''} old`

        const days = Math.floor(timediff / (1000 * 60 * 60 * 24));
        return `${days} day${days > 1 ? 's' : ''} old`

        // const birthdate = new Date(birthday);
        // const currentDate = new Date();
        // console.log(birthday);
        // var age = currentDate.getFullYear() - birthdate.getFullYear();
        // if (
        //     birthdate.getMonth() > currentDate.getMonth() ||
        //     (birthdate.getMonth() === currentDate.getMonth() && birthdate.getDate() > currentDate.getDate())
        // )
        //     age--;
        // if (age > 0) return age + ' years old'

        // console.log(birthdate.getMonth() , currentDate.getMonth());
        // if (currentDate.getMonth() != birthdate.getMonth()){
        //     var month = (11 - birthdate.getMonth()) + currentDate.getMonth()
        //     if (birthdate.getDate() > currentDate.getDate() && currentDate.getFullYear() != birthdate.getFullYear())
        //         month--
        //     console.log(month, 'dslfhjdsf');
        //     if (month > 0) return month + ' months old'
        // }
        // else {
        //     if (currentDate.getFullYear() == birthdate.getFullYear() && currentDate.getDate() < birthdate.getDate()){
        //         return currentDate.getDate() - birthdate.getDate() + ' days old'
        //     }
        //     else 
        //         return (11 - birthdate.getMonth()) + currentDate.getMonth() + ' months old'
        // }

        // const fm = new Date(birthdate.getFullYear(), birthdate.getMonth() + 1, 0)
        // const day = fm.getDate() - birthdate.getDate() + currentDate.getDate()
        // if (day < 0) return null
        // return day + ' days old';
    }

    
</script>

<style>
    .el-table__body-wrapper{
        overflow: auto;
        max-height: 55svh;
    }
</style>