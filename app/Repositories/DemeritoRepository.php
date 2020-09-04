<?php

namespace App\Repositories;

use App\Models\Demerito;
use App\Persona;
use App\Cadete;

class DemeritoRepository
{
    /**
     * @param int $limit
     * @param int $offset
     * @param array $order
     * @param null $searchValue
     * @param null $criterio
     * @return \Illuminate\Support\Collection|Demerito[]
     */
    public function getAll($limit =10, $offset =0, $order = [['col' => 'd.created_at', 'dir' => 'desc']],
                           $searchValue = null, $criterio = null) {
        $query = Demerito::from(Demerito::getFullTableName(). ' as d')
            ->join(Cadete::getFullTableName(). ' as c', 'c.id', '=', 'd.cadete_id')
            ->join(Persona::getFullTableName(). ' as p', 'p.id', '=', 'c.persona_id')
            ->select('d.*');

        if (!is_null($searchValue) && !is_null($criterio)) {
            $query->where(function ($query) use ($criterio, $searchValue) {
                if ($criterio == 'num_orden') {
                    $query->where('d.'.$criterio, 'like', '%'.$searchValue.'%');
                }
                if ($criterio == 'cadete_nombre') {
                    $query->where('p.nombre', 'like', '%'.$searchValue.'%');
                }
            });
        }

        $query->take($limit)->skip($offset);
        foreach($order as $orderItem) {
            $query->orderBy($orderItem['col'], $orderItem['dir']);
        }
        return $query->paginate();
    }

    public function getById($id) {
        return Demerito::find($id);
    }
}