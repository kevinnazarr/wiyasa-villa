<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Menu, X } from '@lucide/vue';
import { computed, ref } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import LanguageSwitcher from '@/components/public/navbar/language-switcher.vue';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { isSupportedLocale } from '@/i18n';
import { about, contact, home, login } from '@/routes';
import { index as bookingIndex } from '@/routes/booking';
import { index as cabinsIndex } from '@/routes/cabins';

const page = usePage();
const { isCurrentUrl, isCurrentOrParentUrl } = useCurrentUrl();
const menuOpen = ref(false);

const locale = computed(() =>
    isSupportedLocale(page.props.locale) ? page.props.locale : 'id',
);

const linkClasses =
    'rounded-sm px-3 py-2 text-nav-link text-muted-foreground transition-colors hover:text-foreground';
const activeLinkClasses = 'text-primary';
</script>

<template>
    <header class="sticky top-0 z-40 border-b border-hairline bg-surface-card">
        <div class="mx-auto flex h-20 max-w-7xl items-center gap-2 px-4">
            <Link :href="home({ locale })" class="flex items-center gap-x-2">
                <AppLogo />
            </Link>

            <nav
                class="ml-6 hidden items-center gap-1 md:flex"
                aria-label="Primary"
            >
                <Link
                    :href="home({ locale })"
                    :class="[
                        linkClasses,
                        isCurrentUrl(home({ locale })) && activeLinkClasses,
                    ]"
                >
                    {{ $t('nav.home') }}
                </Link>
                <Link
                    :href="cabinsIndex({ locale })"
                    :class="[
                        linkClasses,
                        isCurrentOrParentUrl(cabinsIndex({ locale })) &&
                            activeLinkClasses,
                    ]"
                >
                    {{ $t('nav.cabins') }}
                </Link>
                <Link
                    :href="about({ locale })"
                    :class="[
                        linkClasses,
                        isCurrentUrl(about({ locale })) && activeLinkClasses,
                    ]"
                >
                    {{ $t('nav.about') }}
                </Link>
                <Link
                    :href="contact({ locale })"
                    :class="[
                        linkClasses,
                        isCurrentUrl(contact({ locale })) && activeLinkClasses,
                    ]"
                >
                    {{ $t('nav.contact') }}
                </Link>
            </nav>

            <div class="ml-auto flex items-center gap-2">
                <LanguageSwitcher />
                <Link
                    :href="login()"
                    class="hidden text-sm font-medium text-muted-foreground transition-colors hover:text-foreground md:inline"
                >
                    {{ $t('nav.login') }}
                </Link>
                <Link
                    :href="bookingIndex({ locale })"
                    class="inline-flex h-12 items-center rounded-sm bg-primary px-6 text-button-md text-on-primary transition-colors hover:bg-primary-active"
                >
                    {{ $t('common.bookNow') }}
                </Link>
                <button
                    type="button"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-md text-muted-foreground hover:text-foreground md:hidden"
                    :aria-expanded="menuOpen"
                    aria-label="Menu"
                    @click="menuOpen = !menuOpen"
                >
                    <X v-if="menuOpen" class="h-5 w-5" />
                    <Menu v-else class="h-5 w-5" />
                </button>
            </div>
        </div>

        <nav
            v-if="menuOpen"
            class="border-t border-hairline px-4 py-2 md:hidden"
            aria-label="Mobile"
        >
            <Link
                :href="home({ locale })"
                :class="[
                    'block ' + linkClasses,
                    isCurrentUrl(home({ locale })) && activeLinkClasses,
                ]"
                @click="menuOpen = false"
            >
                {{ $t('nav.home') }}
            </Link>
            <Link
                :href="cabinsIndex({ locale })"
                :class="[
                    'block ' + linkClasses,
                    isCurrentOrParentUrl(cabinsIndex({ locale })) &&
                        activeLinkClasses,
                ]"
                @click="menuOpen = false"
            >
                {{ $t('nav.cabins') }}
            </Link>
            <Link
                :href="about({ locale })"
                :class="[
                    'block ' + linkClasses,
                    isCurrentUrl(about({ locale })) && activeLinkClasses,
                ]"
                @click="menuOpen = false"
            >
                {{ $t('nav.about') }}
            </Link>
            <Link
                :href="contact({ locale })"
                :class="[
                    'block ' + linkClasses,
                    isCurrentUrl(contact({ locale })) && activeLinkClasses,
                ]"
                @click="menuOpen = false"
            >
                {{ $t('nav.contact') }}
            </Link>
            <Link
                :href="login()"
                :class="['block ' + linkClasses]"
                @click="menuOpen = false"
            >
                {{ $t('nav.login') }}
            </Link>
        </nav>
    </header>
</template>
