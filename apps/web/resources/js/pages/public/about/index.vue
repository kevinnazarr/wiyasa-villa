<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head :title="$t('public.about.title')" />

    <main class="mx-auto max-w-7xl px-4 py-12">
        <h1 class="font-display text-display-sm">
            {{ $t('public.about.heading') }}
        </h1>
        <p class="mt-4 text-body-sm text-body">
            {{ $t('public.about.empty') }}
        </p>
    </main>
</template>
