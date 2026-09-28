<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import type { ReservationSummary } from '@/types';

defineProps<{
    code?: string | null;
    reservation?: ReservationSummary | null;
}>();
</script>

<template>
    <Head :title="$t('public.booking.confirmationTitle')" />

    <main class="mx-auto max-w-7xl px-4 py-12">
        <h1 class="font-display text-display-sm">
            {{ $t('public.booking.confirmationTitle') }}
        </h1>
        <p v-if="!reservation" class="mt-4 text-body-sm text-body">
            {{ code ? `${code}` : $t('public.booking.confirmationEmpty') }}
        </p>
        <div
            v-else
            class="mt-6 rounded-lg border border-hairline bg-surface-card p-4"
        >
            {{ reservation.code }}
        </div>
    </main>
</template>
