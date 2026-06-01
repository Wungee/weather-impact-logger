<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\DelayLog;
use App\Models\WeatherLog;
use Illuminate\Http\Request;

class DelayController extends Controller
{
    public function store(Request $request, Site $site)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'hours_delayed' => 'required|numeric|min:0',
            'reason' => 'nullable|in:weather,supply,labor,equipment',
            'notes' => 'nullable|string',
        ]);

        $validated['site_id'] = $site->id;
        $log = DelayLog::create($validated);
        return response()->json($log, 201);
    }

    public function correlation(Site $site)
    {
        $weatherLogs = WeatherLog::where('site_id', $site->id)->get();
        $rainyDays = $weatherLogs->where('condition', 'rainy')->count();
        $sunnyDays = $weatherLogs->where('condition', 'sunny')->count();

        $rainyDates = $weatherLogs->where('condition', 'rainy')->pluck('date')->toArray();
        $sunnyDates = $weatherLogs->where('condition', 'sunny')->pluck('date')->toArray();

        $rainyDaysWithDelays = DelayLog::where('site_id', $site->id)
            ->whereIn('date', $rainyDates)
            ->count();

        $sunnyDaysWithDelays = DelayLog::where('site_id', $site->id)
            ->whereIn('date', $sunnyDates)
            ->count();

        return response()->json([
            'rainy_days_total' => $rainyDays,
            'rainy_days_with_delays' => $rainyDaysWithDelays,
            'rainy_days_no_delays' => $rainyDays - $rainyDaysWithDelays,
            'rainy_correlation_percentage' => $rainyDays > 0
                ? round(($rainyDaysWithDelays / $rainyDays) * 100, 1)
                : 0,
            'sunny_days_total' => $sunnyDays,
            'sunny_days_with_delays' => $sunnyDaysWithDelays,
        ]);
    }
}