<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

type CabinDetail = {
    id: number | string;
    name: string;
};

defineProps<{
    cabin?: CabinDetail | null;
}>();
</script>

<template>
    <Head :title="$t('public.cabins.title')" />

    <main class="mx-auto max-w-7xl px-4 py-12">
        <h1 class="font-display text-display-sm">
            {{ $t('public.cabins.heading') }}
        </h1>
        <p v-if="!cabin" class="mt-4 text-body-sm text-body">
            {{ $t('public.cabins.detailEmpty') }}
        </p>
        <div
            v-else
            class="mt-6 rounded-lg border border-hairline bg-surface-card p-4"
        >
            {{ cabin.name }}
        </div>
    </main>
</template>
