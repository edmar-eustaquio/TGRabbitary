<template>
    <el-dialog 
        :width="isPC() ? '50%' : '70%'" 
        v-model="showModal" 
        :title="'Update Deal'"
        :close-on-click-modal="false">

        <div>
            <label for="">Description</label>
            <el-input
                v-model="form_data.desc"
                placeholder="Enter description"
                type="textarea"
                clearable
                autosize
            />
        </div>

        <div class="pt-2">
            <label for="">Date</label> <br>
            <el-date-picker
                v-model="form_data.date"
                placeholder="Enter date."
                type="date"
                clearable
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
        desc:setting.deal_desc,
        date:setting.deal_date_until,
    })

    watch(showModal, (val, _) => {
        if (!val){
            props.onClose()
        }
    })

    function isPC(){
        return window.innerWidth > 960
    }

    function onSubmitUpdate(){
        axios.post(route('setting.update-deal'),{
            desc:form_data.desc,
            date:form_data.date,
        })
            .then(()=>{
                router.visit(window.location.href, {preserveScroll: true})
            })
            .catch(function(error){
                form_errors.value = error.response.data.errors
            })
    }
</script>