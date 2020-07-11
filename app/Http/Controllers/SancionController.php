<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sancion;

class SancionController extends Controller
{   
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $sanciones = Sancion::orderBy('id', 'desc')->paginate(10);
        }
        else{
            $sanciones = Sancion::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(10);
        }
        

        return [
            'pagination' => [
                'total'        => $sanciones->total(),
                'current_page' => $sanciones->currentPage(),
                'per_page'     => $sanciones->perPage(),
                'last_page'    => $sanciones->lastPage(),
                'from'         => $sanciones->firstItem(),
                'to'           => $sanciones->lastItem(),
            ],
            'sanciones' => $sanciones
        ];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $sancion = new Sancion();
        $sancion->nombre = $request->nombre;
        $sancion->puntaje = $request->puntaje;
        $sancion->puntaje_dia = $request->puntaje_dia;
        $sancion->categoria = $request->categoria;
        $sancion->articulo = $request->articulo;
        $sancion->grupo = $request->grupo;
        $sancion->inciso = $request->inciso;
        $sancion->condicion = '1';
        $sancion->save();
    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $sancion = Sancion::findOrFail($request->id);
        $sancion->nombre = $request->nombre;
        $sancion->puntaje = $request->puntaje;
        $sancion->puntaje_dia = $request->puntaje_dia;
        $sancion->categoria = $request->categoria;
        $sancion->articulo = $request->articulo;
        $sancion->grupo = $request->grupo;
        $sancion->inciso = $request->inciso;
        $sancion->condicion = '1';
        $sancion->save();
    }

    public function desactivar(Request $request)
    { 
        if (!$request->ajax()) return redirect('/');
        $sancion = Sancion::findOrFail($request->id);
        $sancion->condicion = '0';
        $sancion->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $sancion = Sancion::findOrFail($request->id);
        $sancion->condicion = '1';
        $sancion->save();
    }
}