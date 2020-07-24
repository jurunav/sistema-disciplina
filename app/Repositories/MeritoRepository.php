<?php

namespace App\Repositories;

use App\Models\Merito;

class MeritoRepository
{
    /**
     * @param int $limit
     * @param int $offset
     * @param array $order
     * @param null $searchValue
     * @param null $criterio
     * @return \Illuminate\Support\Collection|Merito[]
     */
    public function getAll($limit =10, $offset =0, $order = [['col' => 'm.created_at', 'dir' => 'desc']],
                           $searchValue = null, $criterio = null) {
        $query = Merito::from(Merito::getFullTableName(). ' as m')->select('m.*');

        if (!is_null($searchValue) && !is_null($criterio)) {
            $query->where(function ($query) use ($criterio, $searchValue) {
                $query->where('m.'.$criterio, 'like', $searchValue.'%');
            });
        }

        $query->take($limit)->skip($offset);
        foreach($order as $orderItem) {
            $query->orderBy($orderItem['col'], $orderItem['dir']);
        }
        return $query->paginate();
    }

    public function getById($id) {
        return Merito::find($id);
    }
}