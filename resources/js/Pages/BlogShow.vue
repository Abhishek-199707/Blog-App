<template>
    <Layout>
        <div class="container mx-auto py-12" v-if="blog">
            <h1 class="text-3xl font-bold mb-6">{{ blog.title }}</h1>
            <div class="blog-content mb-4">
                <p>{{ blog.content }}</p>
            </div>
            <p class="text-sm text-gray-500">
                By {{ blog.user.name }} on {{ formatDate(blog.created_at) }}
            </p>

            <!-- Edit and Delete Buttons -->
            <div v-if="auth.user && auth.user.id === blog.user.id" class="mb-4">
                <Link :href="route('blogs.edit', blog.id)" class="standard-button">Edit</Link>
                <button @click="confirmDelete" class="standard-button">Delete</button>
            </div>

            <!-- Comments Section -->
            <div class="comments-section mt-6">
                <h2 class="text-2xl font-bold mb-4">Comments</h2>

                <div v-for="comment in visibleComments" :key="comment.id" class="comment mb-4">
                    <p>{{ comment.content }}</p>
                    <p class="text-sm text-gray-500">By {{ comment.user.name }} on {{ formatDate(comment.created_at) }}</p>
                </div>

                <!-- Show More Button -->
                <button v-if="showMoreButtonVisible" @click="loadMoreComments" class="standard-button">
                    Show More
                </button>
            </div>

            <!-- Comment Form -->
            <div v-if="auth.user" class="mt-6">
                <form @submit.prevent="postComment">
                    <textarea v-model="commentContent" rows="3" class="w-full p-2 border rounded" placeholder="Add a comment..."></textarea>
                    <button type="submit" class="standard-button mt-2">Post Comment</button>
                </form>
            </div>

            <!-- Back to Blog Posts Button -->
            <Link :href="route('home')" class="standard-button mt-6">Back to Blog Posts</Link>
        </div>
    </Layout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import Layout from '@/Layouts/Layout.vue';

const { props } = usePage();
const blog = ref(props.blog);
const auth = ref(props.auth);
const commentContent = ref('');
const commentsLoaded = ref(5);

const visibleComments = computed(() => {
    return blog.value.comments ? blog.value.comments.slice(0, commentsLoaded.value) : [];
});

const showMoreButtonVisible = computed(() => {
    return blog.value.comments && blog.value.comments.length > commentsLoaded.value;
});

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString();
};

const postComment = async () => {
    await Inertia.post(route('comments.store', blog.value.id), {
        content: commentContent.value,
    }, {
        onSuccess: () => {
            commentContent.value = '';
            Inertia.reload({ only: ['blog'] });
        }
    });
};

const confirmDelete = () => {
    if (confirm('Are you sure you want to delete this blog post?')) {
        Inertia.delete(route('blogs.destroy', blog.value.id));
    }
};

const loadMoreComments = () => {
    commentsLoaded.value += 5;
};
</script>


<style scoped>
.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
    overflow-wrap: break-word;
}

.standard-button {
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

.standard-button:hover {
    background-color: #1a202c;
    color: white;
}

textarea {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #1a202c;
    border-radius: 4px;
    font-size: 1rem;
    color: #333;
}

textarea:focus {
    border-color: #4a5568;
    outline: none;
    box-shadow: 0 0 0 2px rgba(74, 85, 104, 0.5);
}
</style>
