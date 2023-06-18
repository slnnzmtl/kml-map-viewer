<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use Inertia\Inertia;
use Inertia\Response;

class AffiliateController extends Controller
{
    public function index(): Response
    {
        $affiliates = Affiliate::with('forestry')->get();

        return Inertia::render('Dashboard/Affiliates', [
            'affiliates' => $affiliates
        ]);
    }
}
