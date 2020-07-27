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

    /**
     * @param int $limit
     * @param int $offset
     * @param array $order
     * @param array $filters
     * @return \Illuminate\Support\Collection|Cadete[]
     */
    public function getAllFrancoDeHonor($limit =10, $offset =0, $order = [['col' => 'p.created_at', 'dir' => 'desc']], $filters = []) {
        $query = Cadete::from(Cadete::getFullTableName(). ' as p')->select('p.*')->distinct();
            $query->leftJoin(Persona::getFullTableName(). ' as c', 'p.id', '=', 'c.persona_id');

        if (array_key_exists('finalStage', $filters)) {
            $query->where(function($query) use ($filters) {
                $query->where('c.year_ingreso', '=', 4)
                    ->orWhereNull('c.year_ingreso');
            });
        }

        $query->take($limit)->skip($offset);
        foreach($order as $orderItem) {
            $query->orderBy($orderItem['col'], $orderItem['dir']);
        }

        return $query->paginate();
    }
}