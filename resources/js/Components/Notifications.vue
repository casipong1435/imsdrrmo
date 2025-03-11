<script setup>

import { computed, ref } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';

const auth_user = usePage().props.auth.user;
const notifications = usePage().props.auth.notifications;
const unreadNotifications = usePage().props.auth.unreadNotifications;

let isNotificationsMenuOpen = ref(false);

const toggleNotificationsMenu = () => {
    isNotificationsMenuOpen.value = !isNotificationsMenuOpen.value
};

const closeNotificationsMenu = () => {
    isNotificationsMenuOpen.value = false
};

const viewAll = ref(false);

const itemsToDisplay = computed(() => {
    const allNotificationsArray = notifications ? Object.values(notifications) : [];
    return viewAll.value ? allNotificationsArray : allNotificationsArray.slice(0, 5);
});

dayjs.extend(relativeTime);

// Function to calculate "ago"
function timeAgo(createdAt) {
    return dayjs(createdAt).fromNow();
}

const markAsRead = (id) => {
  router.post(route('markasReadNotification', id),{},{
    preserveState: false
  });
};

const markAsReadAll = () => {
  router.post(route('markasReadAllNotification'),{},{
    preserveState: false
  });
};

</script>

<template>
    <button class="relative align-middle rounded-md focus:outline-none focus:shadow-outline-purple"
        @click="toggleNotificationsMenu" aria-label="Notifications" aria-haspopup="true">
        <svg class="w-6 h-6 text-primary" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
            <path
                d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z">
            </path>
        </svg>
        <!-- Notification badge -->
        <span v-if="unreadNotifications.length > 0"
            class="dot dot-error h-3.5 w-3.5 absolute top-0 right-0 inline-block w-3 h-3 transform translate-x-1 -translate-y-1 text-white flex justify-center items-center"
            style="font-size: 10px;">{{ unreadNotifications.length }}</span>
    </button>
    <div v-if="isNotificationsMenuOpen">
        <ul x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click.self="closeNotificationsMenu"
            class="absolute right-0 overflow-y-auto  p-2 mt-2 space-y-2 text-gray-600 bg-white border border-gray-100 rounded-md shadow-md dark:text-gray-300 dark:border-gray-700 dark:bg-gray-700 mt-7"
            style="width: 350px; height: 450px; z-index: 9999;">
            <div
                class="block py-2 px-4 text-base font-medium text-start text-gray-700 bg-gray-50 dark:bg-gray-600 dark:text-gray-300 flex justify-between">
                <span>Notifications</span>
                <span class="text-blue-600 cursor-pointer" @click="markAsReadAll()">Mark all as read</span>
            </div>
            <div>

                <li v-for="(notif, index) in itemsToDisplay" :key="index"
                    class="flex py-3 px-4 border-b bg-gray-100 dark:hover:bg-gray-600 dark:border-gray-600 text-start relative cursor-pointer" :class="{'bg-transparent': notif.read_at}" @click="markAsRead(notif.id)">
                    <div class="flex-shrink-0">
                        <div class="relative w-11 h-11 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-600">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="absolute w-12 h-12 text-gray-400 -left-1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
                            </svg>

                        </div>

                    </div>
                    <div class="pl-3 w-full">
                        <div class="text-gray-500 font-normal text-sm mb-1.5 dark:text-gray-400">
                            <span class="font-semibold text-gray-900 dark:text-white">{{
                                notif.data.from
                                }}</span> {{ notif.data.message }}
                        </div>
                        <div class="text-xs font-medium text-primary-600 dark:text-primary-500">
                            {{ timeAgo(notif.created_at) }}
                        </div>
                    </div>

                </li>

                <a v-if="itemsToDisplay.length <= 0 || itemsToDisplay.length == null" class="flex py-3 px-4 border-b ">
                    <div class="text-center py-3 px-10">
                        No notifications available
                    </div>
                </a>
            </div>
            <button v-if="itemsToDisplay.length >= 5" @click="viewAll = !viewAll" type="button"
                class="block py-2 text-md font-medium text-center text-gray-900 bg-gray-50 hover:bg-gray-100 dark:bg-gray-600 dark:text-white dark:hover:underline">
                <div class="inline-flex items-center">
                    <svg aria-hidden="true" class="mr-2 w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor"
                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                        <path fill-rule="evenodd"
                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    {{ viewAll ? 'Show Less' : 'Show All' }}
                </div>
            </button>
        </ul>
    </div>
</template>