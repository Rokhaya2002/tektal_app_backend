<?php

namespace App\Http\Controllers;

use App\Models\Line;
use App\Models\Stop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminLineController extends Controller
{
    public function index()
    {
        $lines = Line::with('stops')->get();
        return response()->json($lines);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'departure' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'selectedStops' => 'required|array|min:1',
            'selectedStops.*' => 'exists:stops,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $line = Line::create([
            'name' => $request->name,
            'departure' => $request->departure,
            'destination' => $request->destination
        ]);

        $line->stops()->attach($request->selectedStops);

        return response()->json(['message' => 'Ligne créée avec succès', 'line' => $line->load('stops')], 201);
    }

    public function show($id)
    {
        $line = Line::with('stops')->find($id);

        if (!$line) {
            return response()->json(['message' => 'Ligne non trouvée'], 404);
        }

        return response()->json($line);
    }

    public function update(Request $request, $id)
    {
        $line = Line::find($id);

        if (!$line) {
            return response()->json(['message' => 'Ligne non trouvée'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'departure' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'selectedStops' => 'required|array|min:1',
            'selectedStops.*' => 'exists:stops,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $line->update([
            'name' => $request->name,
            'departure' => $request->departure,
            'destination' => $request->destination
        ]);

        $line->stops()->sync($request->selectedStops);

        return response()->json(['message' => 'Ligne modifiée avec succès', 'line' => $line->load('stops')]);
    }

    public function destroy($id)
    {
        $line = Line::find($id);

        if (!$line) {
            return response()->json(['message' => 'Ligne non trouvée'], 404);
        }

        $line->stops()->detach();
        $line->delete();

        return response()->json(['message' => 'Ligne supprimée avec succès']);
    }
}
