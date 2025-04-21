<script setup>
import SideBarItem from '@/Components/SideBarItem.vue';
import UpdateProfile from '@/Components/UpdateProfile.vue';
import UpdatePassword from '@/Components/UpdatePassword.vue';
import { ref, onMounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import UpdateDeal from '@/Components/UpdateDeal.vue';
import UpdateNumber from '@/Components/UpdateNumber.vue';
import UpdateQuote from '@/Components/UpdateQuote.vue';

    const showNav = ref(window.innerWidth  > 960);
    const  showProfileModal = ref(false);
    const  showNotif = ref(false);
    const  hid = ref(window.innerWidth  < 961);
    const  animate = ref(false);
    const  showPasswordModal = ref(false);
    const  showDealModal = ref(false);
    const  showNumberModal = ref(false);
    const  showQuoteModal = ref(false);

    const tasks = usePage().props.tasks

    window.addEventListener('resize', () => showNav.value =  window.innerWidth > 960)

    onMounted(() => {
        document
            .getElementById('tg-side')
            .addEventListener('animationend', () => {
                hid.value= !hid.value
                animate.value = false
            })
    })

    function isPC (){
        return window.innerWidth > 960
    }

    function closeIfMobile(){
        if (window.innerWidth < 961 && showNav.value){
            animate.value = true
            showNav.value = false
        }
    }

    function onNav(ev){
        animate.value = true
        showNav.value = !showNav.value
        ev.stopPropagation(); 
    }

    function logout(){
        router.post(route('logout'))
    }

    function onDeal(){
        closeIfMobile()
        showDealModal.value = true
    }

    function onNumber(){
        closeIfMobile()
        showNumberModal.value = true
    }

    function onQuote(){
        closeIfMobile()
        showQuoteModal.value = true
    }
</script>

<template>
    <div class="flex">
        <div :id="`tg-side`" :class="`${isPC() ? 'sticky' : 'fixed'} ${animate ? `tg-sidebar${showNav ? '' : '-gone'}` : ''} ${hid ? 'hidden' : 'inline-block'} left-0 w-[var(--sidebar-width)] z-[999] h-svh bg-teal-900 shadow-md text-white`">
            <div class="flex justify-center items-center h-[70px] bg-cyan-700">
                TGRabbitary
            </div>
            <div class="mt-2 mr-1 max-h-[calc(100svh-85px)] overflow-y-auto">
                <SideBarItem 
                    :href="route('dashboard')" 
                    icon="fas fa-gauge" 
                    label="Dashboard" 
                    :closeIfMobile="closeIfMobile" 
                />

                <SideBarItem 
                    :href="route('admin.message.list')" 
                    icon="fas fa-message" 
                    label="Messages" 
                    :closeIfMobile="closeIfMobile" 
                />
                <SideBarItem 
                    :href="route('order.index')" 
                    icon="fas fa-shop" 
                    label="Order List" 
                    :closeIfMobile="closeIfMobile" 
                />
                
                <div class="flex items-center mt-1">
                    <div class="flex-1 border-b border-b-gray-300"></div>
                    <div class="text-gray-300 font-semibold mx-2">
                        Product
                    </div>
                    <div class="flex-1 border-b border-b-gray-300"></div>
                </div>

                <SideBarItem 
                    :href="route('product.index')" 
                    icon="fas fa-cart-shopping" 
                    label="Product List" 
                    :closeIfMobile="closeIfMobile" 
                />
                
                <div class="flex items-center mt-1">
                    <div class="flex-1 border-b border-b-gray-300"></div>
                    <div class="text-gray-300 font-semibold mx-2">
                        Rabbit
                    </div>
                    <div class="flex-1 border-b border-b-gray-300"></div>
                </div>

                <SideBarItem 
                    :href="route('rabbit.breed')" 
                    icon="fas fa-object-group" 
                    label="Rabbit Breed" 
                    :closeIfMobile="closeIfMobile" 
                />

                <SideBarItem 
                    :href="route('rabbit.index')" 
                    icon="fas fa-paw" 
                    label="Rabbit" 
                    :closeIfMobile="closeIfMobile" 
                />

                <SideBarItem 
                    :href="route('rabbit.breeding')" 
                    icon="fas fa-paw" 
                    label="Rabbit Breeding" 
                    :closeIfMobile="closeIfMobile" 
                />

                <SideBarItem 
                    :href="route('rabbit.weight')" 
                    icon="fas fa-weight-scale" 
                    label="Weight Tracker" 
                    :closeIfMobile="closeIfMobile" 
                />

                <SideBarItem 
                    :href="route('rabbit.illness')" 
                    icon="fa-solid fa-disease" 
                    label="Illness History" 
                    :closeIfMobile="closeIfMobile" 
                />
                
                <div class="flex items-center mt-1">
                    <div class="flex-1 border-b border-b-gray-300"></div>
                    <div class="text-gray-300 font-semibold mx-2">
                        Budget
                    </div>
                    <div class="flex-1 border-b border-b-gray-300"></div>
                </div>

                <SideBarItem 
                    :href="route('category.index')" 
                    icon="fas fa-table" 
                    label="Category" 
                    :closeIfMobile="closeIfMobile" 
                />

                <SideBarItem 
                    :href="route('budget.index')" 
                    icon="fas fa-money-check-dollar" 
                    label="Budget List" 
                    :closeIfMobile="closeIfMobile" 
                />
                <SideBarItem 
                    :href="route('budget.expense')" 
                    icon="fas fa-peso-sign" 
                    label="Expense List" 
                    :closeIfMobile="closeIfMobile" 
                />

                <SideBarItem 
                    :href="route('budget.report')" 
                    icon="fas fa-file" 
                    label="Budget Report" 
                    :closeIfMobile="closeIfMobile" 
                />
                <SideBarItem 
                    :href="route('budget.expense-report')" 
                    icon="fas fa-book" 
                    label="Expense Report" 
                    :closeIfMobile="closeIfMobile" 
                />
                
                <div class="flex items-center mt-1">
                    <div class="flex-1 border-b border-b-gray-300"></div>
                    <div class="text-gray-300 font-semibold mx-2">
                        Task
                    </div>
                    <div class="flex-1 border-b border-b-gray-300"></div>
                </div>

                <SideBarItem 
                    :href="route('task.index')" 
                    icon="fas fa-tasks" 
                    label="Task" 
                    :closeIfMobile="closeIfMobile" 
                />

                <SideBarItem 
                    :href="route('task.completed')" 
                    icon="fas fa-check-square" 
                    label="Completed Task" 
                    :closeIfMobile="closeIfMobile" 
                />
                
                <div class="flex items-center mt-1">
                    <div class="flex-1 border-b border-b-gray-300"></div>
                    <div class="text-gray-300 font-semibold mx-2">
                        Blog
                    </div>
                    <div class="flex-1 border-b border-b-gray-300"></div>
                </div>

                <SideBarItem 
                    :href="route('blog.index')" 
                    icon="fas fa-clipboard" 
                    label="Blog" 
                    :closeIfMobile="closeIfMobile" 
                />

                <div class="w-full hover:bg-slate-400 px-5 py-2 cursor-pointer font-semibold text-lg" @click="onQuote">
                    <i class="fas fa-pencil mr-2"></i> Update Blog Quote
                </div>

                <div class="flex items-center mt-1">
                    <div class="flex-1 border-b border-b-gray-300"></div>
                    <div class="text-gray-300 font-semibold mx-2">
                        Settings
                    </div>
                    <div class="flex-1 border-b border-b-gray-300"></div>
                </div>

                <div class="w-full hover:bg-slate-400 px-5 py-2 cursor-pointer font-semibold text-lg" @click="onNumber">
                    <i class="fas fa-phone mr-2"></i> Update Number
                </div>

                <div class="w-full hover:bg-slate-400 px-5 py-2 cursor-pointer font-semibold text-lg" @click="onDeal">
                    <i class="fas fa-square-check mr-2"></i> Update Deal
                </div>

                <SideBarItem 
                    :href="route('log')" 
                    icon="fas fa-note-sticky" 
                    label="Logs" 
                    :closeIfMobile="closeIfMobile" 
                />

            </div>
        </div>

        <div class="flex-1" @click="closeIfMobile">
            <div class="flex pr-4 py-2 pl-2 h-[var(--nav-height)] items-center bg-white shadow-md justify-between">
                <div class="cursor-pointer" @click="onNav">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="menu-icon"
                        viewBox="0 0 24 24"
                        width="24"
                        height="24"
                    >
                        <path
                            fill="currentColor"
                            d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"
                        />
                    </svg>
                </div>

                <div class="flex gap-2 items-center">
                    <div class="relative">
                        <i @click="showNotif = !showNotif" class="fas fa-bell text-lg cursor-pointer"></i>
                        <div 
                            v-if="tasks.length > 0"
                            class="absolute bg-red-600 size-2 top-0 right-[-4px] rounded-full"
                        ></div>
                        <div v-if="showNotif" class="absolute py-4 px-5 shadow-md right-[-20px] min-w-[150px] w-fit mt-4 rounded-md bg-white">
                            <template v-if="tasks.length > 0">
                                <div v-for="task in tasks">
                                    {{ task.title }}
                                    <div class="text-gray-600 text-[14px] mb-2">{{ task.desc }}</div>
                                    <hr>
                                </div>
                            </template>
                            <template v-else>
                                <div class="text-gray-600">No task scheduled yet.</div>
                            </template>
                        </div>
                    </div>

                    <el-dropdown>
                        <span class="el-dropdown-link text-lg px-2">
                            <i class="fas fa-ellipsis-vertical"></i>
                        </span>

                        <template #dropdown>
                            <el-dropdown-menu>
                                <el-dropdown-item 
                                    @click="showProfileModal = true"
                                >
                                    <i class="fas fa-user"></i> 
                                    Update Profile
                                </el-dropdown-item>
                                <el-dropdown-item 
                                    @click="showPasswordModal = true"
                                >
                                    <i class="fas fa-lock"></i>
                                    Update Password
                                </el-dropdown-item>
                                <el-dropdown-item @click="logout" divided>
                                    <i class="fas fa-power-off"></i>
                                    Logout
                                </el-dropdown-item>
                            </el-dropdown-menu>
                        </template>
                    </el-dropdown>
                </div>                
            </div>

            <main :class="`p-4 mt-1 ${isPC() ? 'ml-1 max-w-[calc(100vw-var(--sidebar-width)-.25rem)]' : 'max-w-[calc(100vw-18px)]'} h-[calc(100vh-var(--nav-height))] overflow-auto bg-gray-100`">
                <slot />
            </main>
        </div>
    </div>

    <UpdateProfile v-if="showProfileModal" :on-close="() => showProfileModal = false" />

    <UpdatePassword v-if="showPasswordModal" :on-close="() => showPasswordModal = false" />
        
    <UpdateDeal v-if="showDealModal" :on-close="() => showDealModal = false" />

    <UpdateNumber v-if="showNumberModal" :on-close="() => showNumberModal = false" />

    <UpdateQuote v-if="showQuoteModal" :on-close="() => showQuoteModal = false" />


</template>

<style scoped>
.tg-sidebar{
    animation: slide 500ms;
    width: var(--sidebar-width);
    display: inline-block;
}
.tg-sidebar-gone{
    animation: slide-gone 500ms;
    width: 0px;
}

@keyframes slide{
    from{
        width: 0px;
    }
    to{
        width: var(--sidebar-width);
    }
}
@keyframes slide-gone{
    to{
        width: 0px;
    }
    from{
        width: var(--sidebar-width);
    }
}
</style>
