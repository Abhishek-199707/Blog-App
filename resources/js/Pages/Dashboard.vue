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
        }
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
                            <input v-model="form.title" type="text" placeholder="Title" class="input-field mb-4" />
                            <textarea v-model="form.content" placeholder="Content" class="textarea-field mb-4"></textarea>
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
/* Container for the blog form */
.blog-form-container {
    background: linear-gradient(135deg, #f3f4f6, #ffffff);
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    transition: box-shadow 0.3s ease, transform 0.3s ease;
}

.blog-form-container:hover {
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    transform: translateY(-5px);
}

/* Input fields */
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

.input-field:focus,
.textarea-field:focus {
    border-color: #1a202c;
    outline: none;
}

/* Textarea styling */
.textarea-field {
    height: 200px;
    resize: vertical;
}

/* Submit button */
.submit-button {
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

.submit-button:hover {
    background-color: #1a202c;
    color: white;
}

/* Header styling */
h2 {
    color: #1a202c;
    font-family: 'Poppins', sans-serif;
    font-size: 2rem;
    text-align: center;
    margin-bottom: 1.5rem;
    font-weight: 600;
    text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.1);
}
</style>


