<script setup>
import Navigation  from './Navigation.vue';
import Notifications from '@/Components/Notifications.vue';


</script>

<template>
    <div class="flex flex-col flex-1 w-full dark:bg-gray-900 h-screen">
        <Navigation/>
        <main class="px-5 mt-4 h-full overflow-y-auto">
            <slot></slot>
        </main>
    </div>

</template>