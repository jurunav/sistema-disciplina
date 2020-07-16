<?php

namespace App\Repositories;


use App\Models\Encargado;
use App\Persona;

class EncargadoRepository
{
    /**
     * @param int $limit
     * @param int $offset
     * @param array $order
     * @param null $searchValue
     * @param null $criterio
     * @return \Illuminate\Support\Collection|Encargado[]
     */
    public function getAll($limit =10, $offset =0, $order = [['col' => 'e.created_at', 'dir' => 'desc']],
                           $searchValue = null, $criterio = null) {
        $query = Encargado::from(Encargado::getFullTableName(). ' as e')->select('e.*')
            ->join(Persona::getFullTableName(). ' as p', 'p.id', '=', 'e.persona_id');


        if (!is_null($searchValue) && !is_null($criterio)) {
            $query->where(function ($query) use ($criterio, $searchValue) {
                $query->where('p.'.$criterio, 'like', $searchValue.'%');
            });
        }

        $query->take($limit)->skip($offset);
        foreach($order as $orderItem) {
            $query->orderBy($orderItem['col'], $orderItem['dir']);
        }
        return $query->paginate();
    }

    public function getById($id) {
        return Encargado::find($id);
    }

    public function getByPersonaId($personaId) {
        return Encargado::where('persona_id', '=', $personaId)->first();
    }
}