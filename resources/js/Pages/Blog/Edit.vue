<template>
    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-bold mb-6">Edit Blog Post</h1>
        <form @submit.prevent="submit">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input v-model="form.title" type="text" id="title" class="mt-1 block w-full" required>
            </div>
            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                <textarea v-model="form.content" id="content" rows="5" class="mt-1 block w-full" required></textarea>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Update</button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';

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
</style>
