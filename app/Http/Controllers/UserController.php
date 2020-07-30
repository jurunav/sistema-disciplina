<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\User;


class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $users = User::orderBy('id', 'desc')->paginate(10);
        }
        else{
            $users = User::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(10);
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
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt( $request->password);
            $user->save();

            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }


    }
    public function update(Request $request, $id)
    {
        if (!$request->ajax()) return redirect('/');
           
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt( $request->password);
            $user->save();

    }


    public function destroy(Request $request, $id)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($id);
        $user->delete();
    }


}
