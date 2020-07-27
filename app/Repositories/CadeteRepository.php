<?php

namespace App\Repositories;

use App\Cadete;
use App\Models\Demerito;
use App\Persona;
use App\Sancion;
use Illuminate\Support\Facades\DB;

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
     * @param null $startDate
     * @param null $endDate
     * @param array $filters
     * @return \Illuminate\Support\Collection|Cadete[]
     */
    public function getAllFrancoDeHonor($limit =10, $offset =0,
                                        $order = [['col' => 'c.year_ingreso', 'dir' => 'desc'], ['col' => 'p.nombre', 'dir' => 'asc']],
                                        $startDate = null, $endDate = null, $filters = []) {
        $query = Cadete::from(Cadete::getFullTableName(). ' as c')
            ->join(Persona::getFullTableName(). ' as p', 'c.persona_id', '=', 'p.id')
            ->leftJoin(Demerito::getFullTableName(). ' as d', 'd.cadete_id', '=', 'c.id')
            ->leftJoin(Sancion::getFullTableName(). ' as s', 'd.sancion_id', '=', 's.id')
            ->select(
                'c.id',
                'p.nombre',
                'p.grado',
                'c.year_ingreso',
                DB::raw('COUNT(s.id) AS total_sanciones, 
                    SUM(s.puntaje) AS suma_puntaje, 
                    SUM(s.puntaje_dia * d.cant_dia) AS suma_puntaje_dia'
                ))
            ->distinct();

        if (!is_null($startDate) && !is_null($endDate)) {
            $query->where(function($query) use ($startDate, $endDate) {
                $query->whereRaw(" 
            ( SELECT COUNT(DISTINCT de.id) FROM ".Demerito::getFullTableName()." AS de 
            WHERE de.cadete_id = c.id AND 
            (de.created_at > ".$startDate." AND de.created_at < ".$endDate.")) = 0");
            });
        }

        $query->groupBy('c.id', 'p.nombre');
        $query->take($limit)->skip($offset);
        foreach($order as $orderItem) {
            $query->orderBy($orderItem['col'], $orderItem['dir']);
        }
        return $query->get();
    }

    /**
     * @param null $startDate
     * @param null $endDate
     * @param array $filters
     * @return \Illuminate\Support\Collection|Cadete[]
     */
    public function countAllFrancoDeHonor($startDate = null, $endDate = null, $filters = []) {
        $query = Cadete::from(Cadete::getFullTableName(). ' as c')
            ->join(Persona::getFullTableName(). ' as p', 'c.persona_id', '=', 'p.id')
            ->leftJoin(Demerito::getFullTableName(). ' as d', 'd.cadete_id', '=', 'c.id')
            ->leftJoin(Sancion::getFullTableName(). ' as s', 'd.sancion_id', '=', 's.id')
            ->select('c.*')
            ->distinct();

        if (!is_null($startDate) && !is_null($endDate)) {
            $query->where(function($query) use ($startDate, $endDate) {
                $query->whereRaw(" 
            ( SELECT COUNT(DISTINCT de.id) FROM ".Demerito::getFullTableName()." AS de 
            WHERE de.cadete_id = c.id AND 
            (de.created_at > ".$startDate." AND de.created_at < ".$endDate.")) = 0");
            });
        }
        return $query->count();
    }
}