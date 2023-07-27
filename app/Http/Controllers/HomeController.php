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
        $path = 'public/KML/' . $forestry->id;
        $files = Storage::files($path);
        $filenames = [];

        for ($i = 0; $i < count($files); $i++) {
            $file = $files[$i];
            $pathinfo = pathinfo($file, PATHINFO_EXTENSION);
            if ($pathinfo === ltrim('.kml', '.') || $pathinfo === ltrim('.kmz', '.')) {
                $filenames[] = 'KML/' . $forestry->id  . '/' . basename($file);
            }
        }

        return Inertia::render('GoogleMap', [
            'activeAffiliate' => $forestry->affiliate,
            'activeForestry' => $forestry,
            'points' => $points,
            'affiliates' => $affiliates,
            'categories' => $categories,
            'kmlList' => $filenames,
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

    public function file(Request $request)
    {
        $forestry = $request->get('forestry');
        $path = 'public/KML/' . $forestry;
        $files = Storage::files($path);

        dump($files);

        return response()->json($files);
    }
}


