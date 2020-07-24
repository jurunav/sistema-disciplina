<?php

namespace App\Repositories;

use App\Cadete;
use App\Persona;

class CadeteRepository
{
    /**
     * @param int $limit
     * @param int $offset
     * @param array $order
     * @param null $searchValue
     * @return \Illuminate\Support\Collection|Cadete[]
     */
    public function getAllByFilter($limit = 10, $offset = 0, $order = [['col' => 'c.year_ingreso', 'dir' => 'desc']], $searchValue = null) {
        $query = Cadete::from(Cadete::getFullTableName(). ' as c')->select('c.*')
            ->join(Persona::getFullTableName(). ' as p', 'p.id', '=', 'c.persona_id');


        if (!is_null($searchValue)) {
            $query->where(function ($query) use ($searchValue) {
                $query->where('p.nombre', 'like', $searchValue.'%');
            });
        }

        $query->take($limit)->skip($offset);
        foreach($order as $orderItem) {
            $query->orderBy($orderItem['col'], $orderItem['dir']);
        }
        return $query->get();
    }

    public function getById($id) {
        return Cadete::find($id);
    }
}