<script setup>
import DashDetails from '@/Components/DashDetails.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Chart, registerables } from 'chart.js';
import { LineChart, BarChart } from 'vue-chart-3';

    const {total_sales, recent_orders, todays_orders, users, ratings, all_budget, todays_budgets, todays_expenses, categories, monthly_income} = 
        defineProps(['total_sales', 'recent_orders', 'todays_orders', 'users', 'ratings', 'all_budget', 'todays_budgets', 'todays_expenses', 'categories', 'monthly_income'])
    
    Chart.register(...registerables);

</script>

<template>
    <AuthLayout>
        <div class="mx-5 max-h-[calc(100svh-var(--nav-height)-100px)] overflow-y-auto pr-1">
            <div class="font-semibold text-[2rem]">
                Dashboard
            </div>
    
            <!-- <el-card shadow="always" class="mt-3 bg-violet-100"> -->
            <!-- <div class="flex gap-2 mt-3 flex-wrap justify-center sm:justify-evenly bg-white rounded-lg shadow-lg p-2"> -->
                <div class="flex gap-2 flex-wrap justify-center sm:justify-start">
                    <div class="font-semibold text-lg w-full">
                        Sales
                    </div>

                    <DashDetails
                        class-name="w-full md:w-[32%] sm:w-[49%]" 
                        icon="fas fa-cart-shopping" 
                        title="Total Sales" 
                        :value="`₱${total_sales}`" 
                        icon-color="red"
                    />
                    <!-- Eto ang format ng field -->
                    <DashDetails
                        class-name="w-full md:w-[32%] sm:w-[49%]" 
                        icon="fas fa-cart-shopping" 
                        title="Recent Orders" 
                        :value="recent_orders" 
                        icon-color="red"
                    />
            
                    <DashDetails
                        class-name="w-full md:w-[32%] sm:w-[49%]" 
                        icon="fas fa-trash" 
                        title="Today's Orders" 
                        :value="todays_orders" 
                        icon-color="green"
                    />
            
                    <DashDetails
                        class-name="w-full md:w-[32%] sm:w-[49%]" 
                        icon="fas fa-trash" 
                        title="Customers" 
                        :value="users" 
                        icon-color="green"
                    />
                    
                    <DashDetails
                        class-name="w-full md:w-[32%] sm:w-[49%]" 
                        icon="fas fa-trash" 
                        title="Reviews" 
                        :value="ratings" 
                        dark="true" 
                        icon-color="green"
                    />
                </div>
            <!-- </el-card> -->

            <el-card shadow="always" class="mt-2">
                <BarChart 
                    class="w-full" 
                    :chartData="{
                        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                        datasets: [
                            {
                                label: 'Monthly Sales',
                                data: monthly_income,
                                backgroundColor: 'cyan',
                            },
                        ],
                    }"
                />
                <!-- backgroundColor: ['teal', 'lime', 'gray', '#FF0000', 'cyan'], -->
            </el-card>
            
            <el-card shadow="always" class="mt-3">
                <div class="flex gap-2 flex-wrap justify-center sm:justify-start">                    
                    <div class="font-semibold text-lg w-full">
                        Budget
                    </div>
                    
                    <DashDetails
                        class-name="w-full md:w-[32%] sm:w-[49%]" 
                        icon="fas fa-trash" 
                        title="Current Overall Budget" 
                        :value="`₱${all_budget}`" 
                        dark="true" 
                        icon-color="green"
                    />
                    
                    <DashDetails
                        class-name="w-full md:w-[32%] sm:w-[49%]" 
                        icon="fas fa-trash" 
                        title="Today's Budget Entries"
                        :value="`₱${todays_budgets}`" 
                        dark="true" 
                        icon-color="green"
                    />
                    
                    <DashDetails
                        class-name="w-full md:w-[32%] sm:w-[49%]" 
                        icon="fas fa-trash" 
                        title="Today's Budget Expenses"
                        :value="`₱${todays_expenses}`" 
                        dark="true" 
                        icon-color="green"
                    />

                    <DashDetails
                        class-name="w-full md:w-[32%] sm:w-[49%]" 
                        v-for="c in categories"
                        :key="c.id"
                        icon="fas fa-trash" 
                        :title="c.name" 
                        :value="`₱${c.bal}`" 
                        dark="true" 
                        icon-color="green"
                    />
                </div>
            </el-card>
        </div>
    </AuthLayout>
</template>
