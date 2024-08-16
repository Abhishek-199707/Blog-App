<script setup>
import { Head, Link } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, onMounted } from 'vue';
import Pagination from '@/Components/Pagination.vue';

const blogs = ref([]);
const currentPage = ref(1);
const totalPages = ref(1);

const fetchBlogs = async (page = 1) => {
    try {
        const response = await axios.get(`/api/blogs?page=${page}`);
        blogs.value = response.data.data;
        totalPages.value = response.data.last_page;
        currentPage.value = response.data.current_page;
    } catch (error) {
        console.error(error);
    }
};

onMounted(() => {
    fetchBlogs();
});
</script>

<template>
    <Head title="Home" />

    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-bold mb-6">Blog Posts</h1>
        <div v-if="$page.props.auth.user" class="mb-6">
            <p>Welcome, {{ $page.props.auth.user.name }}!</p>
        </div>
        <div v-else class="mb-6">
            <p>Please <Link :href="route('login')">login</Link> to post your blog.</p>
        </div>
        <div v-for="blog in blogs" :key="blog.id" class="blog-post mb-4">
            <h2 class="text-xl font-bold">{{ blog.title }}</h2>
            <p>{{ blog.content }}</p>
            <p class="text-sm text-gray-500">By {{ blog.user.name }} on {{ blog.created_at }}</p>
        </div>
        <Pagination :currentPage="currentPage" :totalPages="totalPages" @page-changed="fetchBlogs" />
    </div>
</template>

<style scoped>
.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 1rem;
}

.blog-post {
    padding: 1rem;
    border-bottom: 1px solid #ddd;
}

.blog-post h2 {
    margin-top: 0;
}

.blog-post p {
    margin: 0.5rem 0;
}
</style>
