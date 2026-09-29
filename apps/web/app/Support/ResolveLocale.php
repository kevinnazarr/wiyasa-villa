<?php

namespace App\Support;

use App\Http\Middleware\SetLocale;
use Illuminate\Http\Request;

class ResolveLocale
{
    public static function forRedirect(?Request $request = null): string
    {
        $request ??= request();

        $locale = $request?->session()->get('locale');

        if (! is_string($locale) || ! in_array($locale, SetLocale::SUPPORTED_LOCALES, true)) {
            $intended = $request?->session()->get('url.intended');

            if (is_string($intended)) {
                $segment = explode('/', ltrim(parse_url($intended, PHP_URL_PATH) ?: '', '/'))[0] ?? '';

                if (in_array($segment, SetLocale::SUPPORTED_LOCALES, true)) {
                    $locale = $segment;
                }
            }
        }

        if (! is_string($locale) || ! in_array($locale, SetLocale::SUPPORTED_LOCALES, true)) {
            $locale = app()->getLocale();
        }

        if (! in_array($locale, SetLocale::SUPPORTED_LOCALES, true)) {
            $locale = SetLocale::DEFAULT_LOCALE;
        }

        return $locale;
    }

    public static function bookingsUrl(?Request $request = null): string
    {
        return route('bookings.index', ['locale' => self::forRedirect($request)], false);
    }
}
