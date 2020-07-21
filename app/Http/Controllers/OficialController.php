<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Persona;

class OficialController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $personas = Persona::orderBy('id', 'desc')->paginate(10);
        }
        else{
            $personas = Persona::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(10);
        }
        

        return [
            'pagination' => [
                'total'        => $personas->total(),
                'current_page' => $personas->currentPage(),
                'per_page'     => $personas->perPage(),
                'last_page'    => $personas->lastPage(),
                'from'         => $personas->firstItem(),
                'to'           => $personas->lastItem(),
            ],
            'personas' => $personas
        ];
    }

    public function selectOficial(Request $request){
        if (!$request->ajax()) return redirect('/');
        
        $oficiales = [];
        $filtro = $request->filtro;
        if (!empty($filtro)) {
            $oficiales = Persona::where('nombre', 'like', '%'. $filtro . '%')
            ->select('id','nombre','grado')
            ->orderBy('nombre', 'asc')->get();
        }
        return ['oficiales' => $oficiales];
    }

    

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $persona = new Persona();
        $persona->grado = $request->grado;
        $persona->nombre = $request->nombre;
        $persona->ci = $request->ci;
        $persona->cm = $request->cm;
        $persona->domicilio = $request->domicilio;
        $persona->celular = $request->celular;
        $persona->email = $request->email;
        $persona->condicion = '1';
        $persona->save();
    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $persona = Persona::findOrFail($request->id);
        $persona->grado = $request->grado;
        $persona->nombre = $request->nombre;
        $persona->ci = $request->ci;
        $persona->cm = $request->cm;
        $persona->domicilio = $request->domicilio;
        $persona->celular = $request->celular;
        $persona->email = $request->email;
        $persona->condicion = '1';
        $persona->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $persona = Persona::findOrFail($request->id);
        $persona->condicion = '0';
        $persona->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $persona = Persona::findOrFail($request->id);
        $persona->condicion = '1';
        $persona->save();
    }

}
