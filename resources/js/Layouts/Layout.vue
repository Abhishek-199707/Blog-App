<template>
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <header class="bg-blue-600 text-white py-4 shadow-lg">
            <div class="container mx-auto flex justify-between items-center">
                <!-- Clickable Blog Title -->
                <Link :href="route('home')" class="text-2xl font-bold">
                    My Blog
                </Link>
                <nav class="flex items-center space-x-4">
                    <Link :href="route('home')" class="nav-link">Home</Link>
                    <Link
                v-if="auth.user"
                :href="route('logout')"
                method="post"
                as="button"
                class="logout-button"
            >
                Logout
            </Link>
                    <Link v-if="!auth.user" :href="route('login')" class="nav-link">Login</Link>
                    <Link v-if="!auth.user" :href="route('register')" class="nav-link">Register</Link>

                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto py-8 flex-grow">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-4">
            <div class="container mx-auto text-center">
                &copy; 2024 My Blog. All rights reserved.
            </div>
        </footer>
    </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';

const { auth } = usePage().props;

const logout = () => {
    document.getElementById('logout-form').submit();
};
</script>

<style scoped>
.container {
    max-width: 900px;
}

.nav-link {
    margin-left: 1rem;
    color: white;
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s ease;
}

.nav-link:hover {
    color: #cbd5e0;
}

.logout-button {
    color: white;
    background-color: #1a202c;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.logout-button:hover {
    background-color: #2d3748;
}
</style>
