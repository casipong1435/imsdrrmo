<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const open = ref(false);
const dropdownNavbar = ref(false);

const dropdownToggler = () => {
    dropdownNavbar.value = !dropdownNavbar.value;
};

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <nav id="navbar" class="relative z-10 w-full text-neutral-800 bg-gray-50 shadow">
        <div class="flex flex-col max-w-screen-xl px-8 mx-auto lg:items-center lg:justify-between lg:flex-row ">
            <div class="flex flex-col lg:flex-row items-center space-x-4 xl:space-x-8">
                <div class="w-full flex flex-row items-center justify-between">
                    <div>
                        <img :src="'/images/logo/cdrrmc.png'" class="w-20" alt="CDRRMC Logo" />
                    </div>
                    <button class="rounded-lg lg:hidden focus:outline-none focus:shadow-outline" @click="open = !open">
                        <svg v-if="!open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5" />
                        </svg>

                        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>

                    </button>
                </div>
                <ul v-if="0 == 1" :class="open ? 'flex' : 'hidden lg:flex'"
                    class="w-full h-auto flex flex-col flex-grow lg:items-center pb-4 lg:pb-0 lg:justify-end lg:flex-row origin-top duration-300 xl:space-x-2 space-y-3 lg:space-y-0">
                    <a href="#">Home</a>
                    <a href="#">Home</a>
                    <a href="#">Home</a>
                    <a href="#">Home</a>
                    <a href="#">Home</a>

                    <li class="relative group">
                        <button
                            class="md:px-4 py-2 text-sm bg-transparent rounded-lg text-[#666666] hover:text-gray-900 focus:outline-none focus:shadow-outline flex items-center"
                            @click="dropdownToggler" @blur="dropdownToggler">
                            <span>Products</span>
                            <svg v-if="open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                            </svg>

                            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>

                        </button>
                        <div>
                            <ul v-if="dropdownNavbar"
                                class="flex lg:absolute flex-col max-w-42 py-1 lg:bg-white rounded-md lg:shadow-md pl-2 lg:pl-0">
                                <li>
                                    <a href="#"
                                        class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Exchange</a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Wallet</a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Explorer</a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Charts</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
            <div :class="{ 'flex mb-5': open, 'hidden lg:flex mb-0': !open }">
                <button @click="logout" class="py-2 mt-2 text-red-500 hover:text-red-700">
                    <span class="flex space-x-2 items-center">
                        <span>Logout</span>

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9" />
                        </svg>
                    </span>

                </button>
            </div>
        </div>
    </nav>
</template>