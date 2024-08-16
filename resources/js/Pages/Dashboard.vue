<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import { ref } from 'vue';

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
        }
    });
};
</script>

<template>
    <Head title="Thoughts" />

    <AuthenticatedLayout>
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
                            <input v-model="form.title" type="text" placeholder="Title" class="input-field mb-4" />
                            <textarea v-model="form.content" placeholder="Content" class="textarea-field mb-4"></textarea>
                            <button type="submit" class="submit-button">Submit</button>
                        </form>
                    </div>
                    <p v-else>Please <Link :href="route('login')">login</Link> to write a blog.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.blog-form-container {
    background-color: #f9f9f9;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.input-field,
.textarea-field {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

.textarea-field {
    height: 150px;
    resize: vertical;
}

.submit-button {
    background-color: #007bff;
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 4px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.submit-button:hover {
    background-color: #0056b3;
}
</style>
