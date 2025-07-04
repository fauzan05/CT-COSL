<template>
    <div class="flex">
        <Sidebar />
        <div class="flex-1">
            <Navbar />
            <main class="p-4">
                <slot />
            </main>
        </div>
    </div>
</template>
  
<script setup>
import Sidebar from './Sidebar.vue';
import Navbar from './Navbar.vue';
</script>
  