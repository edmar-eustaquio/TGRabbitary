<template>
    <el-dialog 
        :width="isPC() ? '50%' : '70%'" 
        v-model="showProfileModal" 
        :title="'Update Profile'"
        :close-on-click-modal="false">
        <div>
            <label for="">Name<span class="text-red-600">*</span></label>
            <el-input
                v-model="form_data.name"
                placeholder="Enter Name"
                type="text"
                clearable
            />
            <span
                v-if="form_errors.value.name"
                class="text-red-600"
            >{{form_errors.value.name[0]}}</span>
        </div>

        <div class="pt-2">
            <label for="">Address<span class="text-red-600">*</span></label>
            <el-input
                v-model="form_data.address"
                placeholder="Enter Address."
                type="text"
                clearable
                autosize
            />
            <span
                v-if="form_errors.value.address"
                class="text-red-600"
            >{{form_errors.value.address[0]}}</span>
        </div>

        <div class="pt-2">
            <label for="">Email <span class="text-red-600">*</span></label>
            <el-input
                v-model="form_data.email"
                placeholder="Enter Email."
                type="text"
                clearable
                autosize
            />
            <span
                v-if="form_errors.value.email"
                class="text-red-600"
            >{{form_errors.value.email[0]}}</span>
        </div>

        <div class="pt-2" v-if="$page.props.user.admin == 0">
            <label for="">Phone No. <span class="text-red-600">*</span></label>
            <el-input
                v-model="form_data.phone_no"
                placeholder="Enter phone number"
                type="text"
                clearable
                autosize
            />
            <span
                v-if="form_errors.value.phone_no"
                class="text-red-600"
            >{{form_errors.value.phone_no[0]}}</span>
        </div>

        <template #footer>
            <span class="flex justify-end">
                <el-button 
                    type="warning" 
                    @click="showProfileModal = false" 
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

    const user = usePage().props.user;

    const props = defineProps({
        onClose: {
            type: Function,
            default: () => {},
        },
    })

    const  showProfileModal = ref(true);
    const form_errors = reactive({value:{}})
    const form_data = reactive({
        name:user.name,
        address:user.address,
        email:user.email,
        phone_no:user.phone_no,
    })

    watch(showProfileModal, (val, _) => {
        if (!val){
            props.onClose()
        }
    })

    function isPC(){
        return window.innerWidth > 960
    }

    function onSubmitUpdate(){
        axios.post(route('profile.update'),{
            name:form_data.name,
            address:form_data.address,
            email:form_data.email,
            phone_no:form_data.phone_no
        })
        .then(()=>{
            // window.location.reload()
            router.visit(window.location.href, {preserveScroll: true})
        })
        .catch(function(error){
            form_errors.value = error.response.data.errors
        })
    }
</script>