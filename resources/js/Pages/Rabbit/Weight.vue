<template>
    <AuthLayout>
        <div class="mx-5">
            <div class="flex justify-center flex-wrap">
                <div class="text-xl font-semibold">Weight Tracker</div>
            </div>
    
            <div class="flex justify-center gap-8 items-center flex-wrap">
                <div class="flex flex-wrap justify-center items-center size-[9.5rem] border-4 border-[hotpink] bg-white rounded-full">
                    <div class="text-center">
                        <div class="text-lg font-semibold">{{last_weight}}</div>
                        <div class="text-sm text-gray-400 italic">Current Weight (kg)</div>
                    </div>
                </div>
    
                <div>
                    <el-input
                        v-model="form_data.weight"
                        class="w-full mb-2"
                        placeholder="Enter weight."
                        type="number"
                        clearable
                    />
                    
                    <el-date-picker
                        v-model="form_data.date"
                        placeholder="Select date"
                        type="date"
                        clearable
                        style="width: 100%;"
                    />
                    <span
                        v-if="form_errors.value.date"
                        class="text-red-600"
                    >{{form_errors.value.date[0]}}</span>
                    
                    <el-select 
                        v-model="form_data.rabbit_id" 
                        class="min-w-fit mt-2"
                        placeholder="Select rabbit"
                        filterable
                        @change="fetchData"
                        clearable
                    >
                        <el-option 
                            v-for="rabbit in data.rabbits" 
                            :value="rabbit.id" 
                            :label="rabbit.name" 
                        />
                    </el-select>

                    <div @click="onAddWeight" class="px-3 py-1 rounded-sm bg-teal-600 mt-2 text-white cursor-pointer hover:bg-teal-400 text-center">
                        Add Weight
                    </div>
                </div>
            </div>
    
            <div v-if="form_data.rabbit_id">
                <el-card shadow="always" class="mt-5">
                    <LineChart 
                        class="w-full h-[230px]" 
                        :chartData="{
                            labels: data.dates,
                            datasets: [
                                {
                                    label: 'Weight',
                                    data: data.weights,
                                    backgroundColor: ['hotpink', 'lime', 'teal', 'gray', '#FF0000', 'cyan'],
                                },
                            ],
                        }"
                    />
                </el-card>
    
                <div class="text-lg text-gray-500 mt-5">
                    Weight History
                </div>
    
                <div class="flex justify-between gap-4 mt-2">
                    <div>
                        <div v-for="weight in data.weights" class="text-[16px] text-black my-2">
                            {{ weight }}kg
                        </div>
                    </div>
                    <div>
                        <div v-for="date in data.dates" class="text-gray-500 italic text-[16px] my-2">
                            {{ date }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </AuthLayout>
</template>

<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { onBeforeMount, reactive, ref } from 'vue';
import axios from 'axios';
import { Chart, registerables } from 'chart.js';
import { LineChart } from 'vue-chart-3';

    const data = reactive({dates:[], weights: [], rabbits: []})
    const last_weight = ref(0)
    const form_errors = reactive({value:{}})
    const form_data = reactive({
        rabbit_id:null,
        date:null,
        weight: 0,
    })

    Chart.register(...registerables);

    onBeforeMount(()=>{
        axios.get(route('rabbit.dropdown'))
            .then(res => {
                data.rabbits = res.data
            })
    })

    function fetchData(){
        if (!form_data.rabbit_id){
            data.dates = []
            data.weights = []
            return
        }

        axios.get(route('rabbit.weight.get', form_data.rabbit_id))
            .then(res => {
                data.dates = res.data.dates.map(v => v.replace('T', ' ').substring(0, 10))
                data.weights = res.data.weights
            })
    }

    function onAddWeight(){
        axios.post(route('rabbit.weight.add', form_data))
            .then(res => {
                last_weight.value = form_data.weight
                form_data.weight = 0
                form_data.date = null
                fetchData()
            })
    }

</script>