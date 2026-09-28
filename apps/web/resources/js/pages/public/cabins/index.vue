<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

type CabinSummary = {
    id: number | string;
    name: string;
};

defineProps<{
    cabins?: CabinSummary[];
}>();
</script>

<template>
    <Head :title="$t('public.cabins.title')" />

    <main class="mx-auto max-w-7xl px-4 py-12">
        <h1 class="text-3xl font-semibold">
            {{ $t('public.cabins.heading') }}
        </h1>
        <p
            v-if="!cabins || cabins.length === 0"
            class="mt-4 text-sm text-muted-foreground"
        >
            {{ $t('public.cabins.empty') }}
        </p>
        <ul v-else class="mt-6 grid gap-4 md:grid-cols-3">
            <li
                v-for="cabin in cabins"
                :key="cabin.id"
                class="rounded-xl border border-border p-4"
            >
                {{ cabin.name }}
            </li>
        </ul>
    </main>
</template>
