<template>
    <Layout>
    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-bold mb-6">Edit Blog Post</h1>
        <form @submit.prevent="submit">
            <div class="mb-4">
                <label for="title" class="block text-md font-bold text-gray-700">Title</label>
                <input v-model="form.title" type="text" id="title" class="input-field" required>
            </div>
            <div class="mb-4">
                <label for="content" class="block text-md font-bold text-gray-700">Content</label>
                <textarea v-model="form.content" id="content" rows="5" class="textarea-field" required></textarea>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="button">Update</button>
            </div>
        </form>
    </div>
</Layout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import Layout from '@/Layouts/Layout.vue';

const { props } = usePage();
const form = useForm({
    title: props.blog.title,
    content: props.blog.content,
});

const submit = () => {
    form.put(route('blogs.update', props.blog.id));
};
</script>

<style scoped>
.container {
    max-width: 600px;
    margin: 0 auto;
}

.button{
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

.input-field,
.textarea-field {
    width: 100%;
    padding: 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s ease, background-color 0.3s ease;
    margin-bottom: 1rem;
    background-color: #f9fafb;
}

.button:hover{
    background-color: #1a202c;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
}
</style>
