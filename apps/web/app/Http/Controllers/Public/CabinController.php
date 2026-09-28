<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\CabinResource;
use App\Models\Cabin;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CabinController extends Controller
{
    /**
     * List active cabins for the public catalog.
     */
    public function index(Request $request): Response
    {
        $cabins = Cabin::query()
            ->where('status', 'ACTIVE')
            ->with('translations')
            ->orderBy('code')
            ->get();

        return Inertia::render('public/cabins/index', [
            'cabins' => CabinResource::collection($cabins)->resolve($request),
        ]);
    }

    /**
     * Show a single cabin resolved by its canonical slug.
     *
     * The slug is looked up manually because the optional
     * {locale?} prefix shifts positional controller arguments,
     * which breaks implicit route model binding.
     */
    public function show(Request $request): Response
    {
        $slug = $request->route('cabin');

        $cabin = is_string($slug)
            ? Cabin::query()->where('slug', $slug)->with('translations')->first()
            : null;

        if (! $cabin instanceof Cabin) {
            abort(404);
        }

        return Inertia::render('public/cabins/show', [
            'cabin' => (new CabinResource($cabin))->resolve($request),
        ]);
    }
}
