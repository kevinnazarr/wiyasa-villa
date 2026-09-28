import { createInertiaApp, router } from '@inertiajs/vue3';
import type { DefineComponent } from 'vue';
import { initializeTheme } from '@/composables/useAppearance';
import i18n, { setLocale } from '@/i18n';
import AdminAreaLayout from '@/layouts/admin.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import PublicAreaLayout from '@/layouts/public.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import UserAreaLayout from '@/layouts/user.vue';
import { initializeFlashToast } from '@/lib/flashToast';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

router.on('navigate', (event) => {
    const pageLocale = event.detail.page.props.locale;
    if (typeof pageLocale === 'string') {
        setLocale(pageLocale);
    }
});

void createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => {
        const pages = import.meta.glob<DefineComponent>('./pages/**/*.vue', {
            eager: true,
        });
        const page = pages[`./pages/${name}.vue`];
        if (!page) {
            throw new Error(`Unknown Inertia page: ${name}`);
        }
        return page;
    },
    layout: (name) => {
        switch (true) {
            case name === 'public/home/index':
                return PublicAreaLayout;
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('settings/'):
                return [AppLayout, SettingsLayout];
            case name.startsWith('admin/'):
                return AdminAreaLayout;
            case name.startsWith('user/'):
                return UserAreaLayout;
            default:
                return AppLayout;
        }
    },
    withApp: (app) => {
        app.use(i18n);
        app.directive('focus', {
            mounted: (el: HTMLElement, shouldFocus) => {
                if (shouldFocus.value !== false) {
                    el.focus();
                }
            },
        });
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();

// This will listen for flash toast data from the server...
initializeFlashToast();
