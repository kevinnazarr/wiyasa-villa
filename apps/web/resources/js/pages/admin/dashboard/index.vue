<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import { dashboard } from '@/routes';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Admin Dashboard',
                href: dashboard(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Admin Dashboard" />

    <div
        class="gap-4 rounded-xl p-4 flex h-full flex-1 flex-col overflow-x-auto"
    >
        <div class="gap-4 md:grid-cols-3 grid auto-rows-min">
            <div
                class="aspect-video rounded-xl border-sidebar-border/70 dark:border-sidebar-border relative overflow-hidden border"
            >
                <PlaceholderPattern />
            </div>
        </div>
    </div>
</template>
