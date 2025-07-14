<?php

namespace App\Http\Controllers;

use App\Models\Stop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminStopController extends Controller
{
    public function index()
    {
        $stops = Stop::all();
        return response()->json($stops);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:stops,name'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $stop = Stop::create([
            'name' => $request->name
        ]);

        return response()->json(['message' => 'Arrêt créé avec succès', 'stop' => $stop], 201);
    }

    public function show($id)
    {
        $stop = Stop::find($id);

        if (!$stop) {
            return response()->json(['message' => 'Arrêt non trouvé'], 404);
        }

        return response()->json($stop);
    }

    public function update(Request $request, $id)
    {
        $stop = Stop::find($id);

        if (!$stop) {
            return response()->json(['message' => 'Arrêt non trouvé'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:stops,name,' . $id
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $stop->update([
            'name' => $request->name
        ]);

        return response()->json(['message' => 'Arrêt modifié avec succès', 'stop' => $stop]);
    }

    public function destroy($id)
    {
        $stop = Stop::find($id);

        if (!$stop) {
            return response()->json(['message' => 'Arrêt non trouvé'], 404);
        }

        // Vérifier si l'arrêt est utilisé par des lignes
        if ($stop->lines()->count() > 0) {
            return response()->json(['message' => 'Impossible de supprimer cet arrêt car il est utilisé par des lignes'], 422);
        }

        $stop->delete();

        return response()->json(['message' => 'Arrêt supprimé avec succès']);
    }
}
