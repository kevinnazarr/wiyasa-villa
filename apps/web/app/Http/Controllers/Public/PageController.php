<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    /**
     * Render the public home page.
     */
    public function home(): Response
    {
        return Inertia::render('public/home/index');
    }

    /**
     * Render the public about page.
     */
    public function about(): Response
    {
        return Inertia::render('public/about/index');
    }

    /**
     * Render the public contact page.
     */
    public function contact(): Response
    {
        return Inertia::render('public/contact/index');
    }
}
