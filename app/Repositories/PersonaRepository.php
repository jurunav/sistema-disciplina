<?php

namespace App\Repositories;


use App\Cadete;
use App\Models\Encargado;
use App\Persona;
use App\User;

class PersonaRepository
{
    /**
     * @param int $limit
     * @param int $offset
     * @param array $order
     * @param null $searchValue
     * @param null $criterio
     * @param array $filters
     * @return \Illuminate\Support\Collection|Persona[]
     */
    public function getAll($limit =10, $offset =0, $order = [['col' => 'p.created_at', 'dir' => 'desc']],
                           $searchValue = null, $criterio = null, $filters = []) {
        $query = Persona::from(Persona::getFullTableName(). ' as p')->select('p.*')->distinct();

        if (array_key_exists('withCadeteOnly', $filters)) {
            $query->leftJoin(Cadete::getFullTableName(). ' as c', 'p.id', '=', 'c.persona_id');
        }

        if (array_key_exists('withEncargadoOnly', $filters)) {
            $query->leftJoin(Encargado::getFullTableName(). ' as e', 'p.id', '=', 'e.persona_id');
        }

        if (array_key_exists('finalStage', $filters)) {
            $query->where(function($query) use ($filters) {
                $query->where('c.year_ingreso', '=', 4)
                    ->orWhereNull('c.year_ingreso');
            });
        }

        if (!is_null($searchValue) && !is_null($criterio)) {
            $query->where(function ($query) use ($criterio, $searchValue) {
                $query->where('p.'.$criterio, 'like', $searchValue.'%');
            });
        }

        $query->take($limit)->skip($offset);
        foreach($order as $orderItem) {
            $query->orderBy($orderItem['col'], $orderItem['dir']);
        }
//        var_dump($query->toSql());
//        exit();
        return $query->paginate();
    }

    public function getByUser(User $user) {
        return Persona::where('user_id', '=' , $user->id)->first();
    }

    public function getById($id) {
        return Persona::find($id);
    }

    public function getByEmail($email) {
        return Persona::where('email', '=', $email)->first();
    }

    public function getByGrado($grado) {
        return Persona::where('grado', '=', $grado)->first();
    }
}