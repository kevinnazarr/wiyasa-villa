<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { isSupportedLocale } from '@/i18n';
import { index as bookingIndex } from '@/routes/booking';
import type { CabinDetail } from '@/types';

const page = usePage();

const locale = computed(() =>
    isSupportedLocale(page.props.locale) ? page.props.locale : 'id',
);

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
            <p class="text-lg font-medium">{{ cabin.name }}</p>
            <p v-if="cabin.description" class="mt-2 text-body-sm text-body">
                {{ cabin.description }}
            </p>
            <Link
                :href="
                    bookingIndex({ locale }, { query: { cabin: cabin.slug } })
                "
                class="mt-4 inline-flex h-12 items-center rounded-sm bg-primary px-6 text-button-md text-on-primary transition-colors hover:bg-primary-active"
            >
                {{ $t('common.bookNow') }}
            </Link>
        </div>
    </main>
</template>
