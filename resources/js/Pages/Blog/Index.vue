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
/* General Button Styles */

button, .logout-button, .write-button, .register-button, .view-button, .delete-repost-button {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: bold;
    text-transform: uppercase;
    transition: all 0.3s ease;
    cursor: pointer;
    margin-right: 1rem;
    margin-bottom: 1rem;
    display: inline-block;
}

/* Logout, Write, Register Buttons */
.logout-button, .write-button, .register-button {
    background: linear-gradient(90deg, #1e3a8a, #3b82f6);
    color: #fff;
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.logout-button:hover, .write-button:hover, .register-button:hover {
    background: linear-gradient(90deg, #3b82f6, #1e3a8a);
    transform: translateY(-2px);
    box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
}

/* View Button */
.view-button {
    background: linear-gradient(90deg, #10b981, #34d399);
    color: #fff;
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.view-button:hover {
    background: linear-gradient(90deg, #34d399, #10b981);
    transform: translateY(-2px);
    box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
}

/* Repost Button */
.repost-button {
    background: linear-gradient(90deg, #f59e0b, #fbbf24);
    color: #fff;
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.repost-button:hover {
    background: linear-gradient(90deg, #fbbf24, #f59e0b);
    transform: translateY(-2px);
    box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
}

/* Delete Comment Button */
.delete-comment-button {
    background: linear-gradient(90deg, #ef4444, #f87171);
    color: #fff;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
    margin-left: 1rem;
}

.delete-comment-button:hover {
    background: linear-gradient(90deg, #f87171, #ef4444);
    box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

/* Repost Info */
.repost-info {
    padding: 1.5rem;
    border-top: 2px solid #ddd;
    margin-top: 1.5rem;
    background-color: #f9fafb;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.repost-info h3 {
    margin-bottom: 0.75rem;
}

.repost-info ul {
    list-style: none;
    padding: 0;
}

.repost-info li {
    margin-bottom: 0.75rem;
    padding: 0.5rem;
    border-bottom: 1px solid #ddd;
}

/* General Spacing */
.container {
    padding: 2rem 1.5rem;
}

.blog-post {
    margin-bottom: 3rem;
}

.top-right-buttons {
    position: absolute;
    top: 2rem;
    right: 2rem;
}

h1, h2 {
    margin-bottom: 1.5rem;
}

.comments-section {
    margin-top: 2rem;
}

.comment {
    margin-bottom: 1rem;
    padding: 0.75rem;
    background-color: #f1f5f9;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}
</style>

