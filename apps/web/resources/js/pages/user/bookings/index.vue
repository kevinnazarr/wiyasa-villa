<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { isSupportedLocale } from '@/i18n';
import { index as bookingsIndex, show as bookingShow } from '@/routes/bookings';

type BookingSummary = {
    id: number | string;
    code: string;
    status: string;
};

defineProps<{
    bookings?: BookingSummary[];
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

const page = usePage();

const locale = computed(() =>
    isSupportedLocale(page.props.locale) ? page.props.locale : 'id',
);
</script>

<template>
    <Head :title="$t('nav.myBookings')" />

    <main class="mx-auto max-w-7xl px-4 py-12">
        <h1 class="font-display text-display-sm">
            {{ $t('nav.myBookings') }}
        </h1>
        <ul
            v-if="bookings && bookings.length > 0"
            class="mt-6 grid gap-4 md:grid-cols-2"
        >
            <li
                v-for="booking in bookings"
                :key="booking.id"
                class="rounded-lg border border-hairline bg-surface-card p-4"
            >
                <Link :href="bookingShow({ locale, booking: booking.id })">
                    {{ booking.code }} — {{ booking.status }}
                </Link>
            </li>
        </ul>
    </main>
</template>
