<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RolController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $roles = Role::orderBy('id', 'desc')->paginate(10);
        }
        else{
            $roles = Role::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(10);
        }
        

        return [
            'pagination' => [
                'total'        => $roles->total(),
                'current_page' => $roles->currentPage(),
                'per_page'     => $roles->perPage(),
                'last_page'    => $roles->lastPage(),
                'from'         => $roles->firstItem(),
                'to'           => $roles->lastItem(),
            ],
            'roles' => $roles
        ];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $rol = new Role();
        $rol->name = $request->name;
        $rol->guard_name = 'web';
        $rol->save();
    }

    public function update(Request $request, $id)
    {
        if (!$request->ajax()) return redirect('/');
        $rol = Role::find($id);
        $rol->name = $request->name;
        $rol->save();
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->ajax()) return redirect('/');
        $rol = Role::findOrFail($id);
        $rol->delete();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @deprecated
     */
    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $rol = Role::findOrFail($request->id);
        $rol->condicion = '1';
        $rol->save();
    }


}
