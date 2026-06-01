<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        return response()->json(Site::all());
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string',
        'location' => 'required|string',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
    ]);

    $site = Site::create($validated);
    return response()->json($site, 201);
}

    public function show(Site $site)
    {
        return response()->json($site->load(['weatherLogs', 'delayLogs']));
    }

    public function update(Request $request, Site $site)
    {
        $site->update($request->all());
        return response()->json($site);
    }

    public function destroy(Site $site)
    {
        $site->delete();
        return response()->json(null, 204);
    }
}