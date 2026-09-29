<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { index as profileIndex } from '@/routes/profile';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Profile',
                href: profileIndex(),
            },
        ],
    },
});
</script>

<template>
    <Head :title="$t('nav.profile')" />

    <main class="mx-auto max-w-7xl px-4 py-12">
        <h1 class="font-display text-display-sm">
            {{ $t('nav.profile') }}
        </h1>
    </main>
</template>
