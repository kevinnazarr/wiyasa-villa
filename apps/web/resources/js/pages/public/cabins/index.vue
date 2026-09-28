<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { isSupportedLocale } from '@/i18n';
import { index as bookingIndex } from '@/routes/booking';
import { show as cabinsShow } from '@/routes/cabins';
import type { CabinSummary } from '@/types';

const page = usePage();

const locale = computed(() =>
    isSupportedLocale(page.props.locale) ? page.props.locale : 'id',
);

defineProps<{
    cabins?: CabinSummary[] | null;
}>();
</script>

<template>
    <Head :title="$t('public.cabins.title')" />

    <main class="mx-auto max-w-7xl px-4 py-12">
        <h1 class="font-display text-display-sm">
            {{ $t('public.cabins.heading') }}
        </h1>
        <p
            v-if="!cabins || cabins.length === 0"
            class="mt-4 text-body-sm text-body"
        >
            {{ $t('public.cabins.empty') }}
        </p>
        <ul v-else class="mt-6 grid gap-4 md:grid-cols-3">
            <li
                v-for="cabin in cabins"
                :key="cabin.id"
                class="rounded-lg border border-hairline bg-surface-card p-4"
            >
                <Link
                    :href="cabinsShow({ locale, cabin: cabin.slug })"
                    class="font-medium text-primary hover:underline"
                >
                    {{ cabin.name }}
                </Link>
            </li>
        </ul>
        <p class="mt-8">
            <Link
                :href="bookingIndex({ locale })"
                class="inline-flex h-12 items-center rounded-sm bg-primary px-6 text-button-md text-on-primary transition-colors hover:bg-primary-active"
            >
                {{ $t('common.bookNow') }}
            </Link>
        </p>
    </main>
</template>
