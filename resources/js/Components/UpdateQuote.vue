<template>
    <el-dialog 
        :width="isPC() ? '50%' : '70%'" 
        v-model="showModal" 
        :title="'Update Blog Quote'"
        :close-on-click-modal="false">

        <div>
            <label for="">Title</label>
            <el-input
                v-model="form_data.quote_title"
                placeholder="Enter title"
                type="text"
                clearable
            />
            <span
                v-if="form_errors.value.quote_title"
                class="text-red-600"
            >{{form_errors.value.quote_title[0]}}</span>
        </div>

        <div class="pt-2">
            <label for="">Body</label>
            <el-input
                v-model="form_data.quote_body"
                placeholder="Enter body"
                type="text"
                clearable
            />
            <span
                v-if="form_errors.value.quote_body"
                class="text-red-600"
            >{{form_errors.value.quote_body[0]}}</span>
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
                v-if="form_errors.value.quote_image"
                class="text-red-600"
            >{{form_errors.value.quote_image[0]}}</span>
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
                    @click="onSubmitUpdate"
                >Update</el-button>
            </span>
        </template>
    </el-dialog>
</template>

<script setup>
import { usePage, router } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, reactive, watch } from 'vue';

    const setting = usePage().props.setting;

    const props = defineProps({
        onClose: {
            type: Function,
            default: () => {},
        },
    })

    const  showModal = ref(true);
    const form_errors = reactive({value:{}})
    const form_data = reactive({
        quote_title:setting.quote_title,
        quote_body:setting.quote_body,
        quote_image:null,
    })

    watch(showModal, (val, _) => {
        if (!val){
            props.onClose()
        }
    })

    function onChangeImage(img){
        form_data.quote_image = img.raw
    }

    function handleRemove(){
        form_data.quote_image = null
    }

    function isPC(){
        return window.innerWidth > 960
    }

    function onSubmitUpdate(){
        axios.post(route('setting.update-quote'),form_data,{
            headers: {
                'content-type': 'multipart/form-data'
            }
        })
            .then(()=>{
                router.visit(window.location.href, {preserveScroll: true})
            })
            .catch(function(error){
                form_errors.value = error.response.data.errors
            })
    }
</script>