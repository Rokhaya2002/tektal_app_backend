<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminLineController extends Controller
{
    public function index()
    {
        $lines = DB::table('lines')->get();
        return response()->json($lines);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'departure' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
        ]);

        $id = DB::table('lines')->insertGetId([
            'name' => $request->name,
            'departure' => $request->departure,
            'destination' => $request->destination,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $line = DB::table('lines')->where('id', $id)->first();

        return response()->json([
            'message' => 'Ligne créée avec succès',
            'line' => $line
        ], 201);
    }

    public function show($id)
    {
        $line = DB::table('lines')->where('id', $id)->first();

        if (!$line) {
            return response()->json(['message' => 'Ligne non trouvée'], 404);
        }

        return response()->json($line);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'departure' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
        ]);

        $line = DB::table('lines')->where('id', $id)->first();

        if (!$line) {
            return response()->json(['message' => 'Ligne non trouvée'], 404);
        }

        DB::table('lines')->where('id', $id)->update([
            'name' => $request->name,
            'departure' => $request->departure,
            'destination' => $request->destination,
            'updated_at' => now(),
        ]);

        $updatedLine = DB::table('lines')->where('id', $id)->first();

        return response()->json([
            'message' => 'Ligne mise à jour avec succès',
            'line' => $updatedLine
        ]);
    }

    public function destroy($id)
    {
        $line = DB::table('lines')->where('id', $id)->first();

        if (!$line) {
            return response()->json(['message' => 'Ligne non trouvée'], 404);
        }

        DB::table('lines')->where('id', $id)->delete();

        return response()->json([
            'message' => 'Ligne supprimée avec succès'
        ]);
    }
}
