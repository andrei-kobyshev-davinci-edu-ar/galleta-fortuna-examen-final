<?php

namespace App\Http\Controllers;

use App\Models\Fortuna;
use Illuminate\Http\Request;

class FortunaController extends Controller
{
    public function obtenerFortuna(Request $request)
    {
        $fortuna = Fortuna::inRandomOrder()->first();
        
        if (!$fortuna) {
            return response()->json(['error' => 'No hay fortunas disponibles'], 404);
        }
        
        return response()->json(['fortuna' => $fortuna->mensaje]);
    }
}