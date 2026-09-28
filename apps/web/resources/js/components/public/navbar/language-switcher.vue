<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { SUPPORTED_LOCALES, isSupportedLocale } from '@/i18n';

const page = usePage();

const currentLocale = computed(() =>
    isSupportedLocale(page.props.locale) ? page.props.locale : 'id',
);

function switchedUrl(locale: string): string {
    const url = new URL(
        page.url,
        typeof window !== 'undefined'
            ? window.location.origin
            : 'http://localhost',
    );
    const segments = url.pathname.split('/').filter(Boolean);
    if (segments.length > 0 && isSupportedLocale(segments[0])) {
        segments[0] = locale;
    } else {
        segments.unshift(locale);
    }
    return `/${segments.join('/')}${url.search}`;
}
</script>

<template>
    <div class="flex items-center gap-1" role="group" aria-label="Language">
        <a
            v-for="locale in SUPPORTED_LOCALES"
            :key="locale"
            :href="switchedUrl(locale)"
            :aria-current="locale === currentLocale ? 'true' : undefined"
            :class="[
                'text-micro rounded-sm px-2 py-1 uppercase transition-colors',
                locale === currentLocale
                    ? 'bg-primary text-button-md text-on-primary'
                    : 'text-nav-link text-muted-foreground hover:text-foreground',
            ]"
        >
            {{ locale }}
        </a>
    </div>
</template>
