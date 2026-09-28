<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';

const name = usePage().props.name;
</script>

<template>
    <div
        class="size-8 rounded-md bg-sidebar-primary text-sidebar-primary-foreground flex aspect-square items-center justify-center"
    >
        <AppLogoIcon class="size-5 text-white dark:text-black fill-current" />
    </div>
    <div class="ml-1 text-sm grid flex-1 text-left">
        <span class="mb-0.5 leading-tight font-semibold truncate">{{
            name
        }}</span>
    </div>
</template>
