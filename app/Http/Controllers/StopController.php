<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stop;

class StopController extends Controller
{
    public function index()
    {
        $stops = Stop::with('lines')->get();

        $formattedStops = $stops->map(function ($stop) {
            return [
                'id' => $stop->id,
                'name' => $stop->name,
                'lines' => $stop->lines->map(function ($line) {
                    return [
                        'id' => $line->id,
                        'name' => $line->name,
                        'order' => $line->pivot->order
                    ];
                })
            ];
        });

        return response()->json($formattedStops);
    }
}
