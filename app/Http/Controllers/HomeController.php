<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use App\Models\Forestry;
use App\Models\Point;
use App\Models\PointCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HomeController extends Controller
{
    public function index(): Response
    {
        $affiliates = Affiliate::with('forestry.points')->get();

        return Inertia::render('Home', [
            'affiliates' => $affiliates
        ]);
    }

    public function map(Forestry $forestry): Response
    {
        $points = Point::where('forestry_id', $forestry->id)->with(['category', 'category.image'])->get();
        $affiliates = Affiliate::with('forestry')->get();
        $categories = PointCategory::all();


        return Inertia::render('GoogleMap', [
            'activeAffiliate' => $forestry->affiliate,
            'activeForestry' => $forestry,
//            'forestry' => $forestry,
            'points' => $points,
            'affiliates' => $affiliates,
            'categories' => $categories
        ]);

    }

    public function list(Forestry $forestry): Response
    {
        $affiliates = Affiliate::with('forestry')->get();
        $points = Point::where('forestry_id', $forestry->id)->get();
        $categories = PointCategory::all();

        return Inertia::render('List', [
            'activeAffiliate' => $forestry->affiliate,
            'activeForestry' => $forestry,
//            'forestry' => $forestry,
            'points' => $points,
            'affiliates' => $affiliates,
            'categories' => $categories
        ]);
    }

    public function file(Request $request): StreamedResponse
    {
        return Storage::download('public/' . $request->get('file'), 'test.kml', [
                'Content-Type' => 'application/vnd.google-earth.kml+xml',
//                'Charset' => 'utf-8'
            ]
        );
    }
}


