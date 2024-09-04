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
    color: #1a202c;
    text-decoration: none;
    padding: 0.75rem 1.5rem;
    border: 2px solid #1a202c;
    border-radius: 4px;
    transition: background-color 0.3s ease, color 0.3s ease;
    display: inline-block;
    margin-right: 1rem;
    margin-bottom: 0.5rem;
    font-weight: bold;
    cursor: pointer;
}

.nav-link:hover {
    background-color: #1a202c;
    color: white;
}

.logout-button {
    color: #1a202c;
    text-decoration: none;
    padding: 0.75rem 1.5rem;
    border: 2px solid #1a202c;
    border-radius: 4px;
    transition: background-color 0.3s ease, color 0.3s ease;
    display: inline-block;
    margin-right: 1rem;
    margin-bottom: 0.5rem;
    font-weight: bold;
    cursor: pointer;
}

.logout-button:hover {
    background-color: #1a202c;
    color: white;
}
</style>
