<template>
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <header class="bg-yellow-100 text-white py-4 shadow-lg">
            <div class="container mx-auto flex justify-between items-center">
                <!-- Clickable Blog Title -->
                <Link :href="route('home')" class="text-2xl text-black font-bold">
                    Thoughts
                </Link>
                <nav class="flex items-center space-x-4">
                    <Link
                        :href="route('home')"
                        class="nav-link"
                        :class="{ 'active': route().current('home') }"
                    >
                        Home
                    </Link>

                    <!-- Profile and Logout for logged-in users -->
                    <Link
                        v-if="auth.user"
                        :href="route('profile.edit')"
                        class="nav-link"
                        :class="{ 'active': route().current('profile.edit') }"
                    >
                        Profile
                    </Link>
                    <Link
                        v-if="auth.user"
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="logout-button"
                    >
                        Logout
                    </Link>

                    <!-- Login and Register for guests -->
                    <Link
                        v-if="!auth.user"
                        :href="route('login')"
                        class="nav-link"
                        :class="{ 'active': route().current('login') }"
                    >
                        Login
                    </Link>
                    <Link
                        v-if="!auth.user"
                        :href="route('register')"
                        class="nav-link"
                        :class="{ 'active': route().current('register') }"
                    >
                        Register
                    </Link>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto py-8 flex-grow">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-yellow-100 text-white py-4">
            <div class="container mx-auto text-center text-black">
                &copy; 2024 Thoughts. All rights reserved.
            </div>
        </footer>
    </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';

const { auth } = usePage().props;
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

/* Active state styling */
.nav-link.active {
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
