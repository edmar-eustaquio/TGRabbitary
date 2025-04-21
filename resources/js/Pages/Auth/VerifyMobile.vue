<script setup>
import axios from 'axios';
import { ref } from 'vue';
import {router} from '@inertiajs/vue3'
import { ElMessage } from 'element-plus'

    const code = ref(null)
    const error = ref(null)

    const submit = () => {
        axios.post(route('verify-mobile'), {code: code.value,})
            .then(() => {
                router.reload()
            })
            .catch((err) => {
                code.value= null;
                if (err.response.status === 404){
                    error.value = "Incorrect code"
                    return
                }
                error.value = err.response.data.message
            })
    };
    const resend = () => {
        error.value = null
        code.value = null
        axios.post(route('verification-mobile.resend'))
            .then(() => {
                ElMessage.success('Successfully resend the code.')
            })
            .catch(() => {
                ElMessage.error('Error sending code')
            })
    };
</script>

<template>
    <div class="flex h-svh bg-slate-300 items-center justify-center">
        <div class="w-[400px] text-gray-800 py-5 px-7 bg-white shadow-lg rounded-md">
            <div class="font-semibold text-[30px] mb-5 text-center">Verify Mobile</div>
            <div class="uppercase text-sm mb-2">Code</div>
            <input 
                v-model="code"
                type="number" 
                @keyup.enter="submit"
                class="w-full p-2 rounded-md " 
                placeholder="Type your code"
            >
            <div v-if="error" class="text-red-600 mt-1">{{error}}</div>

            <button @click="submit" class="bg-teal-800 text-white mt-4 py-2 rounded-md w-full hover:bg-teal-600">
                Verify
            </button>
            <button @click="resend" class="bg-cyan-600 text-white mt-2 py-2 rounded-md w-full hover:bg-cyan-500">
                Resend Code
            </button>
        </div>
    </div>
</template>
