<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { isSupportedLocale } from '@/i18n';
import { index as bookingIndex } from '@/routes/booking';
import { index as cabinsIndex } from '@/routes/cabins';

const page = usePage();

const locale = computed(() =>
    isSupportedLocale(page.props.locale) ? page.props.locale : 'id',
);
</script>

<template>
    <Head :title="$t('public.home.title')" />

    <main class="mx-auto max-w-7xl px-4 py-12">
        <h1 class="text-3xl font-semibold">{{ $t('public.home.heading') }}</h1>
        <p class="mt-2 text-muted-foreground">{{ $t('common.tagline') }}</p>
        <div class="mt-6 flex flex-wrap gap-3">
            <Link
                :href="bookingIndex({ locale })"
                class="inline-flex h-10 items-center rounded-md bg-primary px-5 text-sm font-medium text-on-primary"
            >
                {{ $t('common.bookNow') }}
            </Link>
            <Link
                :href="cabinsIndex({ locale })"
                class="inline-flex h-10 items-center rounded-md border border-border px-5 text-sm font-medium"
            >
                {{ $t('nav.cabins') }}
            </Link>
        </div>
        <p class="mt-8 text-sm text-muted-foreground">
            {{ $t('public.home.empty') }}
        </p>
    </main>
</template>
