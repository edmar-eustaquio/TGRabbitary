<script setup>
import { useForm, Link } from '@inertiajs/vue3';

    const form = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = () => {
        form.post(route('login'), {
            onFinish: () => form.reset('password'),
        });
    };
</script>

<template>
    <form @submit.prevent="submit">
        <div class="flex h-svh">
            <div 
                :style="`background-image: url(${$page.props.base_img_path}website/2.jpg);`"
                class="w-full min-[800px]:w-[65svw] bg-center bg-cover h-full text-gray-600 overflow-hidden flex justify-center items-center p-4"
            >
                <div class="flex justify-center w-full max-w-[350px] flex-wrap">
                    <div class="text-green-300 mb-4 text-[2.8rem] font-[900]">
                        Sign in
                    </div>
    
                    <input 
                        v-model="form.email"
                        type="text" 
                        class="mt-4 outline-none border-none bg-gray-200 rounded-lg p-4 w-full placeholder-gray-500 text-[1.2rem]"
                        placeholder="EMAIL"
                    >
                    <div v-if="form.errors?.email" class="text-red-600 mt-1">
                        {{form.errors?.email}}
                    </div>
                    <input 
                        v-model="form.password"
                        type="password" 
                        class="mt-4 outline-none border-none bg-gray-200 rounded-lg p-4 w-full placeholder-gray-500 text-[1.2rem]"
                        placeholder="PASSWORD"
                    >
                    <div v-if="form.errors?.password" class="text-red-600 mt-1">{{form.errors?.password}}</div>

                    <button class="mt-5 w-[14rem] font-semibold uppercase border-2 cursor-pointer border-green-300 text-[1.4rem] py-[.6rem] rounded-[4rem] bg-green-300 text-white text-center hover:bg-transparent hover:text-green-300">
                        Sign in
                    </button>
                    
                    <Link class="flex min-[800px]:hidden" :href="route('register')">
                        <div class="mt-5 font-semibold uppercase w-[14rem] border-2 cursor-pointer border-green-300 text-[1.4rem] py-[.6rem] rounded-[4rem] bg-transparent text-green-300 text-center hover:bg-green-300 hover:text-white">
                            Sign up
                        </div>
                    </Link>
                </div>
            </div>
            <div class="hidden min-[800px]:flex flex-1 h-full bg-green-300 text-white justify-center items-center">
                <div class="block">
                    <div class="font-bold text-[1.75rem] w-full text-center">
                        Not a member?
                    </div>
                    <div class="mt-6 text-[1.2rem] w-full text-center">
                        Sign up and start shopping.
                    </div>
                    <Link :href="route('register')">
                        <div class="mt-5 font-semibold uppercase w-[14rem] border-2 cursor-pointer border-white text-[1.4rem] py-[.6rem] rounded-[4rem] bg-transparent text-white text-center hover:bg-white hover:text-green-300">
                            Sign up
                        </div>
                    </Link>
                </div>
            </div>
        </div>

        
        <!-- <div class="flex h-svh bg-slate-300 items-center justify-center">
            <div class="w-[400px] text-gray-800 py-5 px-7 bg-white shadow-lg rounded-md">
                <div class="font-semibold text-[30px] mb-5 text-center">Login</div>
                <div class="uppercase text-sm mb-2">Email</div>
                <input 
                    v-model="form.email"
                    type="text" 
                    class="w-full p-2 rounded-md " 
                    placeholder="Type your email"
                >
                <div v-if="form.errors?.email" class="text-red-600 mt-1">{{form.errors?.email}}</div>

                <div class="uppercase text-sm my-2">Password</div>
                <input 
                    v-model="form.password"
                    type="password" 
                    class="w-full p-2 rounded-md " 
                    placeholder="Type your password"
                >
                <div v-if="form.errors?.password" class="text-red-600 mt-1">{{form.errors?.password}}</div>

                <button class="bg-teal-800 text-white mt-4 py-2 rounded-md w-full hover:bg-teal-600">
                    Login
                </button>

                <Link :href="route('register')">
                    <button class="bg-cyan-700 text-white mt-2 py-2 rounded-md w-full hover:bg-cyan-500">
                        Register
                    </button>
                </Link>
            </div>
        </div> -->
    </form>
</template>
