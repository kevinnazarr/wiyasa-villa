<script setup lang="ts">
import { Toaster } from '@/components/ui/sonner';
</script>

<template>
    <div class="bg-background text-foreground min-h-svh">
        <slot />
        <Toaster />
    </div>
</template>
