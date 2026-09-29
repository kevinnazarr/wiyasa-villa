<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { isSupportedLocale } from '@/i18n';
import { show as cabinShow } from '@/routes/cabins';

type CabinSummary = {
    id: number | string;
    name: string;
};

defineProps<{
    cabins?: CabinSummary[];
}>();

const page = usePage();

const locale = computed(() =>
    isSupportedLocale(page.props.locale) ? page.props.locale : 'id',
);
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
                <Link :href="cabinShow({ locale, cabin: cabin.id })">
                    {{ cabin.name }}
                </Link>
            </li>
        </ul>
    </main>
</template>
