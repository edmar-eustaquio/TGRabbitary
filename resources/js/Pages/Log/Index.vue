<template>
    <AuthLayout>
        <div class="flex justify-between flex-wrap">
            <div class="text-lg font-semibold">Event Logs</div>
        </div>


        <div class="flex gap-2 flex-wrap">
            <div class="flex-1 min-w-36">
                <el-input 
                    class="w-50" 
                    v-model="range.search" 
                    placeholder="Search here" 
                    clearable
                    @keyup.enter="fetchData"
                    @clear="fetchData"
                />
            </div>
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
                        {{ row.date}}
                    </template>
                </el-table-column>

                <el-table-column 
                    min-width="130px" 
                    label="Section"
                >
                    <template v-slot="{ row }">
                        {{row.section}}
                    </template>
                </el-table-column>

                <el-table-column 
                    min-width="200px" 
                    label="Action"
                >
                    <template v-slot="{ row }">
                        {{ row.action }}
                    </template>
                </el-table-column>

                <el-table-column 
                    min-width="200px" 
                    label="Who did?"
                >
                    <template v-slot="{ row }">
                        {{row.user.name}}
                    </template>
                </el-table-column>
            </el-table>

            <div class="flex justify-end pt-2">
                <el-pagination
                    v-model:currentPage="range.page"
                    v-model:page-size="range.page_size"
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
</template>

<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { onBeforeMount, reactive, ref } from 'vue';
import axios from 'axios';

    const today = new Date()
    const range = reactive({
        from: today,
        until: today,
        search: null,
        page_size:10,
        page:1,
    })
    const pagination_total = ref(0)
    const data = reactive({value:[]})

    onBeforeMount(()=>{
        fetchData()
    })

    function get(){
        let v = ''
        data.value.forEach(val => {
            v += `<tr>\
                    <td>${val.date}</td>\
                    <td>${val.section}</td>\
                    <td>${val.action}</td>\
                    <td>${val.user.name}</td>\
                </tr>`
        });

        return v;
    }
    
    function onPrint(){
        const tab = window.open('', '_blank')

        tab.document.open()
        tab.document.write(
            `<!DOCTYPE html>\
            <html>\
                <head>\
                    <meta charset="utf-8">\
                    <meta name="viewport" content="width=device-width, initial-scale=1">\
                    <title>Print</title>\
                    <style>\
                        table{\
                            border-collapse: collapse;\
                        }\
                        table, th, td{\
                            border: 1px solid darkgray;\
                            padding: 5px;\
                        }\
                        td{\
                            text-align: center;\
                        }\
                    </style>\
                </head>\
                <body>\
                    <div style="padding: 10px;">\
                        <div style="font-size: 20px; text-align: center; margin-top: 10px;">TGRabbitary</div>\
                        <div style="font-size: 20px; font-weight: 700; text-align: center;">Logs Report</div>\
                        <div style="font-size: 15px; text-align: center; margin-top: 30px; margin-bottom: 5px;">\
                            Date Between ${range.from.toString().substring(0, 15)} and ${range.until.toString().substring(0, 15)}\
                        </div>\
                        <hr>\
                        <table style="width: 100%;">\
                            <thead>\
                                <th>Date</th>\
                                <th>Section</th>\
                                <th>Action</th>\
                                <th>Who did?</th>\
                            </thead>\
                            <tbody>\
                                ${get()}\
                            </tbody>\
                        </table>\
                    </div>\
                </body>\
            </html>`
        )
        tab.document.close()

        tab.print()
    }

    function fetchData(){
        axios.get(route('log.get', {
            from: (range.from === today ? null : range.from),
            until: (range.until === today ? null : range.until),
            search: range.search,
            page_size: range.page_size,
            page: range.page,
        }))
            .then(res => {
                data.value = res.data.data
                pagination_total.value = res.data.total
            })
    }
    
</script>