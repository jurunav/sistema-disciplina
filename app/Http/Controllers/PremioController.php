<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Premio;

class PremioController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $premios = Premio::select('premios.id','premios.nombre','premios.puntaje','premios.condicion')
            ->orderBy('premios.id', 'desc')->paginate(10);
        }
        else{

            $premios = Premio::select('premios.id','premios.nombre','premios.puntaje','premios.condicion')
            ->where('premios.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('premios.id', 'desc')->paginate(10);

        }
        

        return [
            'pagination' => [
                'total'        => $premios->total(),
                'current_page' => $premios->currentPage(),
                'per_page'     => $premios->perPage(),
                'last_page'    => $premios->lastPage(),
                'from'         => $premios->firstItem(),
                'to'           => $premios->lastItem(),
            ],
            'premios' => $premios
        ];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $premio = new Premio();
        $premio->nombre = $request->nombre;
        $premio->puntaje = $request->puntaje;
        $premio->condicion = '1';
        $premio->save();
    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $premio = Premio::findOrFail($request->id);
        $premio->nombre = $request->nombre;
        $premio->puntaje = $request->puntaje;
        $premio->condicion = '1';
        $premio->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $premio = Premio::findOrFail($request->id);
        $premio->condicion = '0';
        $premio->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $premio = Premio::findOrFail($request->id);
        $premio->condicion = '1';
        $premio->save();
    }
}
