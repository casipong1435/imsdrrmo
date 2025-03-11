<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Layout from '.././Layout/Layout.vue'
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { watch, computed, ref } from 'vue';

defineProps({
    errors: Object
});

const { barangays } = usePage().props;
const searchQuery = ref('');
const isShowPassword = ref(false);

const filtedredBarangay = computed(() => {
    if (searchQuery.value === '') return barangays;
    return barangays.filter(barangay => barangay.barangay_name.toLowerCase().includes(searchQuery.toLowerCase()));
});

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
    date_of_birth: ''
});

const submit = () => {
    form.post(route('register'), {
        onSuccess: (page) => {
            if(page.props.flash.success){
                form.reset('password', 'password_confirmation');
            }else{
                console.log(page.props.flash.error);
            }
        },
    });
};

const togglePassword = () => {
    isShowPassword.value = !isShowPassword.value;
};
</script>

<template>

    <Head title="Register" />

    <Layout>
        <div class="py-10 min-w-full h-full rounded dark:bg-black-900">
            <div class="text-4xl font-bold text-center block md:hidden">
                        Register
                    </div>
            <div class="grid grid-cols-1 md:grid-cols-3 p-5">
                <div class="col-span-1 w-full flex justify-center items-between">
                    
                    <img class="hidden md:block w-46 h-96 " src="/images/logo/cdrrmc.png" alt="CDRRMC.png">
                </div>
                <div class="col-span-2 px-0 md:px-5">
                    <form @submit.prevent="submit">
                        <div class="flex flex-row gap-3 ">
                            <div class="mb-2 grid grid-cols-2 w-full gap-5">
                                <div class="col-span-2 md:col-span-1 flex flex-col">
                                    <InputLabel for="first_name" value="First Name" />

                                    <TextInput id="first_name" type="text" class="mt-1 block min-w-full"
                                        v-model="form.first_name" required autofocus autocomplete="first_name"/>

                                    <InputError class="mt-2" :message="errors.first_name" />
                                </div>
                                <div class="col-span-2 md:col-span-1 flex flex-col">
                                    <InputLabel for="last_name" value="Last Name" />

                                    <TextInput id="last_name" type="text" class="mt-1 block w-full"
                                        v-model="form.last_name" required autofocus autocomplete="last_name" />

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
                                        v-model="form.mobile_number" required autofocus autocomplete="mobile_number" placeholder="9XXXXXXXXX" />
                                        <span class="absolute text-gray-500 left-2" style="font-size: 16px; top: 13px;">+63</span>
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

                                    <select id="barangay" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-gray-900" v-model="form.barangay">
                                        <option value="">Select Barangay</option>
                                        <option :value="barangay.id" v-for="barangay in filtedredBarangay" :key="barangay.id">
                                            {{ barangay.barangay_name }}
                                        </option>
                                    </select>

                                    <InputError class="mt-2" :message="errors.barangay" />
                                </div>
                                <div class="col-span-2 md:col-span-1 flex flex-col">
                                    <InputLabel for="purok" value="Purok/Street" />

                                    <TextInput id="purok" type="text" class="block w-full"
                                        v-model="form.purok" autofocus autocomplete="purok" />

                                    <InputError class="mt-2" :message="errors.purok" />
                                </div>
                                <div class="col-span-2 flex flex-col">
                                    <InputLabel for="username" value="Username" />

                                    <TextInput id="username" type="text" class="block w-full"
                                        v-model="form.username" required autofocus autocomplete="username" />

                                    <InputError class="mt-2" :message="errors.username" />
                                </div>
                                <div class="col-span-2 md:col-span-1 flex flex-col">
                                    <InputLabel for="password" value="Password" />

                                    <TextInput id="password" :type="isShowPassword ? 'text':'password'" class="block w-full"
                                        v-model="form.password" required autofocus autocomplete="password" />

                                    <InputError class="mt-2" :message="errors.password" />
                                </div>
                                <div class="col-span-2 md:col-span-1 flex flex-col">
                                    <InputLabel for="password_confirmation" value="Password Confirmation" />

                                    <TextInput id="password_confirmation" :type="isShowPassword ? 'text':'password'" class="block w-full"
                                        v-model="form.password_confirmation" required autofocus autocomplete="password_confirmation" />

                                    <InputError class="mt-2" :message="errors.password_confirmation" />
                                </div>
                                <div class="col-span-2 md:col-span-1 flex flex-col">
                                    <div class="flex flex-row items-center gap-2 ">
                                        <input type="checkbox" id="show_password" :checked="isShowPassword" @click="togglePassword">
                                        <label for="show_password">Show Password</label>
                                        
                                    </div>
                                </div>
                                <div class="col-span-2 md:col-span-1 flex items-center justify-center ">
                                    <span>Already have an account?</span>
                                    <a :href="route('login')" class="ml-1 text-blue-500 hover:text-blue-800">Sign in</a>
                                </div>
                                <div class="col-span-2">
                                    <button type="submit" class="min-w-full btn btn-primary" :disabled="form.processing"><span class="pe-4">Register</span>
                                        <span v-if="form.processing" class="spinner-dot-intermittent [--spinner-color:var(--gray-8)] spinner-sm"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </Layout>

</template>
