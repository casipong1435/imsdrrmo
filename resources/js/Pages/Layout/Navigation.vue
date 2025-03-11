<script setup>
import { ref } from "vue";

const open = ref(false);
const dropdownNavbar = ref(false);

const dropdownToggler = () => {
    dropdownNavbar.value = !dropdownNavbar.value;
};
</script>

<template>
    <nav id="navbar" class="relative z-10 w-full text-neutral-800 bg-gray-50 dark:bg-gray-600 shadow">
        <div class="flex flex-col max-w-screen-xl px-8 mx-auto lg:items-center lg:justify-between lg:flex-row ">
            <div class="flex flex-col lg:flex-row items-center space-x-4 xl:space-x-8">
                <div class="w-full flex flex-row items-center justify-between">
                    <div>
                        <img :src="'/images/logo/cdrrmc.png'" class="w-20 dark:bg-gray-100 dark:rounded" alt="CDRRMC Logo" />
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
                
            </div>
            <div :class="{'flex mb-5' : open, 'hidden lg:flex' : !open}" class="space-x-3">
                <a :href="route('login')" class="btn py-2 mt-2 bg-inherit text-gradient hover:text-white hover:bg-gradient-to-r from-[#468ef9] to-[#0c66ee] " :class="{'text-white bg-gradient-to-r from-[#468ef9] to-[#0c66ee] disabled opacity-70 pointer-events-none': $page.component.startsWith('Auth/Login')}">
                    Login
                </a>
                <a :href="route('register')" class="btn py-2 mt-2 bg-inherit text-gradient hover:text-white hover:bg-gradient-to-r from-[#468ef9] to-[#0c66ee] " :class="{'text-white bg-gradient-to-r from-[#468ef9] to-[#0c66ee] disabled opacity-70 pointer-events-none': $page.component.startsWith('Auth/Register')}">
                    Register
                </a>
            </div>
        </div>
    </nav>
</template>