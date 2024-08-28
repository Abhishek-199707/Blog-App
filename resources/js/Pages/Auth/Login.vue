<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
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
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="status-message">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
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

            <div class="flex items-center justify-end mt-4">
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
                <Link
                    :href="route('register')"
                    class="register-button ms-4"
                >
                    Register
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>

<style scoped>
/* General Button Styles */
.primary-button {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-size: 1rem; /* Adjust this for larger button text */
    font-weight: bold;
    text-transform: uppercase;
    transition: all 0.3s ease;
    cursor: pointer;
    display: inline-block;
}

/* Primary Button */
.primary-button {
    background: linear-gradient(90deg, #3b82f6, #1e3a8a);
    color: #fff;
    border: none;
}

.primary-button:hover {
    background: linear-gradient(90deg, #1e3a8a, #3b82f6);
    transform: translateY(-2px);
}

/* Form Styling */
form {
    background: #fff;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    margin: auto;
}

input {
    font-size: 1.125rem; /* Slightly larger font size for inputs */
    line-height: 1.6; /* Increased line height for more spacing */
}

/* Label Styling */
input[type="email"], input[type="password"] {
    padding: 0.75rem;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    width: 100%;
    margin-bottom: 1rem;
}

/* Error Messages */
input.error {
    border-color: #f87171; /* Error border color */
}

.error-message {
    color: #ef4444;
    margin-top: 0.5rem;
    font-size: 0.875rem;
}

/* Checkbox Styling */
.checkbox-label {
    font-size: 1rem;
    color: #4b5563; /* Darker text color for checkboxes */
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}

/* Forgot Password Link */
.forgot-password {
    font-size: 0.875rem;
    color: #6b7280;
    text-decoration: underline;
    transition: color 0.3s ease;
}

.forgot-password:hover {
    color: #1f2937;
}

/* Margin for Status Messages */
.status-message {
    margin-bottom: 1rem;
    font-size: 1rem;
    color: #059669; /* Success message color */
}

</style>
