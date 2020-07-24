<?php

namespace App\Repositories;

use App\Categoria;

class CategoriaRepository
{
    /**
     * @param int $limit
     * @param int $offset
     * @param array $order
     * @param null $searchValue
     * @param null $criterio
     * @return \Illuminate\Support\Collection|Categoria[]
     */
    public function getAll($limit =10, $offset =0, $order = [['col' => 'c.created_at', 'dir' => 'desc']],
                           $searchValue = null, $criterio = null) {
        $query = Categoria::from(Categoria::getFullTableName(). ' as c')->select('c.*');


        if (!is_null($searchValue) && !is_null($criterio)) {
            $query->where(function ($query) use ($criterio, $searchValue) {
                $query->where('c.'.$criterio, 'like', $searchValue.'%');
            });
        }

        $query->take($limit)->skip($offset);
        foreach($order as $orderItem) {
            $query->orderBy($orderItem['col'], $orderItem['dir']);
        }
        return $query->paginate();
    }

    public function getById($id) {
        return Categoria::find($id);
    }
}