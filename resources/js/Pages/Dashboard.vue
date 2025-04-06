<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import { ref, onMounted } from 'vue';
import Layout from '@/Layouts/Layout.vue';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

const form = ref({
    title: '',
    content: '',
});

let editor;

onMounted(() => {
    editor = new Quill('#editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ header: [1, 2, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['link', 'image', 'video'],
            ],
        },
    });

    // Custom image upload handler
    const toolbar = editor.getModule('toolbar');
    toolbar.addHandler('image', () => {
        selectLocalImage();
    });

    // Event listener to update form content when editor content changes
    editor.on('text-change', () => {
        form.value.content = editor.root.innerHTML;
    });
});

// Function to handle local image uploads
const selectLocalImage = () => {
    const input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');
    input.click();

    input.onchange = async () => {
        const file = input.files[0];
        const formData = new FormData();
        formData.append('image', file);

        try {
            const response = await fetch('/upload-image', {
                method: 'POST',
                body: formData,
            });

            const data = await response.json();

            // Insert uploaded image URL into the editor
            const range = editor.getSelection();
            editor.insertEmbed(range.index, 'image', data.url);
        } catch (error) {
            console.error('Image upload failed:', error);
        }
    };
};

const submit = () => {
    Inertia.post(route('blogs.store'), form.value, {
        onSuccess: () => {
            form.value.title = '';
            form.value.content = '';
            editor.root.innerHTML = ''; // Reset the editor content
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
                                placeholder="Title"
                                class="input-field mb-4"
                                required
                            />
                            <div id="editor" class="textarea-field mb-4"></div>
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
    min-height: 200px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background-color: #f9fafb;
    padding: 1rem;
    font-size: 1rem;
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
