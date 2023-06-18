<?php

namespace App\Http\Controllers;

use App\Http\Requests\PointRequest;
use App\Http\Requests\PointUpdateRequest;
use App\Models\Affiliate;
use App\Models\Forestry;
use App\Models\Point;
use App\Models\PointCategory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PointController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Dashboard/Points',
            [
                'points' => Point::with(['category', 'forestry'])->get(),
                'categories' => PointCategory::with('image')->get(),
                'affiliates' => Affiliate::with('forestry')->get()
            ]);
    }

    public function add(PointRequest $request): Application|\Illuminate\Routing\Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $point = Point::make($request->all());

        $point->category()->associate(PointCategory::find($request->get('category_id')));
        $point->forestry()->associate(Forestry::find($request->get('forestry_id')));
        $point->save();

        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $file) {
                $point->addMedia($file)
                    ->preservingOriginal()
                    ->toMediaCollection();
            }
        }

        return redirect(route('dashboard.points'));
    }

    public function update(PointUpdateRequest $request, Point $point): RedirectResponse
    {
        $point->update($request->all());

        $point->category()->associate(PointCategory::find($request->get('category_id')));
        $point->forestry()->associate(Forestry::find($request->get('forestry_id')));
        $point->save();

        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $file) {
                $point->addMedia($file)
                    ->preservingOriginal()
                    ->toMediaCollection();
            }
        }

        return to_route('dashboard.points');
    }

    public function destroy(Point $point)
    {
        $point->delete();

        return redirect(route('dashboard.points'));
    }

    public function removePointMedia(Point $point, Media $media)
    {
        $point->deleteMedia($media);

        return to_route('dashboard.points');
    }

}
