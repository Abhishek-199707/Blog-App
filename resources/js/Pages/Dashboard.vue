<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import { ref } from 'vue';
import Layout from '@/Layouts/Layout.vue';

const form = ref({
    title: '',
    content: '',
});

const submit = () => {
    Inertia.post(route('blogs.store'), form.value, {
        onSuccess: () => {
            form.value.title = '';
            form.value.content = '';
        },
        onError: (errors) => {
            console.log(errors);
        },
    });
};
</script>

<template>
    <Layout>
        <Head title="Thoughts" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Welcome to Your Blog !!!!</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">You're logged in!</div>
                </div>
                <div class="mt-6">
                    <div v-if="$page.props.auth.user" class="blog-form-container">
                        <form @submit.prevent="submit">
                            <input
                                v-model="form.title"
                                type="text"
                                placeholder="Blog Title"
                                class="input-field mb-4"
                                required
                            />
                            <textarea
                                v-model="form.content"
                                placeholder="Write your blog content here..."
                                class="textarea-field mb-4"
                                rows="10"
                                required
                            ></textarea>
                            <button type="submit" class="submit-button">Submit</button>
                        </form>
                    </div>
                    <p v-else>Please <Link :href="route('login')">login</Link> to write a blog.</p>
                </div>
            </div>
        </div>
    </Layout>
</template>

<style scoped>
.blog-form-container {
    background: linear-gradient(135deg, #f3f4f6, #ffffff);
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
}

.input-field {
    width: 100%;
    padding: 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    background-color: #f9fafb;
    margin-bottom: 1rem;
    font-size: 1rem;
}

.textarea-field {
    width: 100%;
    padding: 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    background-color: #f9fafb;
    font-size: 1rem;
    resize: vertical;
}

.submit-button {
    color: #1a202c;
    border: 2px solid #1a202c;
    border-radius: 4px;
    padding: 0.75rem 1.5rem;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s, color 0.3s;
}

.submit-button:hover {
    background-color: #1a202c;
    color: white;
}
</style>
