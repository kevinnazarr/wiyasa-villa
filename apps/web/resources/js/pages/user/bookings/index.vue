<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { isSupportedLocale } from '@/i18n';
import {
    index as bookingsIndex,
    show as bookingsShow,
} from '@/routes/bookings';
import { dashboard } from '@/routes';
import type { BookingSummary } from '@/types';

const page = usePage();

const locale = computed(() =>
    isSupportedLocale(page.props.locale) ? page.props.locale : 'id',
);

defineProps<{
    bookings?: BookingSummary[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard({ locale: 'id' }),
            },
            {
                title: 'Bookings',
                href: bookingsIndex({ locale: 'id' }),
            },
        ],
    },
});
</script>

<template>
    <Head title="Bookings" />

    <main class="mx-auto max-w-7xl px-4 py-12">
        <h1 class="font-display text-display-sm">Bookings</h1>
        <p
            v-if="!bookings || bookings.length === 0"
            class="mt-4 text-body-sm text-body"
        >
            {{ $t('public.booking.confirmationEmpty') }}
        </p>
        <ul v-else class="mt-6 grid gap-4 md:grid-cols-3">
            <li
                v-for="booking in bookings"
                :key="booking.code"
                class="rounded-lg border border-hairline bg-surface-card p-4"
            >
                <Link
                    :href="bookingsShow({ locale, code: booking.code })"
                    class="font-medium text-primary hover:underline"
                >
                    {{ booking.code }}
                </Link>
            </li>
        </ul>
    </main>
</template>
