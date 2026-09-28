<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { show as profileShow } from '@/routes/profile';
import { dashboard } from '@/routes';

defineProps<{
    user?: { name: string; email: string } | null;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard({ locale: 'id' }),
            },
            {
                title: 'Profile',
                href: profileShow({ locale: 'id' }),
            },
        ],
    },
});
</script>

<template>
    <Head title="Profile" />

    <main class="mx-auto max-w-7xl px-4 py-12">
        <h1 class="font-display text-display-sm">Profile</h1>
        <p v-if="!user" class="mt-4 text-body-sm text-body">
            {{ $t('public.about.empty') }}
        </p>
        <div
            v-else
            class="mt-6 rounded-lg border border-hairline bg-surface-card p-4"
        >
            {{ user.name }}
        </div>
    </main>
</template>
