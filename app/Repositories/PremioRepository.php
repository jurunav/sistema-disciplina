<?php

namespace App\Repositories;

use App\Premio;

class PremioRepository
{
    /**
     * @param int $limit
     * @param int $offset
     * @param array $order
     * @param null $searchValue
     * @param null $criterio
     * @return \Illuminate\Support\Collection|Premio[]
     */
    public function getAll($limit =10, $offset =0, $order = [['col' => 'p.created_at', 'dir' => 'desc']],
                           $searchValue = null, $criterio = null) {
        $query = Premio::from(Premio::getFullTableName(). ' as p')->select('p.*');


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
        return Premio::find($id);
    }
}