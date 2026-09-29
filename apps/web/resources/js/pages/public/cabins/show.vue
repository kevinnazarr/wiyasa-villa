<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { isSupportedLocale } from '@/i18n';
import { index as bookingIndex } from '@/routes/booking';

type CabinDetail = {
    id: number | string;
    name: string;
};

defineProps<{
    cabin?: CabinDetail | null;
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
        <p v-if="!cabin" class="mt-4 text-body-sm text-body">
            {{ $t('public.cabins.detailEmpty') }}
        </p>
        <div
            v-else
            class="mt-6 rounded-lg border border-hairline bg-surface-card p-4"
        >
            {{ cabin.name }}
            <div class="mt-4">
                <Link
                    :href="
                        bookingIndex(
                            { locale },
                            { query: { cabin_id: cabin.id } },
                        )
                    "
                >
                    {{ $t('common.bookNow') }}
                </Link>
            </div>
        </div>
    </main>
</template>
