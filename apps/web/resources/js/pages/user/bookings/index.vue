<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { index as bookingsIndex } from '@/routes/bookings';

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
    </main>
</template>
