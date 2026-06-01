<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\WeatherLog;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function store(Request $request, Site $site)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'condition' => 'required|in:rainy,sunny,windy,overcast,cloudy',
            'temperature' => 'nullable|numeric',
            'precipitation' => 'nullable|numeric',
        ]);

        $validated['site_id'] = $site->id;
        $log = WeatherLog::create($validated);
        return response()->json($log, 201);
    }
}