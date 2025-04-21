<template>
    <AuthLayout>
        <div class="flex justify-between flex-wrap">
            <div class="text-lg font-semibold">{{is_budget ? 'Budget' : 'Expense'}} Report</div>
        </div>


        <div class="flex gap-2 flex-wrap">
            <el-date-picker
                v-model="range.from"
                type="date"
                placeholder="Enter date from"
                :clearable="false"
                @change="fetchData"
            />
            <el-date-picker
                v-model="range.until"
                type="date"
                placeholder="Enter date until"
                :clearable="false"
                @change="fetchData"
            />
            <el-button type="primary" @click="onPrint">
                <i class="fas fa-print mr-2"></i>
                Print
            </el-button>
        </div>

        <el-card class="mt-4" shadow="always">
            <el-table
                :data="data.value"
                row-key="id"
                header-row-class-name="thead-light"
            >
                <el-table-column
                    min-width="220px"
                    label="Date"
                >
                    <template v-slot="{ row }">
                        {{ row.created_at ? row.created_at.substring(0, 10) : ''}}
                    </template>
                </el-table-column>

                <el-table-column 
                    min-width="130px" 
                    label="Category"
                >
                    <template v-slot="{ row }">
                        {{row.category.name}}
                    </template>
                </el-table-column>

                <el-table-column 
                    min-width="200px" 
                    label="Amount"
                >
                    <template v-slot="{ row }">
                        {{numberWithCommas(row.amount)}}
                    </template>
                </el-table-column>

                <el-table-column 
                    min-width="200px" 
                    label="Remarks"
                >
                    <template v-slot="{ row }">
                        {{row.remarks}}
                    </template>
                </el-table-column>
            </el-table>
        </el-card>
    </AuthLayout>
</template>

<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { onBeforeMount, reactive, ref } from 'vue';
import axios from 'axios';

    const {is_budget} = defineProps(['is_budget'])
    

    const today = new Date()

    const range = reactive({
        from: today,
        until: today
    })
    const data = reactive({value:[]})

    onBeforeMount(fetchData)

    const numberWithCommas = (str) => {
        const number = parseFloat(str.replace(/,/g, ''));
        
        return !isNaN(number)
            ? number.toLocaleString()
            : ''
    };

    function get(){
        let v = ''
        let total = 0
        data.value.forEach(val => {
            v += `<tr>\
                    <td>${val.created_at ? val.created_at.replace('T', ' ').substring(0, 19) : ''}</td>\
                    <td>${val.category.name}</td>\
                    <td>${val.amount}</td>\
                    <td>${val.remarks}</td>\
                </tr>`
            total += parseInt(val.amount)
        });

        return {g: v, t: total};
    }
    
    function onPrint(){
        const tab = window.open('', '_blank')

        const  h = get()

        tab.document.open()
        tab.document.write(
            `<!DOCTYPE html>
            <html>
                <head>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <title>Print</title>
                    <style>
                        table{
                            border-collapse: collapse;
                        }
                        table, th, td{
                            border: 1px solid darkgray;
                            padding: 5px;
                        }
                        td{
                            text-align: center;
                        }
                        @media print {
                            @page {
                                size: auto;  
                                margin: 0;   
                            }
                            @page :first {
                                margin-top: 0 !important;
                                margin-bottom: 0 !important;
                                size: auto; /* Reset size for first page */
                            }

                            @page :left {
                                margin-top: 0 !important;
                                margin-bottom: 0 !important;
                                size: auto; /* Reset size for left pages */
                            }

                            @page :right {
                                margin-top: 0 !important;
                                margin-bottom: 0 !important;
                                size: auto; /* Reset size for right pages */
                            }
                        }
                    </style>
                </head>
                <body>
                    <div style="padding: 10px;">
                        <div style="font-size: 20px; text-align: center; margin-top: 10px;">TGRabbitary</div>
                        <div style="font-size: 20px; font-weight: 700; text-align: center;"> ${is_budget ? 'Budget' : 'Expense'} Report </div>
                        <div style="font-size: 15px; text-align: center; margin-top: 30px; margin-bottom: 5px;">
                            Date Between ${range.from.toString().substring(0, 15)} and ${range.until.toString().substring(0, 15)}
                        </div>
                        <hr>
                        <table style="width: 100%;">
                            <thead>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Remarks</th>
                            </thead>
                            <tbody>
                                ${h.g}
                                <tr><td colspan="4" style="font-weight: 600">Total: ${h.t}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </body>
            </html>`
        )
        tab.document.close()

        tab.print()
    }

    function fetchData(){
        axios.get(route('budget.get-range', {
            is_budget: is_budget,
            from: (range.from === today ? null : range.from),
            until: (range.until === today ? null : range.until),
        }))
            .then(res => {
                data.value = res.data
            })
    }
    
</script>


