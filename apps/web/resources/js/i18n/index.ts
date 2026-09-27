import { createI18n } from 'vue-i18n';
import en from './locales/en';
import id from './locales/id';

export const SUPPORTED_LOCALES = ['id', 'en'] as const;
export type SupportedLocale = (typeof SUPPORTED_LOCALES)[number];

export const DEFAULT_LOCALE: SupportedLocale = 'id';
export const FALLBACK_LOCALE: SupportedLocale = 'en';

type DeepStringRecord<T> = {
    [K in keyof T]: T[K] extends Record<string, unknown> ? DeepStringRecord<T[K]> : string;
};

export type MessageSchema = DeepStringRecord<typeof id>;

export const i18n = createI18n<[MessageSchema], SupportedLocale>({
    legacy: false,
    locale: DEFAULT_LOCALE,
    fallbackLocale: FALLBACK_LOCALE,
    messages: {
        id,
        en,
    },
});

export function setLocale(locale: string): void {
    if (isSupportedLocale(locale)) {
        const globalLocale = i18n.global.locale as unknown;
        if (typeof globalLocale === 'object' && globalLocale !== null && 'value' in globalLocale) {
            (globalLocale as { value: SupportedLocale }).value = locale;
        } else {
            (i18n.global.locale as unknown as SupportedLocale) = locale;
        }
    }
}

export function isSupportedLocale(locale: unknown): locale is SupportedLocale {
    return typeof locale === 'string' && SUPPORTED_LOCALES.includes(locale as SupportedLocale);
}

export default i18n;
