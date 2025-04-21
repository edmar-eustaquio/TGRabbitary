<template>
    <AuthLayout>
        <div class="flex justify-between flex-wrap">
            <div class="text-lg font-semibold">Invoice</div>
            <el-button type="primary" @click="print">Print</el-button>
        </div>

        <el-card class="mt-4" shadow="always">
            <div id="print-div">
                <div style="display: flex; justify-content: space-between; gap: 10px; margin-bottom: 5px;">
                    <div style="flex: 1 1 0%;">
                        <div style="font-weight: 700;">Invoice No.: {{ invoice_no }}</div>
                        <div>Order No.: {{ data.no }}</div>
                        <div>Order Date: {{ data.created_at ? data.created_at.replace('T', ' ').substring(0, 10) : '' }}</div>
                        <div>Email Address: {{ data.user.email }}</div>
                    </div>
                    <div style="flex: 1 1 0%;">
                        <div>Full Name: {{ data.user.name }}</div>
                        <div>Adress: {{ data.address }}</div>
                        <div>Phone Number: {{ data.user.phone_no }}</div>
                    </div>
                </div>

                <hr>

                <div style="font-weight: 700; font-size: large; margin-top: 10px;">Ordered Items</div>
            
                <table style="width: 100%; margin-top: 10px; border-collapse: collapse; border: 1px solid #B0AAAA">
                    <thead style="background-color: #EEEBEB;">
                        <th style="padding: 5px;">Product</th>
                        <th style="padding: 5px;">Price</th>
                        <th style="padding: 5px;">Quantity</th>
                        <th style="padding: 5px;">Grandtotal</th>
                    </thead>

                    <tbody>
                        <tr v-for="d in data.details" style="border-bottom: 1px solid #B0AAAA;">
                            <td style="padding: 5px; text-align: center;">{{ d.product.name }}</td>
                            <td style="padding: 5px; text-align: center;">{{ numberWithCommas(d.product.price) }}</td>
                            <td style="padding: 5px; text-align: center;">{{ numberWithCommas(d.qty) }}</td>
                            <td style="padding: 5px; text-align: center;">{{ numberWithCommas(d.grandtotal) }}</td>
                        </tr>

                        <!-- <tr>
                            <td colspan="3" style="text-align: right;">Sub Total</td>
                            <td style="text-align: center;">{{ numberWithCommas(data.details.reduce((total, v) => v.grandtotal + total, 0)) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: right;">Tax Amount</td>
                            <td style="text-align: center;">4,090</td>
                        </tr> -->
                        <tr>
                            <td colspan="3" style="text-align: right; padding: 5px;">Grand Total</td>
                            <td style="text-align: center; padding: 5px;">{{ data.details.reduce((total, v) => v.grandtotal + total, 0) }}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="font-weight: 700; font-size: large; margin-top: 10px;">Payment Status: {{ data.paid === 1 ? 'Paid' : 'Unpaid' }}</div>
                <div style="font-weight: 700; font-size: large; margin-top: 10px;">Payment Mode: {{ data.payment_method }}</div>
            </div>
        </el-card>
    </AuthLayout>

</template>

<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Link } from '@inertiajs/vue3';

    const {data, invoice_no} = defineProps(['data', 'invoice_no'])
    
    data.details.map(d => {
        d.grandtotal = parseInt(d.product.price) * parseInt(d.qty)
    })
    
    const numberWithCommas = (str) => {
        str += ''
        const number = parseFloat(str.replace(/,/g, ''));
        
        return !isNaN(number)
            ? number.toLocaleString()
            : ''
    };

    function print(){
        const tab = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0')
        tab.document.open()
        tab.document.write(`<!DOCTYPE html>
            <html>
                <head>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <title>Print</title>
                    <style>
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
                    <div style="padding:10px; padding-top:20px;">
                        ${document.getElementById('print-div').innerHTML}
                    </div>
                </body>
            </html>`)
            
        tab.print()
        tab.close()
    }

</script>