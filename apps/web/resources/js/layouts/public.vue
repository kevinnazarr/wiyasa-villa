<script setup lang="ts">
import Footer from '@/components/public/footer/index.vue';
import Navbar from '@/components/public/navbar/index.vue';
import { Toaster } from '@/components/ui/sonner';
</script>

<template>
    <div class="min-h-svh bg-background text-foreground">
        <Navbar />
        <slot />
        <Footer />
        <Toaster />
    </div>
</template>
