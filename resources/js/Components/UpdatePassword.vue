<template>
    
    <el-dialog 
        :width="isPC() ? '50%' : '70%'" 
        v-model="showPasswordModal" 
        :title="'Update password'"
        :close-on-click-modal="false">
        <p class="mb-2 text-sm text-gray-600">
            Ensure your account is using a long, random password to stay secure.
        </p>
        <div>
            <label for="">Current Password<span class="text-red-600">*</span></label>
            <el-input
                v-model="form_pass_data.current_password"
                placeholder="Enter Current Password"
                type="password"
                clearable
            />
            <span
                v-if="form_errors.value?.current_password"
                class="text-red-600"
            >{{form_errors.value.current_password[0]}}</span>
        </div>

        <div class="pt-2">
            <label for="">New Password<span class="text-red-600">*</span></label>
            <el-input
                v-model="form_pass_data.new_password"
                placeholder="Enter New Password."
                type="password"
                clearable
                autosize
            />
            <span
                v-if="form_errors.value.new_password"
                class="text-red-600"
            >{{form_errors.value.new_password[0]}}</span>
        </div>

        <div class="pt-2">
            <label for="">Confirm Password <span class="text-red-600">*</span></label>
            <el-input
                v-model="form_pass_data.confirm_password"
                placeholder="Enter Confirm Password."
                type="password"
                clearable
                autosize
            />
            <span
                v-if="form_errors.value.confirm_password"
                class="text-red-600"
            >{{form_errors.value.confirm_password[0]}}</span>
        </div>

        <template #footer>
            <span class="flex justify-end">
                <el-button 
                    type="warning" 
                    @click="showPasswordModal = false" 
                    plain
                >Cancel</el-button>
                <el-button 
                    type="primary" 
                    @click="onSubmitUpdatePassword"
                >Update</el-button>
            </span>
        </template>
    </el-dialog>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, reactive, watch } from 'vue';

    const props = defineProps({
        onClose: {
            type: Function,
            default: () => {},
        },
    })

    const  showPasswordModal = ref(true);
    const form_errors = reactive({value:{}})
    const form_pass_data = reactive({
        current_password:null,
        new_password:null,
        confirm_password:null,
    })

    watch(showPasswordModal, (val, _) => {
        if (!val){
            props.onClose()
        }
    })

    function isPC(){
        return window.innerWidth > 960
    }

    function onSubmitUpdatePassword(){
        axios.post(route('profile.update-password'),{
            current_password:form_pass_data.current_password,
            new_password:form_pass_data.new_password,
            confirm_password:form_pass_data.confirm_password
        })
        .then(()=>{
            router.post(route('logout'))
        })
        .catch(function(error){
            form_errors.value = error.response.data.errors
        })
    }

</script>