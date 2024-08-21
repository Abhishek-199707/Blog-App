<template>
    <div class="container mx-auto py-12 relative">
        <!-- Success Message -->
        <div v-if="successMessage" class="success-message">
            {{ successMessage }}
        </div>

        <!-- Top Right Buttons (Logout, Write, and Register) -->
        <div class="top-right-buttons">
            <Link
                v-if="auth.user"
                :href="route('logout')"
                method="post"
                as="button"
                class="logout-button"
            >
                Logout
            </Link>
            <Link
                v-if="auth.user"
                :href="route('dashboard')"
                as="button"
                class="write-button"
            >
                Write
            </Link>
            <Link
                v-else
                :href="route('register')"
                as="button"
                class="register-button"
            >
                Register
            </Link>
        </div>

        <h1 class="text-3xl font-bold mb-6">Blog Posts</h1>

        <!-- User Authentication Section -->
        <div v-if="auth.user" class="mb-6">
            <p>Welcome, {{ auth.user?.name }}!</p>
        </div>
        <div v-else class="mb-6">
            <p class="text-xl">
                Please
                <Link :href="route('login')" class="login-link">login</Link>
                to post your blog.
            </p>
        </div>

        <!-- Blog Posts Listing -->
        <div v-for="blog in blogs" :key="blog.id" class="blog-post mb-4">
            <h2 class="text-xl font-bold">{{ blog.title }}</h2>
            <p>
                {{ (blog.content?.length > 300 ? blog.content.substring(0, 300) + '...' : blog.content) || 'No content available' }}
            </p>
            <p class="text-sm text-gray-500">
                By {{ blog.user?.name || 'Unknown author' }} on {{ new Date(blog.created_at).toLocaleDateString() }}
            </p>

            <!-- Repost Information -->
            <div v-if="blog.reposts.length > 0" class="repost-info mt-4">
                <h3 class="text-lg font-semibold">Reposted By:</h3>
                <ul>
                    <li v-for="repost in blog.reposts" :key="repost.id">
                        <p><strong>{{ repost.user?.name }}:</strong> Originally posted by {{ blog.originalAuthor?.name || 'Unknown author' }}</p>
                    </li>
                </ul>
            </div>

            <!-- View Button -->
            <Link :href="route('blogs.show', blog.id)" class="view-button">View</Link>

            <!-- Repost Button -->
            <button
                v-if="auth.user"
                @click="repost(blog.id)"
                class="repost-button"
            >
                Repost
            </button>

            <!-- Delete Repost Button -->
            <button
                v-if="auth.user && hasReposted(blog.id)"
                @click="deleteRepost(blog.id)"
                class="delete-repost-button"
            >
                Delete Repost
            </button>

            <!-- Comments Section -->
            <div v-if="blog.comments?.length" class="comments-section mt-4">
                <h3 class="text-lg font-semibold">Comments</h3>
                <div v-for="comment in blog.comments" :key="comment.id" class="comment mt-2">
                    <p><strong>{{ comment.user?.name || 'Anonymous' }}:</strong> {{ comment.content || 'No content' }}</p>
                </div>
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
import { Inertia } from '@inertiajs/inertia';
import Pagination from '@/Components/Pagination.vue';

const { props } = usePage();
const blogs = ref(props.blogs?.data || []);
const auth = ref(props.auth || {});
const currentPage = ref(props.blogs?.current_page || 1);
const totalPages = ref(props.blogs?.last_page || 1);
const successMessage = ref(props.success || null);

const repost = async (blogId) => {
    await Inertia.post(route('blogs.repost', blogId), {}, {
        onSuccess: () => {
            successMessage.value = 'Blog reposted successfully!';
            fetchBlogs(currentPage.value);
        },
        onError: (errors) => {
            console.error(errors);
        }
    });
};

const deleteRepost = async (blogId) => {
    await Inertia.delete(route('blogs.delete-repost', blogId), {}, {
        onSuccess: () => {
            successMessage.value = 'Repost deleted successfully!';
            fetchBlogs(currentPage.value);
        },
        onError: (errors) => {
            console.error(errors);
        }
    });
};

const hasReposted = (blogId) => {
    // Check if the current user has reposted this blog
    return blogs.value.some(blog => blog.id === blogId && blog.reposts.some(repost => repost.user.id === auth.value.user.id));
};

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
    if (successMessage.value) {
        console.log('Success:', successMessage.value);
    }
});
</script>

<style scoped>
.repost-info {
    padding: 1rem;
    border-top: 1px solid #ddd;
    margin-top: 1rem;
}
.repost-info h3 {
    margin-bottom: 0.5rem;
}
.repost-info ul {
    list-style: none;
    padding: 0;
}
.repost-info li {
    margin-bottom: 0.5rem;
}
.delete-repost-button {
    color: #FF0000;
    background-color: #fff;
    border: 1px solid #FF0000;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease;
    display: inline-block;
    margin-top: 1rem;
}

.delete-repost-button:hover {
    background-color: #FF0000;
    color: white;
}
.reposts-section {
    padding: 1rem;
    border-top: 1px solid #ddd;
    margin-top: 1rem;
}

.repost {
    padding: 0.5rem;
    border-bottom: 1px solid #ddd;
}
.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    position: relative;
}

.login-link {
    color: blue;
}

.login-link:hover {
    color: rgb(3, 146, 79);
}

/* Success Message Styling */
.success-message {
    padding: 1rem;
    margin-bottom: 1rem;
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    border-radius: 4px;
    font-family: 'Roboto', sans-serif;
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

.repost-button {
    color: #007BFF;
    background-color: #fff;
    border: 1px solid #007BFF;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease;
    display: inline-block;
    margin-top: 1rem;
}

.repost-button:hover {
    background-color: #007BFF;
    color: white;
}

.comments-section {
    padding: 1rem;
    border-top: 1px solid #ddd;
    margin-top: 1rem;
}

.comment {
    padding: 0.5rem;
    border-bottom: 1px solid #ddd;
}

.delete-button {
    color: #FF0000;
    text-decoration: none;
    padding: 0.5rem 1rem;
    border: 1px solid #FF0000;
    border-radius: 4px;
    transition: background-color 0.3s ease, color 0.3s ease;
    display: inline-block;
    margin-top: 1rem;
}

.delete-button:hover {
    background-color: #FF0000;
    color: white;
}

.edit-button {
    color: #007BFF;
    text-decoration: none;
    padding: 0.5rem 1rem;
    border: 1px solid #007BFF;
    border-radius: 4px;
    transition: background-color 0.3s ease, color 0.3s ease;
    display: inline-block;
    margin-top: 1rem;
}

.edit-button:hover {
    background-color: #007BFF;
    color: white;
}

/* Top Right Buttons (Logout, Write, and Register) */
.top-right-buttons {
    position: absolute;
    top: 1rem;
    right: 1rem;
    display: flex;
    gap: 1rem;
}

.logout-button, .write-button, .register-button {
    color: white;
    background-color: #007BFF;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    text-decoration: none;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.logout-button:hover, .write-button:hover, .register-button:hover {
    background-color: #0056b3;
}

.reposted-blogs {
    padding: 1rem;
    background-color: #f0f0f0;
    border-radius: 8px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
}

.reposted-blogs h2 {
    color: #333;
    font-family: 'Roboto', sans-serif;
}
</style>
