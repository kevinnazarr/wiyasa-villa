<?php

namespace App\Http\Responses;

use App\Support\ResolveLocale;
use Illuminate\Http\Request;

/**
 * Shared post-authentication redirect target.
 *
 * Fortify POST routes are not locale-prefixed, so the locale is resolved
 * from session first, then the intended URL, then the app locale.
 */
class LocaleAwareBookingsRedirect
{
    public static function url(?Request $request = null): string
    {
        return ResolveLocale::bookingsUrl($request);
    }
}
