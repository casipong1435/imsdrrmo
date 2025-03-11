<script setup>
import Navigation  from './Navigation.vue';
import Toast from '@/Components/Toast.vue';
import Notifications from '@/Components/Notifications.vue';

</script>

<template>
    <div class="flex flex-col flex-1 w-full">
        <Toast/>
        <Navigation/>
        <div class="flex justify-end mt-4 me-5 sm:me-16">
            <Notifications/>
        </div>
        <main class="px-5 mt-4 h-full overflow-y-auto">
            <slot></slot>
        </main>
    </div>

</template>