<?php

namespace App\Http\Controllers;

use App\Models\Fortuna;
use App\Exceptions\FraseDuplicadaException;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function agregarFrase(Request $request)
    {
        $request->validate([
            'mensaje' => 'required|string'
        ]);
        
        // Verificar duplicados
        $existe = Fortuna::whereRaw('LOWER(mensaje) = LOWER(?)', [$request->mensaje])->exists();
        
        if ($existe) {
            throw new FraseDuplicadaException();
        }
        
        $fortuna = Fortuna::create([
            'mensaje' => $request->mensaje,
            'agregado_por' => $request->get('user_id')
        ]);
        
        return response()->json([
            'mensaje' => 'Frase agregada exitosamente',
            'id' => $fortuna->id
        ], 201);
    }
    
    public function listarFrases()
    {
        $frases = Fortuna::with('usuario:id,nombre')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function($frase) {
                return [
                    'id' => $frase->id,
                    'mensaje' => $frase->mensaje,
                    'agregado_por' => $frase->usuario ? $frase->usuario->nombre : 'Sistema',
                    'fecha_creacion' => $frase->fecha_creacion
                ];
            });
            
        return response()->json(['frases' => $frases]);
    }
    
    public function eliminarFrase($id)
    {
        $fortuna = Fortuna::where('id', $id)
            ->whereNotNull('agregado_por')
            ->first();
            
        if (!$fortuna) {
            return response()->json(['error' => 'Frase no encontrada o no se puede eliminar'], 404);
        }
        
        $fortuna->delete();
        
        return response()->json(['mensaje' => 'Frase eliminada exitosamente']);
    }
}