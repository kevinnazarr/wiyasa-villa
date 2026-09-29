<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

type ReservationSummary = {
    code: string;
    status?: string | null;
};

defineProps<{
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
            {{ $t('public.booking.confirmationEmpty') }}
        </p>
        <div
            v-else
            class="mt-6 rounded-lg border border-hairline bg-surface-card p-4"
        >
            <p>{{ reservation.code }}</p>
            <p v-if="reservation.status" class="mt-2 text-body-sm text-body">
                {{ reservation.status }}
            </p>
        </div>
    </main>
</template>
