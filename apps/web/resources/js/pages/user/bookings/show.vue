<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { index as bookingsIndex } from '@/routes/bookings';
import { dashboard } from '@/routes';
import type { BookingSummary } from '@/types';

defineProps<{
    code?: string | null;
    booking?: BookingSummary | null;
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
    <Head title="Booking Detail" />

    <main class="mx-auto max-w-7xl px-4 py-12">
        <h1 class="font-display text-display-sm">Booking Detail</h1>
        <p v-if="!booking" class="mt-4 text-body-sm text-body">
            {{ code }}
        </p>
        <div
            v-else
            class="mt-6 rounded-lg border border-hairline bg-surface-card p-4"
        >
            {{ booking.code }}
        </div>
    </main>
</template>
