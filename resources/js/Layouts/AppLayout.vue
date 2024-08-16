<template>
    <div>
      <header>
        <a href="/login">Login</a>
        <a href="/register">Register</a>
        <LogoutButton v-if="user" />
      </header>
      <main>
        <slot></slot>
      </main>
    </div>
  </template>

  <script setup>
  import LogoutButton from '@/Components/LogoutButton.vue';
  import { usePage } from '@inertiajs/inertia-vue3';

  const { props } = usePage();
  const user = props.value.auth.user;
  </script>
