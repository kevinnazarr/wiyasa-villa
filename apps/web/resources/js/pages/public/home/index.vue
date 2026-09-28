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
        <h1 class="font-display text-display-md">
            {{ $t('public.home.heading') }}
        </h1>
        <p class="mt-2 text-body-sm text-muted-foreground">
            {{ $t('common.tagline') }}
        </p>
        <div class="mt-6 flex flex-wrap gap-3">
            <Link
                :href="bookingIndex({ locale })"
                class="inline-flex h-12 items-center rounded-sm bg-primary px-6 text-button-md text-on-primary transition-colors hover:bg-primary-active"
            >
                {{ $t('common.bookNow') }}
            </Link>
            <Link
                :href="cabinsIndex({ locale })"
                class="inline-flex h-12 items-center rounded-sm border border-border-strong bg-surface-card px-6 text-button-md text-primary"
            >
                {{ $t('nav.cabins') }}
            </Link>
        </div>
        <p class="mt-8 text-sm text-muted-foreground">
            {{ $t('public.home.empty') }}
        </p>
    </main>
</template>
