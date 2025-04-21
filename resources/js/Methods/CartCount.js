import axios from 'axios';
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

export const cartCount = ref(0);

export const refreshCartCount = ()=>{
    if (!usePage().props.user) return
     
    axios.get(route('cart.count'))
        .then(res => {
            cartCount.value = res.data
        })
}