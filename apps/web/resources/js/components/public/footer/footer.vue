<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { isSupportedLocale } from '@/i18n';
import { about, contact, home } from '@/routes';
import { index as cabinsIndex } from '@/routes/cabins';

const page = usePage();

const locale = computed(() =>
    isSupportedLocale(page.props.locale) ? page.props.locale : 'id',
);

const year = new Date().getFullYear();
</script>

<template>
    <footer class="border-t border-border bg-background">
        <div
            class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-8 md:flex-row md:items-center md:justify-between"
        >
            <div>
                <p class="font-medium">{{ $t('common.appName') }}</p>
                <p class="text-sm text-muted-foreground">
                    {{ $t('common.tagline') }}
                </p>
            </div>
            <nav
                class="flex flex-wrap gap-x-4 gap-y-2 text-sm"
                aria-label="Footer"
            >
                <Link
                    :href="home({ locale })"
                    class="text-muted-foreground transition-colors hover:text-foreground"
                >
                    {{ $t('nav.home') }}
                </Link>
                <Link
                    :href="cabinsIndex({ locale })"
                    class="text-muted-foreground transition-colors hover:text-foreground"
                >
                    {{ $t('nav.cabins') }}
                </Link>
                <Link
                    :href="about({ locale })"
                    class="text-muted-foreground transition-colors hover:text-foreground"
                >
                    {{ $t('nav.about') }}
                </Link>
                <Link
                    :href="contact({ locale })"
                    class="text-muted-foreground transition-colors hover:text-foreground"
                >
                    {{ $t('nav.contact') }}
                </Link>
            </nav>
        </div>
        <div class="border-t border-border">
            <p
                class="mx-auto max-w-7xl px-4 py-4 text-xs text-muted-foreground"
            >
                © {{ year }} {{ $t('common.appName') }}.
                {{ $t('public.footer.rights') }}
            </p>
        </div>
    </footer>
</template>
