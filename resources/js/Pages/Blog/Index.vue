<template>
    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-bold mb-6">Blog Posts</h1>
        <div v-if="auth.user" class="mb-6">
            <p>Welcome, {{ auth.user.name }}!</p>
        </div>
        <div v-else class="mb-6">
            <p>Please <Link :href="route('login')">login</Link> to post your blog.</p>
        </div>
        <div v-for="blog in blogs" :key="blog.id" class="blog-post mb-4">
            <h2 class="text-xl font-bold">{{ blog.title }}</h2>
            <p>{{ blog.content }}</p>
            <p class="text-sm text-gray-500">By {{ blog.user.name }} on {{ new Date(blog.created_at).toLocaleDateString() }}</p>
        </div>
        <Pagination
            :currentPage="currentPage"
            :totalPages="totalPages"
            @page-changed="fetchBlogs"
        />
    </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';

const { props } = usePage();
const blogs = ref(props.blogs.data);
const auth = ref(props.auth);
const currentPage = ref(props.blogs.current_page);
const totalPages = ref(props.blogs.last_page);

const fetchBlogs = async (page = 1) => {
    await Inertia.get(route('home', { page }), {}, {
        preserveState: true,
        onSuccess: (page) => {
            blogs.value = page.props.blogs.data;
            totalPages.value = page.props.blogs.last_page;
            currentPage.value = page.props.blogs.current_page;
        }
    });
};

onMounted(async () => {
    await nextTick();
});
</script>

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
