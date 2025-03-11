<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Layout from '.././Layout/Layout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    username: '',
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

    <Head title="Log in" />
    
    <Layout>
        <div class="flex mt-5 md:mt-20 flex-col items-center sm:justify-center">
            <div class="w-full overflow-hidden px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg dark:bg-gray-700 dark:shadow-white dark:shadow">

                <div class="text-center font-bold text-3xl dark:text-white">Login</div>
                <form @submit.prevent="submit">
                    <div>
                        <InputLabel for="username" value="Username" class="dark:text-white" />

                        <TextInput id="username" type="text" class="mt-1 block w-full " v-model="form.username" required
                            autofocus autocomplete="username" />

                        <InputError class="mt-2 dark:text-black" :message="form.errors.username" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="password" value="Password" class="dark:text-white" />

                        <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password"
                            required autocomplete="current-password" />

                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    

                    <div class="mt-4 block">
                        <label class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember" />
                            <span class="ms-2 text-sm text-gray-600 dark:text-white">Remember me</span>
                        </label>
                    </div>

                    <div class="mt-4 flex items-center justify-end">
                        <Link v-if="canResetPassword" :href="route('password.request')"
                            class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-white">
                        Forgot your password?
                        </Link>

                        <PrimaryButton class="ms-4 btn-primary" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">
                            Log in
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </Layout>

</template>
