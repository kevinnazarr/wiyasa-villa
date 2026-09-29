<?php

namespace App\Http\Controllers\Public;

use App\Enums\CabinStatus;
use App\Http\Controllers\Controller;
use App\Models\Cabin;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CabinController extends Controller
{
    public function index(Request $request): Response
    {
        $locale = $request->route('locale') ?? app()->getLocale();

        $cabins = Cabin::query()
            ->where('status', CabinStatus::Active)
            ->with('translations')
            ->orderBy('name')
            ->get()
            ->map(fn (Cabin $cabin) => [
                'id' => $cabin->id,
                'name' => $cabin->nameFor($locale),
            ]);

        return Inertia::render('public/cabins/index', [
            'cabins' => $cabins,
        ]);
    }

    public function show(Request $request): Response
    {
        $locale = $request->route('locale') ?? app()->getLocale();
        $cabin = (string) $request->route('cabin');

        $model = Cabin::query()->with('translations')->where('slug', $cabin)->first();

        if ($model === null && is_numeric($cabin)) {
            $model = Cabin::query()->with('translations')->find((int) $cabin);
        }

        abort_unless($model !== null && $model->status === CabinStatus::Active, 404);

        return Inertia::render('public/cabins/show', [
            'cabin' => [
                'id' => $model->id,
                'name' => $model->nameFor($locale),
            ],
        ]);
    }
}
