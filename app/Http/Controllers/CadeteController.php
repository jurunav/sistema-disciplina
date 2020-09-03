<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Cadete;
use App\Persona;

class CadeteController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $personas = Cadete::join('personas','cadetes.persona_id','=','personas.id')
            ->select('cadetes.id', 'cadetes.persona_id','personas.grado','personas.nombre','personas.ci',
            'personas.cm','personas.domicilio','personas.celular',
            'personas.email','cadetes.year_ingreso','personas.condicion')
            ->orderBy('personas.id', 'desc')->paginate(10);
        }
        else{
            $personas = Cadete::join('personas','cadetes.persona_id','=','personas.id')
            ->select('cadetes.id', 'cadetes.persona_id','personas.grado','personas.nombre','personas.ci',
            'personas.cm','personas.domicilio','personas.celular',
            'personas.email','cadetes.year_ingreso','personas.condicion')           
            ->where('personas.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('personas.id', 'desc')->paginate(10);
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
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();

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

            $cadete = new Cadete();
            $cadete->persona_id = $persona->id;
            $cadete->year_ingreso = new \DateTime($request->year_ingreso);
            $cadete->save();

            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }



    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();
            
            //Buscar primero el cadete a modificar
            $cadete = Cadete::findOrFail($request->id);

            $persona = Persona::findOrFail($cadete->persona_id);
            $persona->grado = $request->grado;
            $persona->nombre = $request->nombre;
            $persona->ci = $request->ci;
            $persona->cm = $request->cm;
            $persona->domicilio = $request->domicilio;
            $persona->celular = $request->celular;
            $persona->email = $request->email;
            $persona->condicion = '1';
            $persona->save();

            $cadete->year_ingreso = new \DateTime($request->year_ingreso);
            //$cadete->condicion = '1';
            $cadete->save();

            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();
            
            //Buscar primero el cadete a modificar
            $cadete = Cadete::findOrFail($request->id);

            $persona = Persona::findOrFail($cadete->persona_id);
            $persona->condicion = '0';
            $persona->save();

            //$cadete->condicion = '0';
            $cadete->save();

            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }

    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();
            
            //Buscar primero el cadete a modificar
            $cadete = Cadete::findOrFail($request->id);

            $persona = Persona::findOrFail($cadete->persona_id);
            $persona->condicion = '1';
            $persona->save();

            //$cadete->condicion = '1';
            $cadete->save();

            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }
    }
}
