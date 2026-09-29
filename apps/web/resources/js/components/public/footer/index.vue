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
    <footer class="bg-primary text-on-primary">
        <div
            class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-16 md:flex-row md:items-center md:justify-between"
        >
            <div>
                <p class="font-medium">{{ $t('common.appName') }}</p>
                <p class="text-body-sm text-on-primary/80">
                    {{ $t('common.tagline') }}
                </p>
            </div>
            <nav
                class="flex flex-wrap gap-x-4 gap-y-2 text-sm"
                aria-label="Footer"
            >
                <Link
                    :href="home({ locale })"
                    class="text-on-primary/80 transition-colors hover:text-on-primary"
                >
                    {{ $t('nav.home') }}
                </Link>
                <Link
                    :href="cabinsIndex({ locale })"
                    class="text-on-primary/80 transition-colors hover:text-on-primary"
                >
                    {{ $t('nav.cabins') }}
                </Link>
                <Link
                    :href="about({ locale })"
                    class="text-on-primary/80 transition-colors hover:text-on-primary"
                >
                    {{ $t('nav.about') }}
                </Link>
                <Link
                    :href="contact({ locale })"
                    class="text-on-primary/80 transition-colors hover:text-on-primary"
                >
                    {{ $t('nav.contact') }}
                </Link>
            </nav>
        </div>
        <div class="border-t border-white/10 bg-primary-active">
            <p
                class="mx-auto max-w-7xl px-4 py-4 text-caption-sm text-muted-soft"
            >
                © {{ year }} {{ $t('common.appName') }}.
                {{ $t('public.footer.rights') }}
            </p>
        </div>
    </footer>
</template>
