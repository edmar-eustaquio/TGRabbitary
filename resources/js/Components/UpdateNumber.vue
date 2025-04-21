<template>
    <el-dialog 
        :width="isPC() ? '50%' : '70%'" 
        v-model="showModal" 
        :title="'Update Number'"
        :close-on-click-modal="false">

        <div>
            <label for="">Number</label>
            <el-input
                v-model="form_data"
                placeholder="Enter number"
                type="text"
            />
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
import { ref, watch } from 'vue';

    const setting = usePage().props.setting;

    const props = defineProps({
        onClose: {
            type: Function,
            default: () => {},
        },
    })

    const showModal = ref(true);
    const form_errors = ref(null)
    const form_data = ref(setting.number)

    watch(showModal, (val, _) => {
        if (!val){
            props.onClose()
        }
    })

    function isPC(){
        return window.innerWidth > 960
    }

    function onSubmitUpdate(){
        axios.post(route('setting.update-number'),{
            number:form_data.value,
        })
            .then(()=>{
                router.visit(window.location.href, {preserveScroll: true})
            })
            .catch(function(error){
                form_errors.value = error.response.data.errors
            })
    }
</script>