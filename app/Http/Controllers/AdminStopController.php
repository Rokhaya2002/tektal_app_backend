<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminStopController extends Controller
{
    public function index()
    {
        $stops = DB::table('stops')
            ->join('lines', 'stops.line_id', '=', 'lines.id')
            ->select('stops.id', 'stops.name', 'stops.line_id', 'stops.stop_order', 'lines.name as line_name')
            ->orderBy('lines.name')
            ->orderBy('stops.stop_order')
            ->get();

        return response()->json($stops);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'line_id' => 'required|integer|exists:lines,id',
            'stop_order' => 'required|integer|min:1',
        ]);

        $id = DB::table('stops')->insertGetId([
            'name' => $request->name,
            'line_id' => $request->line_id,
            'stop_order' => $request->stop_order,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $stop = DB::table('stops')
            ->join('lines', 'stops.line_id', '=', 'lines.id')
            ->where('stops.id', $id)
            ->select('stops.id', 'stops.name', 'stops.line_id', 'stops.stop_order', 'lines.name as line_name')
            ->first();

        return response()->json([
            'message' => 'Arrêt créé avec succès',
            'stop' => $stop
        ], 201);
    }

    public function show($id)
    {
        $stop = DB::table('stops')
            ->join('lines', 'stops.line_id', '=', 'lines.id')
            ->where('stops.id', $id)
            ->select('stops.id', 'stops.name', 'stops.line_id', 'stops.stop_order', 'lines.name as line_name')
            ->first();

        if (!$stop) {
            return response()->json(['message' => 'Arrêt non trouvé'], 404);
        }

        return response()->json($stop);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'line_id' => 'required|integer|exists:lines,id',
            'stop_order' => 'required|integer|min:1',
        ]);

        $stop = DB::table('stops')->where('id', $id)->first();

        if (!$stop) {
            return response()->json(['message' => 'Arrêt non trouvé'], 404);
        }

        DB::table('stops')->where('id', $id)->update([
            'name' => $request->name,
            'line_id' => $request->line_id,
            'stop_order' => $request->stop_order,
            'updated_at' => now(),
        ]);

        $updatedStop = DB::table('stops')
            ->join('lines', 'stops.line_id', '=', 'lines.id')
            ->where('stops.id', $id)
            ->select('stops.id', 'stops.name', 'stops.line_id', 'stops.stop_order', 'lines.name as line_name')
            ->first();

        return response()->json([
            'message' => 'Arrêt mis à jour avec succès',
            'stop' => $updatedStop
        ]);
    }

    public function destroy($id)
    {
        $stop = DB::table('stops')->where('id', $id)->first();

        if (!$stop) {
            return response()->json(['message' => 'Arrêt non trouvé'], 404);
        }

        DB::table('stops')->where('id', $id)->delete();

        return response()->json([
            'message' => 'Arrêt supprimé avec succès'
        ]);
    }
}
