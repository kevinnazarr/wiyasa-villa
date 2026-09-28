<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { isSupportedLocale } from '@/i18n';
import { show as cabinsShow } from '@/routes/cabins';
import type { CabinSummary } from '@/types';

const page = usePage();

const locale = computed(() =>
    isSupportedLocale(page.props.locale) ? page.props.locale : 'id',
);

defineProps<{
    cabins?: CabinSummary[] | null;
    cabinSlug?: string | null;
}>();
</script>

<template>
    <Head :title="$t('public.booking.title')" />

    <main class="mx-auto max-w-7xl px-4 py-12">
        <h1 class="font-display text-display-sm">
            {{ $t('public.booking.heading') }}
        </h1>
        <div v-if="!cabinSlug" class="mt-4">
            <p
                v-if="!cabins || cabins.length === 0"
                class="text-body-sm text-body"
            >
                {{ $t('public.booking.empty') }}
            </p>
            <ul v-else class="mt-4 grid gap-4 md:grid-cols-3">
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
        </div>
        <p v-else class="mt-4 text-body-sm text-body">
            {{ cabinSlug }}
        </p>
    </main>
</template>
