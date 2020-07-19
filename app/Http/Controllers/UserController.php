<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\User;
use App\Persona;
use App\Rol;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
                   
            $users = User::join('personas','users.idpersona','=','personas.id')
            ->join('roles','users.idrol','=','roles.id')
            ->select('users.id','personas.nombre','personas.grado',
            'users.usuario','users.password','users.condicion','roles.nombre as nombre_rol')
            ->orderBy('users.id', 'desc')->paginate(5);
        }
        else{
            $users = User::join('personas','users.idpersona','=','personas.id')
            ->join('roles','users.idrol','=','roles.id')
            ->select('users.id','personas.nombre','personas.grado',
            'users.usuario','users.password','users.condicion','roles.nombre as nombre_rol') 

            ->where('personas.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('users.id', 'desc')->paginate(5);
        }
        

        return [
            'pagination' => [
                'total'        => $users->total(),
                'current_page' => $users->currentPage(),
                'per_page'     => $users->perPage(),
                'last_page'    => $users->lastPage(),
                'from'         => $users->firstItem(),
                'to'           => $users->lastItem(),
            ],
            'users' => $users
        ];
    }
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();
            
            $user = new User();
            $user->idpersona = $request->idpersona;
            $user->idrol = $request->idrol;
            $user->usuario = $request->usuario;
            $user->password = bcrypt( $request->password);
            $user->condicion = '1';            
            $user->save();

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
            
           
            $user = User::findOrFail($request->id);
            $user->idpersona = $request->idpersona;
            $user->idrol = $request->idrol;
            $user->usuario = $request->usuario;
            $user->password = bcrypt( $request->password);
            $user->condicion = '1';
            $user->save();

            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condicion = '0';
        $user->save();

    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condicion = '1';
        $user->save();
    }
}
