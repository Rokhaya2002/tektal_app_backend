<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StopController extends Controller
{
    public function index()
    {
        $stops = DB::table('stops')
            ->join('lines', 'stops.line_id', '=', 'lines.id')
            ->select('stops.id', 'stops.name as stop_name', 'lines.name as line_name', 'stop_order')
            ->orderBy('line_name')
            ->orderBy('stop_order')
            ->get();

        return response()->json($stops);
    }

    public function search(Request $request)
    {
        $query = $request->query('query');

        if (!$query) {
            return response()->json([]);
        }

        $results = DB::table('stops')
            ->where('name', 'like', '%' . $query . '%')
            ->distinct()          
            ->orderBy('name')
            ->limit(10)
            ->pluck('name');

        return response()->json($results);
    }
}
