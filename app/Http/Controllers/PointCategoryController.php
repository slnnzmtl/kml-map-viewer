<?php

namespace App\Http\Controllers;

use App\Models\PointCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PointCategoryController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Dashboard/Categories', [
            'categories' => PointCategory::all()
        ]);
    }

    public function add(Request $request): RedirectResponse
    {
        $pointCategory = PointCategory::create($request->validate([
            'title' => ['required', 'max:20', 'unique:point_categories'],
            'image' => []
        ]));

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $pointCategory->addMedia($image[0])
                ->preservingOriginal()
                ->toMediaCollection();
        }

        return to_route('dashboard.categories');
    }

    public function update(Request $request, PointCategory $category)
    {
        $category->update($request->validate([
            'title' => ['required', 'max:20'],
            'image' => []
        ]));

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            if ($category->getFirstMedia())
                $category->deleteMedia($category->getFirstMedia()->getKey());
            $category->addMedia($image[0])
                ->preservingOriginal()
                ->toMediaCollection();
        }

        return to_route('dashboard.categories');
    }

    public function destroy(Request $request, PointCategory $category): RedirectResponse
    {
        if ($category->getFirstMedia())
            $category->deleteMedia($category->getFirstMedia()->getKey());
        $category->delete();

        return to_route('dashboard.categories');
    }
}
