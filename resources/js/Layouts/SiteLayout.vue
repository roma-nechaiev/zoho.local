<script setup lang="ts">
import { Toaster } from "vue-sonner";
import Header from "@/Features/Header/index.vue";
</script>

<template>
    <div class="flex min-h-screen w-full flex-col">
        <Header />
        <main class="container space-y-8 py-10">
            <slot />
        </main>
        <Toaster position="bottom-right" />
    </div>
</template>
