<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { index as bookingsIndex } from '@/routes/bookings';

type BookingDetail = {
    id: number | string;
    code?: string | null;
    status?: string | null;
};

defineProps<{
    booking?: BookingDetail | null;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'My Bookings',
                href: bookingsIndex(),
            },
        ],
    },
});
</script>

<template>
    <Head :title="$t('nav.myBookings')" />

    <main class="mx-auto max-w-7xl px-4 py-12">
        <h1 class="font-display text-display-sm">
            {{ $t('nav.myBookings') }}
        </h1>
        <div
            v-if="booking"
            class="mt-6 rounded-lg border border-hairline bg-surface-card p-4"
        >
            <p>{{ booking.id }}</p>
            <p v-if="booking.code" class="mt-2 text-body-sm text-body">
                {{ booking.code }}
            </p>
            <p v-if="booking.status" class="mt-2 text-body-sm text-body">
                {{ booking.status }}
            </p>
        </div>
    </main>
</template>
