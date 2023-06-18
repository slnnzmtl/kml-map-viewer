<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use App\Models\Forestry;
use Illuminate\Http\Request;

class ForestryController extends Controller
{
    public function getByFilia(string $filiaCode)
    {
        $affiliate = Affiliate::firstOrFail()->where('code', $filiaCode)->first();
        $forestry = Forestry::where('filia_id', $affiliate->id)->get();

        return response()->json(json_decode($forestry));
    }
}
