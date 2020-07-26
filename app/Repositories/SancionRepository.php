<?php

namespace App\Repositories;

use App\Sancion;

class SancionRepository
{
    /**
     * @param int $limit
     * @param int $offset
     * @param array $order
     * @param null $searchValue
     * @param null $criterio
     * @return \Illuminate\Support\Collection|Sancion[]
     */
    public function getAll($limit =10, $offset =0, $order = [['col' => 's.created_at', 'dir' => 'desc']],
                           $searchValue = null, $criterio = null) {
        $query = Sancion::from(Sancion::getFullTableName(). ' as s')->select('s.*');


        if (!is_null($searchValue) && !is_null($criterio)) {
            $query->where(function ($query) use ($criterio, $searchValue) {
                $query->where('s.'.$criterio, 'like', $searchValue.'%');
            });
        }

        $query->take($limit)->skip($offset);
        foreach($order as $orderItem) {
            $query->orderBy($orderItem['col'], $orderItem['dir']);
        }
        return $query->paginate();
    }

    public function getById($id) {
        return Sancion::find($id);
    }
}