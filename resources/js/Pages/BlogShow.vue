<template>
    <div class="container mx-auto py-12" v-if="blog">
        <h1 class="text-3xl font-bold mb-6">{{ blog.title }}</h1>
        <div class="blog-content mb-4">
            <p>{{ blog.content }}</p>
        </div>
        <p class="text-sm text-gray-500">
            By {{ blog.user.name }} on {{ formatDate(blog.created_at) }}
        </p>

        <!-- Back to Blog Posts Button -->
        <Link :href="route('home')" class="back-button">Back to Blog Posts</Link>
    </div>
    <div v-else class="container mx-auto py-12">
        <h1 class="text-3xl font-bold mb-6">Blog Post Not Found</h1>
        <Link :href="route('home')" class="back-button">Back to Blog Posts</Link>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const { props } = usePage();
const blog = ref(props.blog);

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString();
};
</script>

<style scoped>
.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

h1 {
    color: #333;
    font-family: 'Roboto', sans-serif;
}

.blog-content {
    margin-bottom: 1.5rem;
    font-family: 'Open Sans', sans-serif;
    line-height: 1.6;
    color: #555;
}

.back-button {
    color: #007BFF;
    text-decoration: none;
    padding: 0.5rem 1rem;
    border: 1px solid #007BFF;
    border-radius: 4px;
    transition: background-color 0.3s ease, color 0.3s ease;
    display: inline-block;
    margin-top: 1rem;
}

.back-button:hover {
    background-color: #007BFF;
    color: white;
}
</style>
