<script setup>
import { ref, reactive, onBeforeMount, onBeforeUnmount, onMounted } from 'vue';
import NavItem from '@/Components/NavItem.vue';
import MNavItem from '@/Components/MNavItem.vue';
import { usePage } from '@inertiajs/vue3';
import {refreshCartCount, cartCount} from '../Methods/CartCount.js'
import { Link } from '@inertiajs/vue3';

    const deal_date_until = usePage().props.setting.deal_date_until
    
    const date = reactive({
        days: 0,
        hours: 0,
        minutes: 0,
        seconds: 0,
    })
    const showNav = ref(window.innerWidth  > 960);
    const windowWidth = ref(window.innerWidth);
    window.addEventListener('resize', () => {
        showNav.value =  window.innerWidth > 960
        windowWidth.value= window.innerWidth;
    })
    const targetDate = new Date(deal_date_until);

    let interval = null

    onBeforeMount(() => {
        refreshCartCount()
        if (deal_date_until)
            interval = setInterval(calculateTimeDifference, 1000)
    })

    onBeforeUnmount(() => {
        if (interval)
            clearInterval(interval)
    })

    onMounted(()=>{
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0

            document.querySelector('#navbar-top').style.backgroundColor = scrollTop > 20 
                ? (window.innerWidth > 960 ? 'white' : 'black')
                : 'transparent';  

            if (window.innerWidth < 961) return

            const n = document.getElementsByClassName('nav-item')
            for (const h in n){
                if (n[h].tagName)
                    n[h].style.color = scrollTop > 20 ? 'black' : 'white';  
            }
        });
    })

    function calculateTimeDifference() {
        const difference = targetDate - new Date();
        
        date.days = Math.floor(difference / (1000 * 60 * 60 * 24));
        date.hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        date.minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
        date.seconds = Math.floor((difference % (1000 * 60)) / 1000);
    }

</script>

<template>
    <!-- <div :class="`bg-lime-600 text-gray-100 text-[13px] pb-1 px-4 ${windowWidth < 770 ? '' : 'flex gap-4 justify-evenly'}`">
        <div class="pt-1">
            <i class="fas fa-phone mr-1"></i>
            0995 885 3635
        </div>
        <div class="pt-1">
            <i class="fas fa-paper-plane mr-1"></i>
            TGRABBITRY@GMAIL.COM
        </div>
        <div class="pt-1">3-5 BUSINESS DAYS DELIVERY & FREE RETURNS</div>
    </div> -->

    <!-- <div :class="`sticky top-0 left-0 w-svw h-[var(--nav-height)] z-[999] flex items-center flex-wrap shadow-sm justify-between ${windowWidth < 961 ? 'px-4 bg-black text-gray-50' : 'px-10 bg-white text-gray-700'}`">
        <div class="text-[20px] text-lime-600 font-[900] uppercase">
            TGRabbitary
        </div>

        <div v-if="windowWidth > 960" class="flex justify-between gap-2 h-full items-center">
            <NavItem
                :href="route('home')"
                label="Home"
            />
            <NavItem
                :href="route('shop')"
                label="Shop"
            />
            <NavItem
                :href="route('cart.index')"
                label="Cart"
            >
                <el-badge v-if="cartCount" :value="cartCount" class="item">
                    <i class="fas fa-cart-shopping"></i>
                </el-badge>
                <i v-else class="fas fa-cart-shopping"></i>
            </NavItem>
            <NavItem
                v-if="$page.props.user"
                :href="route('message')"
                label="Messages"
            />
            <NavItem
                :href="route('blog')"
                label="Blog"
            />
            <NavItem
                :href="route('about')"
                label="About"
            />
            <NavItem
                :href="route('profile')"
                label="Profile"
            />
        </div>
        <div 
            v-else 
            class=" cursor-pointer pr-2" 
            @click="() => showNav = !showNav"
        >
            <i class="fas fa-bars"></i> MENU
        </div>
    </div> -->
    
    <div id="navbar-top" :class="`fixed top-0 left-0 w-svw h-[var(--nav-height)] z-[999] flex items-center flex-wrap  justify-between  bg-transparent ${windowWidth < 961 ? 'px-4 text-gray-50' : 'px-10 text-gray-700'}`">
        <Link :href="route('home')">
            <img 
                :src="`${$page.props.base_img_path}website/logo.jpeg`" 
                class="size-[45px] rounded-full"
                alt=""
            >
        </Link>
        <div v-if="windowWidth > 960" class="flex bg-transparent justify-between gap-2 h-full items-center">
            <NavItem
                :href="route('home')"
                label="Home"
            />
            <NavItem
                :href="route('shop')"
                label="Shop"
            />
            <NavItem
                :href="route('cart.index')"
                label="Cart"
            >
                <el-badge v-if="cartCount" :value="cartCount" class="item">
                    <i class="fas fa-cart-shopping"></i>
                </el-badge>
                <i v-else class="fas fa-cart-shopping"></i>
            </NavItem>
            <NavItem
                v-if="$page.props.user"
                :href="route('message')"
                label="Messages"
            />
            <NavItem
                :href="route('blog')"
                label="Blog"
            />
            <NavItem
                :href="route('about')"
                label="About"
            />
            <NavItem
                :href="route('profile')"
                label="Profile"
            />
        </div>
        
        <div 
            v-else 
            class=" cursor-pointer pr-2" 
            @click="() => showNav = !showNav"
        >
            <i class="fas fa-bars"></i> MENU
        </div>  
    </div>

    <div 
        v-if="windowWidth < 961 && showNav"
        class="sticky top-[var(--nav-height)] left-0 w-svw z-[999] bg-black text-white"
        >
        <MNavItem
            :href="route('home')"
            label="Home"
        />
        <MNavItem
            :href="route('shop')"
            label="Shop"
        />
        <MNavItem
            :href="route('cart.index')"
            label="Cart"
        >
                <el-badge v-if="cartCount" :value="cartCount" class="item">
                    <i class="fas fa-cart-shopping"></i>
                </el-badge>
                <i v-else class="fas fa-cart-shopping"></i>
        </MNavItem>
        <MNavItem
            v-if="$page.props.user"
            :href="route('message')"
            label="Messages"
        />
       
        <MNavItem
            :href="route('blog')"
            label="Blog"
        />
        <MNavItem
            :href="route('about')"
            label="About"
        />
        <MNavItem
            :href="route('profile')"
            label="Profile"
        />
    </div>
    <div class="bg-[#f8f9fa]">
        <slot/>

        <div style="background-image: url('/images/website/bg_3.jpg');" class="flex justify-end items-center w-full bg-cover bg-center h-[550px] mt-16">
            <div class="w-[90%] md:w-[50%]">
                <div v-html="$page.props.setting.deal_desc"></div>
                <div class="flex gap-2 mt-12">
                    <div>
                        <div class="text-lime-600 text-[37px]">
                            {{date.days}}
                        </div>
                        <div class="text-[13px]">DAYS</div>
                    </div>
                    <div>
                        <div class="text-lime-600 text-[37px]">
                            {{date.hours}}
                        </div>
                        <div class="text-[13px]">HOURS</div>
                    </div>
                    <div>
                        <div class="text-lime-600 text-[37px]">
                            {{date.minutes}}
                        </div>
                        <div class="text-[13px]">MINUTES</div>
                    </div>
                    <div>
                        <div class="text-lime-600 text-[37px]">
                            {{date.seconds}}
                        </div>
                        <div class="text-[13px]">SECONDS</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center px-5 md:px-[20rem] mt-7 mb-2">
            <div class="text-lime-700 italic text-lg">
                Testimony
            </div>
    
            <div class="font-semibold text-[50px]">
                Our satisfied customer says
            </div>
            <div class="text-gray-600">
                Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in
            </div>
        </div>
    
       

        <!-- <div class="flex flex-wrap justify-between gap-2 px-5 md:px-16 pb-4">
            <div class="flex justify-center relative w-full mb-12">
                <a href="#" class="absolute w-[60px] top-[-30px] h-[60px] bg-lime-600 rounded-full flex justify-center items-center">
                    <i class="fas fa-arrow-up text-white text-[18px] animate-bounce"></i>
                </a>
            </div>

            <div class="w-full md:w-[23%]">
                <div class="font-semibold text-lg">TGRABBITRY</div>
                <div class="mt-2">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</div>
            </div>
            <div class="w-full md:w-[23%]">
                <div class="font-semibold text-lg">Menu</div>
                <div class="mt-2">Shop</div>
                <div class="mt-2">About</div>
                <div class="mt-2">Journal</div>
                <div class="mt-2">Contact Us</div>
            </div>
            <div class="w-full md:w-[23%]">
                <div class="font-semibold text-lg">Help</div>
                <div class="mt-2">Shipping Information</div>
                <div class="mt-2">Return & Exchange</div>
                <div class="mt-2">Terms & Conditions</div>
                <div class="mt-2">Privacy Policy</div>
            </div>
            <div class="w-full md:w-[23%]">
                <div class="font-semibold text-lg">Have a Question?</div>
                <div class="mt-2"><i class="fas fa-location-dot"></i> Roxas, San Mariano, Orkidyas Street.</div>
                <div class="mt-2"><i class="fas fa-phone"></i> 0995 885 3635</div>
                <div class="mt-2"><i class="fas fa-envelope"></i> TGRabbitry@gmail.com</div>
            </div>
        </div> -->
    </div>

</template>
