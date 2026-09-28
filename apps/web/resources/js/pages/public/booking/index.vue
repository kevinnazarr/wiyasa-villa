<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

defineProps<{
    cabinId?: number | string | null;
}>();
</script>

<template>
    <Head :title="$t('public.booking.title')" />

    <main class="mx-auto max-w-7xl px-4 py-12">
        <h1 class="text-3xl font-semibold">
            {{ $t('public.booking.heading') }}
        </h1>
        <p class="mt-4 text-sm text-muted-foreground">
            {{ $t('public.booking.empty') }}
        </p>
    </main>
</template>
