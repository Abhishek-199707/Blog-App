<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Layout from '@/Layouts/Layout.vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

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
    <Layout>
    <div class="flex justify-center items-center h-[75vh] bg-gray-100">
        <div class="w-full max-w-md">
            <Head title="Log in" />

            <div v-if="status" class="status-message flex justify-center items-center mb-4">
                {{ status }}
            </div>

            <div class="flex justify-center p-4">
                <img src="../../../../public/storage/assets/logo.png" width="100em" alt="">
            </div>

            <form @submit.prevent="submit" class="bg-white p-6 rounded-lg shadow-lg">
                <div>
                    <InputLabel for="email" value="Email" />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Password" />

                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                    />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="block mt-4">
                    <label class="checkbox-label">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        <span class="ms-2">Remember me</span>
                    </label>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="forgot-password"
                    >
                        Forgot your password?
                    </Link>

                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Log in
                    </PrimaryButton>

                    <!-- Register Button -->
                    <Link :href="route('register')" class="register-button ms-4">
                        Register
                    </Link>
                </div>
            </form>
        </div>
    </div>
</Layout>
</template>

<style scoped>
.status-message {
    color: #059669; /* Success message color */
}

.forgot-password {
    font-size: 0.875rem;
    color: #6b7280;
    text-decoration: underline;
    transition: color 0.3s ease;
}

.forgot-password:hover {
    color: #1f2937;
}

.register-button {
    font-size: 0.875rem;
    color: #3b82f6;
    text-decoration: underline;
    margin-left: 1rem;
}
</style>

