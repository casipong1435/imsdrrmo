<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Layout from './Layout/Layout.vue'
import { Head, usePage, router, useForm } from '@inertiajs/vue3';
import { computed, ref, onMounted } from 'vue';
import { useToast } from "@/composables/useToast";

const { role, barangays } = usePage().props;
const { showToast } = useToast();

defineProps({
    errors: Object,
    users: Array
});

const searchQuery = ref('');
const searchBarangayQuery = ref('');
const filterRole = ref("");
const isOpenCreateModal = ref(false);
const isShowPassword = ref(false);
const isOpenDeleteModal = ref(false);
const isOpenPasswordModal = ref(false);
const isAdd = ref(false);
const processing = ref(false);

const getName = (first_name, middle_name, last_name) => {
    return first_name + (middle_name ?? " ") + last_name;
};

const userID = ref(null);

onMounted(() => {
    filterRole.value = role ?? null;
});

const filteredUsers = computed(() => {

    if (searchQuery.value === "") return usePage().props.users;

    return usePage().props.users.filter(user => (user.first_name + user.middle_name + user.last_name).toLowerCase().includes(searchQuery.value.toLowerCase()) || (user?.mobile_number?.includes(searchQuery.value.toLowerCase())));
});

const filtedredBarangay = computed(() => {
    if (searchBarangayQuery.value === '') return barangays;
    return barangays.filter(barangay => barangay.barangay_name.toLowerCase().includes(searchBarangayQuery.toLowerCase()));
});

const filterUserRole = () => {
    router.get(route('admin.account'),
        { role: filterRole.value },
        {
            preserveState: false,
            replace: false,
        });
};

const form = useForm({
    first_name: '',
    middle_name: '',
    last_name: '',
    mobile_number: '',
    username: '',
    password: '',
    password_confirmation: '',
    barangay: '',
    purok: '',
    sex: '',
    date_of_birth: '',
    role: ''
});



const togglePassword = () => {
    isShowPassword.value = !isShowPassword.value;
};

const submit = () => {
    if (isAdd.value) {
        form.post(route('admin.addAccount'), {
            onSuccess: (page) => {
                if (page.props.flash.success) {
                    showToast(page.props.flash.success, false);
                    closeModal()
                } else {
                    showToast(page.props.flash.error, true);
                }
            },
        });
    } else {
        form.put(route('admin.editAccount', { id: userID.value }), {
            onSuccess: (page) => {
                if (page.props.flash.success) {
                    showToast(page.props.flash.success, false);
                    closeModal()
                } else {
                    showToast(page.props.flash.error, true);
                }
            },
        });
    }
};

const deleteUser = () => {
    processing.value = true;
    router.delete(route('admin.deleteAccount', { id: userID.value }), {
        onSuccess: (page) => {
            if (page.props.flash.success) {
                isOpenDeleteModal.value = false;
                showToast(page.props.flash.success, false);
                processing.value = false;
            } else {
                processing.value = false;
                showToast(page.props.flash.error, true);
            }
        }
    });
}

function openAddModal() {
    isAdd.value = true;
    isOpenCreateModal.value = true;
}

function editAddModal(user) {
    isAdd.value = false;
    isOpenCreateModal.value = true;
    userID.value = user.id;

    form.first_name = user?.first_name ?? "";
    form.middle_name = user.middle_name ?? "";
    form.last_name = user.last_name ?? "";
    form.mobile_number = user.mobile_number ?? "";
    form.username = user.username ?? "";
    form.barangay = user.barangay_id ?? "";
    form.purok = user.purok ?? "";
    form.sex = user.sex ?? "";
    form.date_of_birth = user.date_of_birth ?? "";

    switch (user.role) {
        case 'team': form.role = 0; break;
        case 'admin': form.role = 1; break;
        case 'resident': form.role = 2; break;
    }
}

function openDeleteModal(id) {
    userID.value = id;
    isOpenDeleteModal.value = true;
    usePage().props.errors = [];
}

function closeModal() {
    isOpenCreateModal.value = false;
    form.reset();
    isShowPassword.value = false;
    usePage().props.errors = [];
}

function closePasswordModal() {
    isOpenPasswordModal.value = false;
    passwordForm.reset();
    isShowPassword.value = false;
    usePage().props.errors = [];
}

function openPasswordModal(id) {
    userID.value = id;
    isOpenPasswordModal.value = true;
}

const passwordForm = useForm({
    password: '',
    password_confirmation: '',
});


const changePassword = () => {
    passwordForm.put(route('admin.changeAccountPassword', { id: userID.value }), {
        onSuccess: (page) => {
            if (page.props.flash.success) {
                showToast(page.props.flash.success, false);
                closePasswordModal();
            } else {
                showToast(page.props.flash.error, true);
            }
        },
    });
};

</script>

<template>

    <Head title="Account" />

    <Layout>
        <div class="px-10 py-5 min-h-screen">
            <div class="mb-2">
                <div class="font-bold mb-4 text-2xl">Account Summary</div>
                <div class="grid grid-cols-4 gap-x-8">
                    <div class="col-span-4 sm:col-span-1 ">
                        <div class="grid grid-cols-3 shadow-md p-4 rounded">
                            <div class="col-span-2 text-center font-bold flex gap-2">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>

                                </span>
                                <span>
                                    Admin
                                </span>
                            </div>
                            <div class="col-span-1 text-center">
                                {{ $page.props.admins }}
                            </div>
                        </div>
                    </div>
                    <div class="col-span-4 sm:col-span-1">
                        <div class="grid grid-cols-3 shadow-md p-4 rounded">
                            <div class="col-span-2 text-center font-bold flex gap-2">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                    </svg>


                                </span>
                                <span>
                                    Team
                                </span>
                            </div>
                            <div class="col-span-1">
                                {{ $page.props.team }}
                            </div>
                        </div>
                    </div>
                    <div class="col-span-4 sm:col-span-1">
                        <div class="grid grid-cols-3 shadow-md p-4 rounded">
                            <div class="col-span-2 text-center font-bold flex gap-2">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                    </svg>
                                </span>
                                <span>
                                    Resident
                                </span>
                            </div>
                            <div class="col-span-1">
                                {{ $page.props.resident }}
                            </div>
                        </div>
                    </div>
                    <div class="col-span-4 sm:col-span-1">
                        <div class="grid grid-cols-3 shadow-md p-4 rounded">
                            <div class="col-span-2 text-center font-bold flex gap-2">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                    </svg>

                                </span>
                                <span>
                                    Total User
                                </span>
                            </div>
                            <div class="col-span-1">
                                {{ $page.props.admins+$page.props.team+$page.props.resident }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-2xl font-bold flex justify-between items-center gap-2 mt-4">

                <span>
                    User Accounts
                </span>

                <button type="button" class="btn rounded-full bg-green-500 text-white hover:bg-green-700"
                    @click="openAddModal()">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                        </svg>


                    </span>
                </button>
            </div>

            <div class="flex justify-end flex-col sm:flex-row gap-5 py-5">
                <div class="relative">
                    <span class="absolute text-gray-500 top-2 left-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>

                    </span>
                    <input type="search"
                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pl-10 min-w-full dark:text-gray-800"
                        placeholder="Search..." v-model="searchQuery">
                </div>
                <select
                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-gray-800"
                    v-model="filterRole" @change="filterUserRole">
                    <option :value="null">All</option>
                    <option value="0">Team</option>
                    <option value="1">Admin</option>
                    <option value="2">Resident</option>
                </select>
            </div>

            <div class="flex w-full overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Date of Birth</th>
                            <th>Sex</th>
                            <th>Mobile Number</th>
                            <th>Barangay</th>
                            <th>Purok</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, index) in filteredUsers" :key="user.id">
                            <th>{{ index + 1 }}</th>
                            <td>{{ getName(user.first_name, user.middle_name, user.last_name ?? "") }}</td>
                            <td>{{ user.date_of_birth ?? "Not Set" }}</td>
                            <td>{{ user.sex ?? "Not Set" }}</td>
                            <td>{{ user.mobile_number ?? "Not Set" }}</td>
                            <td>{{ user.barangay?.barangay_name ?? "Not Set" }}</td>
                            <td>{{ user.purok ?? "Not Set" }}</td>
                            <td>
                                <span class="badge"
                                    :class="{ 'badge-success': user.role == 'admin', 'badge-warning': user.role == 'team' }">
                                    {{ user.role }}
                                </span>
                            </td>
                            <td>
                                <div class="flex gap-3">
                                    <button v-if="user.username !== 'admin'" @click="openDeleteModal(user.id)" type="button" class=" text-error">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>

                                    </button>
                                    <button type="button" class=" text-primary" @click="editAddModal(user)">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>
                                    <button v-if="user.role != $page.props.auth.user.role" type="button"
                                        class="text-warning" @click="openPasswordModal(user.id)">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="filteredUsers.length <= 0">
                            <td colspan="9" class="text-center">
                                <span class="flex items-center justify-center">
                                    No Users Yet!
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!--Add modal-->

        <input class="modal-state" id="create-modal" type="checkbox" v-model="isOpenCreateModal" />
        <div class="modal">
            <label class="modal-overlay"></label>
            <div class="modal-content flex flex-col gap-5">
                <label @click="closeModal()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</label>
                <h2 class="text-xl">{{ isAdd ? 'Create User' : 'Update User' }}</h2>
                <form @submit.prevent="submit">
                    <div class="flex flex-row gap-3 ">
                        <div class="mb-2 grid grid-cols-2 w-full gap-5">
                            <div class="col-span-2 md:col-span-1 flex flex-col">
                                <InputLabel for="role" value="Role" />

                                <select id="role" :disabled="form.username === 'admin'"
                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-gray-800"
                                    v-model="form.role">
                                    <option value="0">Team</option>
                                    <option value="1">Admin</option>
                                    <option value="2">Resident</option>
                                </select>

                                <InputError class="mt-2" :message="errors.role" />
                            </div>

                            <div class="col-span-2 md:col-span-1 flex flex-col">
                                <InputLabel for="first_name" value="First Name" />

                                <TextInput id="first_name" type="text" class="mt-1 block min-w-full"
                                    v-model="form.first_name" required autofocus autocomplete="first_name" />

                                <InputError class="mt-2" :message="errors.first_name" />
                            </div>
                            <div class="col-span-2 md:col-span-1 flex flex-col">
                                <InputLabel for="last_name" value="Last Name" />

                                <TextInput id="last_name" type="text" class="mt-1 block w-full" v-model="form.last_name"
                                    required autofocus autocomplete="last_name" />

                                <InputError class="mt-2" :message="errors.last_name" />
                            </div>
                            <div class="col-span-1 flex flex-col">
                                <InputLabel for="middle_name" value="Middle Name" />

                                <TextInput id="middle_name" type="text" class="mt-1 block w-full"
                                    v-model="form.middle_name" autofocus autocomplete="middle_name" />

                                <InputError class="mt-2" :message="errors.middle_name" />
                            </div>
                            <div class="col-span-1 flex flex-col">
                                <InputLabel for="mobile_number" value="Mobile Number" />

                                <div class="relative">
                                    <TextInput id="mobile_number" type="text" class="mt-1 block w-full pl-10"
                                        v-model="form.mobile_number" required autofocus placeholder="9XXXXXXXXX" />
                                    <span class="absolute text-gray-500 left-2"
                                        style="font-size: 16px; top: 13px;">+63</span>
                                </div>

                                <InputError class="mt-2" :message="errors.mobile_number" />
                            </div>
                            <div class="col-span-1 flex flex-col">
                                <InputLabel for="date_of_birth" value="Date of Birth" />

                                <TextInput id="date_of_birth" type="date" class="mt-1 block w-full"
                                    v-model="form.date_of_birth" required autofocus autocomplete="date_of_birth" />

                                <InputError class="mt-2" :message="errors.date_of_birth" />
                            </div>
                            <div class="col-span-1 flex flex-col">
                                <InputLabel for="last_name" value="Sex" />

                                <div class="flex flex-row items-center gap-2 mt-3">
                                    <input type="radio" value="male" v-model="form.sex" id="male">
                                    <label for="male">Male</label>
                                    <input type="radio" value="female" v-model="form.sex" class="ml-4" id="female">
                                    <label for="female">Female</label>
                                </div>
                                <InputError class="mt-2" :message="errors.sex" />
                            </div>
                            <div class="col-span-2 md:col-span-1 flex flex-col">
                                <InputLabel for="barangay" value="Barangay" />

                                <select id="barangay"
                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-gray-800"
                                    v-model="form.barangay">
                                    <option value="">Select Barangay</option>
                                    <option :value="barangay.id" v-for="barangay in filtedredBarangay"
                                        :key="barangay.id">
                                        {{ barangay.barangay_name }}
                                    </option>
                                </select>

                                <InputError class="mt-2" :message="errors.barangay" />
                            </div>
                            <div class="col-span-2 flex flex-col">
                                <InputLabel for="purok" value="Purok/Street" />

                                <TextInput id="purok" type="text" class="block w-full" v-model="form.purok" autofocus
                                    autocomplete="purok" />

                                <InputError class="mt-2" :message="errors.purok" />
                            </div>
                            <div class="col-span-2 flex flex-col">
                                <InputLabel for="username" value="Username" />

                                <TextInput :disabled="!isAdd" id="username" type="text" class="block w-full"
                                    v-model="form.username" required autofocus />

                                <InputError class="mt-2" :message="errors.username" />
                            </div>
                            <div v-if="isAdd" class="col-span-2 md:col-span-1 flex flex-col">
                                <InputLabel for="password" value="Password" />

                                <TextInput id="password" :type="isShowPassword ? 'text' : 'password'"
                                    class="block w-full" v-model="form.password" required autofocus
                                    autocomplete="password" />

                                <InputError class="mt-2" :message="errors.password" />
                            </div>
                            <div v-if="isAdd" class="col-span-2 md:col-span-1 flex flex-col">
                                <InputLabel for="password_confirmation" value="Password Confirmation" />

                                <TextInput id="password_confirmation" :type="isShowPassword ? 'text' : 'password'"
                                    class="block w-full" v-model="form.password_confirmation" required autofocus
                                    autocomplete="password_confirmation" />

                                <InputError class="mt-2" :message="errors.password_confirmation" />
                            </div>
                            <div v-if="isAdd" class="col-span-2 md:col-span-1 flex flex-col">
                                <div class="flex flex-row items-center gap-2 ">
                                    <input type="checkbox" id="show_password" :checked="isShowPassword"
                                        @click="togglePassword">
                                    <label for="show_password">Show Password</label>

                                </div>
                            </div>

                            <div class="col-span-2 flex gap-2">
                                <button type="submit" class="btn-block btn"
                                    :class="isAdd ? 'btn-success' : 'btn-primary'" :disabled="form.processing">
                                    <span class="">{{ isAdd ? 'Create' : 'Update' }}</span>
                                    <span v-if="form.processing"
                                        class="pl-4 spinner-dot-intermittent [--spinner-color:var(--gray-8)] spinner-sm"></span>
                                </button>
                                <label @click="closeModal()" class="btn btn-block">Cancel</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!--Change Password Modal-->

        <input class="modal-state" id="change-password-modal" type="checkbox" v-model="isOpenPasswordModal" />
        <div class="modal">
            <label class="modal-overlay"></label>
            <div class="modal-content flex flex-col gap-5">
                <label @click="closePasswordModal()"
                    class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</label>
                <h2 class="text-xl">Change Password</h2>
                <form @submit.prevent="changePassword">
                    <div class="flex flex-row gap-3 ">
                        <div class="mb-2 w-full gap-5 flex flex-col">

                            <div class=" flex flex-col">
                                <InputLabel for="password" value="Password" />

                                <TextInput id="password" :type="isShowPassword ? 'text' : 'password'"
                                    class="block w-full" v-model="passwordForm.password" autofocus
                                    autocomplete="password" />

                                <InputError class="mt-2" :message="errors.password" />
                            </div>
                            <div class=" flex flex-col">
                                <InputLabel for="password_confirmation" value="Password Confirmation" />

                                <TextInput id="password_confirmation" :type="isShowPassword ? 'text' : 'password'"
                                    class="block w-full" v-model="passwordForm.password_confirmation" autofocus
                                    autocomplete="password_confirmation" />

                                <InputError class="mt-2" :message="errors.password_confirmation" />
                            </div>
                            <div class=" flex flex-col">
                                <div class="flex flex-row items-center gap-2 ">
                                    <input type="checkbox" id="show_password" :checked="isShowPassword"
                                        @click="togglePassword">
                                    <label for="show_password">Show Password</label>

                                </div>
                            </div>

                            <div class="col-span-2 flex gap-2">
                                <button type="submit" class="btn-primary btn flex gap-2"
                                    :disabled="passwordForm.processing">
                                    <span class="">Update</span>
                                    <span v-if="passwordForm.processing"
                                        class="spinner-dot-intermittent [--spinner-color:var(--gray-8)] spinner-xs"></span>
                                </button>
                                <label @click="closePasswordModal()" class="btn btn-block">Cancel</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <!--Delete modal-->

        <input class="modal-state" id="delete-modal" type="checkbox" v-model="isOpenDeleteModal" />
        <div class="modal">
            <label class="modal-overlay"></label>
            <div class="modal-content flex flex-col gap-5">
                <label for="delete-modal" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</label>
                <h2 class="text-xl">Delete Account</h2>
                <div class="flex flex-col items-center justify-center">
                    <span class="text-error mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-20">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                    </span>
                    <span class="text-xl">Are you sure you want to delete this account?</span>
                </div>
                <div class="flex justify-end gap-3 my-2">
                    <button @click="deleteUser()" type="button" :disabled="processing" class="btn btn-error">
                        <span>Confirm</span>
                        <span v-if="processing"
                            class="pl-4 spinner-dot-intermittent [--spinner-color:var(--gray-8)] spinner-sm"></span>
                    </button>

                    <label class="btn" for="delete-modal">Cancel</label>
                </div>
            </div>
        </div>

    </Layout>
</template>