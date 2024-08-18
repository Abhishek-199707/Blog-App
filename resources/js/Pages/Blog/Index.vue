<template>
    <div class="container mx-auto py-12 relative">
        <!-- Top Right Buttons (Logout and Write) -->
        <div v-if="auth.user" class="top-right-buttons">
            <Link :href="route('logout')" method="post" as="button" class="logout-button">Logout</Link>
            <Link :href="route('dashboard')" as="button" class="write-button">Write</Link>
        </div>

        <h1 class="text-3xl font-bold mb-6">Blog Posts</h1>

        <!-- User Authentication Section -->
        <div v-if="auth.user" class="mb-6">
            <p>Welcome, {{ auth.user.name }}!</p>
        </div>
        <div v-else class="mb-6">
            <p class="text-xl">Please <Link :href="route('login')" class="login-link">login</Link> to post your blog.</p>
        </div>

        <!-- Blog Posts Listing -->
        <div v-for="blog in blogs" :key="blog.id" class="blog-post mb-4">
            <h2 class="text-xl font-bold">{{ blog.title }}</h2>
            <p>
                {{ blog.content.length > 300 ? blog.content.substring(0, 300) + '...' : blog.content }}
            </p>
            <p class="text-sm text-gray-500">
                By {{ blog.user.name }} on {{ new Date(blog.created_at).toLocaleDateString() }}
            </p>

            <!-- View Button -->
            <Link :href="route('blogs.show', blog.id)" class="view-button">View</Link>

            <!-- Edit Button (Visible only to the author) -->
            <div v-if="auth.user && auth.user.id === blog.user.id" class="mt-2">
                <Link :href="route('blogs.edit', blog.id)" class="edit-button">Edit</Link>
            </div>
        </div>

        <!-- Pagination -->
        <Pagination
            :currentPage="currentPage"
            :totalPages="totalPages"
            @page-changed="fetchBlogs"
        />
    </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import { Inertia } from '@inertiajs/inertia';

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
    padding: 2rem;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    position: relative;
}

h1 {
    color: #333;
    text-align: center;
    font-family: 'Roboto', sans-serif;
    letter-spacing: 1px;
}

.blog-post {
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.blog-post:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.blog-post h2 {
    margin-top: 0;
    font-size: 1.75rem;
    color: #007BFF;
    font-family: 'Roboto', sans-serif;
    transition: color 0.3s ease;
}

.blog-post h2:hover {
    color: #0056b3;
}

.blog-post p {
    margin: 0.75rem 0;
    font-family: 'Open Sans', sans-serif;
    line-height: 1.6;
    color: #555;
}

.blog-post p.text-sm {
    margin-top: 1rem;
    color: #999;
}

.view-button {
    color: #007BFF;
    text-decoration: none;
    padding: 0.5rem 1rem;
    border: 1px solid #007BFF;
    border-radius: 4px;
    transition: background-color 0.3s ease, color 0.3s ease;
    display: inline-block;
    margin-top: 1rem;
}

.view-button:hover {
    background-color: #007BFF;
    color: white;
}

/* Top Right Buttons (Logout and Write) */
.top-right-buttons {
    position: absolute;
    top: 1rem;
    right: 1rem;
    display: flex;
    gap: 1rem;
}

.logout-button, .write-button {
    color: white;
    background-color: #007BFF;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    text-decoration: none;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.logout-button:hover, .write-button:hover {
    background-color: #0056b3;
}

/* Edit Button */
.edit-button {
    color: white;
    background-color: #28a745;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    text-decoration: none;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.edit-button:hover {
    background-color: #218838;
}
</style>
