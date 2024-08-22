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
                        <p><strong>{{ repost.user?.name }}</strong></p>
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
                <div v-for="comment in blog.comments" :key="comment.id" class="comment mt-2 flex justify-between items-center">
                    <p><strong>{{ comment.user?.name || 'Anonymous' }}:</strong> {{ comment.content || 'No content' }}</p>
                    <button
                        v-if="auth.user && (comment.user_id === auth.user.id || blog.user_id === auth.user.id)"
                        @click="deleteComment(comment.id)"
                        class="delete-comment-button"
                    >
                        Delete
                    </button>
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

const deleteComment = async (commentId) => {
    await Inertia.delete(route('comments.destroy', commentId), {}, {
        onSuccess: () => {
            successMessage.value = 'Comment deleted successfully!';
            fetchBlogs(currentPage.value);
        },
        onError: (errors) => {
            console.error(errors);
        }
    });
};

const hasReposted = (blogId) => {
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
.delete-comment-button {
    background: transparent;
    border: none;
    cursor: pointer;
    color: red;
    font-weight: bold;
}

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
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.top-right-buttons {
    position: absolute;
    top: 1rem;
    right: 1rem;
    display: flex;
    gap: 0.5rem;
}

.logout-button, .write-button, .register-button {
    padding: 0.5rem 1rem;
    color: white;
    background-color: #333;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.logout-button:hover,
.write-button:hover,
.register-button:hover {
    background-color: rgb(11, 61, 156);
}

.logout-button {
    background-color: #ff0000;
}

.logout-button:hover {
    background-color: #cc0000;
}

.view-button {
    display: inline-block;
    margin-top: 0.5rem;
    padding: 0.5rem 1rem;
    color: white;
    background-color: #333;
    border-radius: 4px;
    text-align: center;
    cursor: pointer;
    transition: background-color 0.3s ease;
    text-decoration: none;
}

.view-button:hover {
    background-color: #666;
}

.repost-button {
    display: inline-block;
    margin-top: 0.5rem;
    padding: 0.5rem 1rem;
    color: #fff;
    background-color: #0b3d9c;
    border-radius: 4px;
    text-align: center;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.repost-button:hover {
    background-color: #0b61d9;
}

.comment {
    background-color: #f4f4f4;
    padding: 0.5rem;
    border-radius: 4px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
</style>
