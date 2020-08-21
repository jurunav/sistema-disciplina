<?php

namespace App\Repositories;

use App\Cadete;
use App\Models\Demerito;
use App\Models\Disciplina;
use App\Models\Merito;
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
                $query->where('p.nombre', 'like', '%'.$searchValue.'%');
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
     * @param array $filters
     * @return \Illuminate\Support\Collection|Cadete[]
     */
    public function getAllFrancoDeHonor($filters = []) {
        $query = Cadete::from(Cadete::getFullTableName(). ' as c')
            ->join(Persona::getFullTableName(). ' as p', 'c.persona_id', '=', 'p.id');

        if (array_key_exists('startDate', $filters) && !is_null($filters['startDate']) &&
            array_key_exists('endDate', $filters) && !is_null($filters['endDate'])) {
            $query->leftJoin(DB::raw("(select * from ".Demerito::getFullTableName().
                " where (created_at > '".$filters['startDate']."' AND created_at < '".
                $filters['endDate']."') UNION select * from ".Demerito::getFullTableName().
                " where num_orden IS NOT NULL AND (cant_dia > 0 AND ADDDATE(created_at, INTERVAL cant_dia DAY) > '".$filters['endDate']."')) as d"),
                'd.cadete_id', '=', 'c.id');
        } else {
            $query->leftJoin(Demerito::getFullTableName(). 'as d', 'd.cadete_id', '=', 'c.id');
        }

//        $query->leftJoin(Sancion::getFullTableName(). ' as s', 'd.sancion_id', '=', 's.id');

        $query->leftJoin(Sancion::getFullTableName(). ' as s', function ($leftJoin) {
            $leftJoin->on('d.sancion_id', '=', 's.id')
                ->where('s.exclusion_salida_franco', '=', 'false');
        });

        $query->select(
            'c.id',
            'p.nombre',
            'p.grado',
            'c.year_ingreso',
            DB::raw('COUNT(s.id) as total_sanciones'),
            DB::raw('SUM(s.puntaje + (s.puntaje_dia * d.cant_dia)) AS puntaje_total'),
            DB::raw('SUM(IF ((s.salida_franco = false AND s.por_orden = true AND s.por_reposo = false), true, false)) AS arresto_total'),
            DB::raw('SUM(IF ((s.salida_franco = false AND s.por_orden = false AND s.por_reposo = true), true, false)) AS reposo_total')
        )->distinct();

        if (array_key_exists('code', $filters) && $filters['code'] == 'sancion_orden_dia') {
            $query->where('d.num_orden', 'is not null');
        }

//        if (array_key_exists('code', $filters) && $filters['code'] == 'reposo') {
//            $query->where('s.nombre', 'like', '% reposo %');
//        }

        $query->groupBy('c.id', 'p.nombre','p.grado','c.year_ingreso');

        if (array_key_exists('puntaje', $filters)) {
            $withScore  = "0=0";
            if (is_array($filters['puntaje']) && count($filters['puntaje']) > 0) {
                $withScore = "(puntaje_total >= ".
                    $filters['puntaje']['min'] ." AND puntaje_total <= ".
                    $filters['puntaje']['max'].") AND (arresto_total = 0 AND reposo_total = 0)";
            } else if (array_key_exists('code', $filters) && $filters['code'] == 'franco_de_honor') {
                $withScore = "puntaje_total is null";
            } else if (array_key_exists('code', $filters) && $filters['code'] == 'sin_salida') {
                $withScore = "puntaje_total >= ". $filters['puntaje']. " AND (arresto_total = 0 AND reposo_total = 0)";
            } else if (array_key_exists('code', $filters) && $filters['code'] == 'sancion_orden_dia') {
                $withScore = "arresto_total > 0";
            } else         if (array_key_exists('code', $filters) && $filters['code'] == 'reposo') {
                $withScore = "reposo_total > 0";
            }
            $query->havingRaw($withScore);
        }

        $order = [
            ['col' => 'c.year_ingreso', 'dir' => 'desc'],
            ['col' => 'p.nombre', 'dir' => 'asc']
        ];
        foreach($order as $orderItem) {
            $query->orderBy($orderItem['col'], $orderItem['dir']);
        }
        return $query->get();
    }

    /**
     * @param array $filters
     * @return \Illuminate\Support\Collection|Cadete[]
     */
    public function countAllFrancoDeHonor($filters = []) {
        $query = Cadete::from(Cadete::getFullTableName(). ' as c')
            ->join(Persona::getFullTableName(). ' as p', 'c.persona_id', '=', 'p.id')
            ->leftJoin(Demerito::getFullTableName(). ' as d', 'd.cadete_id', '=', 'c.id')
            ->leftJoin(Sancion::getFullTableName(). ' as s', 'd.sancion_id', '=', 's.id')
            ->select('c.*')
            ->distinct();

        if (array_key_exists('startDate', $filters) && !is_null($filters['startDate']) &&
            array_key_exists('endDate', $filters) && !is_null($filters['endDate'])) {

            $startDate = $filters['startDate'];
            $endDate = $filters['endDate'];
            $query->where(function($query) use ($startDate, $endDate) {
                $query->whereRaw(" 
            ( SELECT COUNT(DISTINCT de.id) FROM ".Demerito::getFullTableName()." AS de 
            WHERE de.cadete_id = c.id AND 
            (de.created_at > '".$startDate."' AND de.created_at < '".$endDate."')) = 0");
            });
        }
        return $query->count();
    }

    /**
     * @param Cadete $cadete
     * @param array $filters
     * @return \Illuminate\Support\Collection|Cadete[]
     */
    public function getAllDemeritoByFilter(Cadete $cadete, $filters = []) {
        $queryA = Disciplina::from(Disciplina::getFullTableName(). ' as di')
            ->join(Merito::getFullTableName(). ' as m', 'di.id', '=', 'm.disciplina_id')
            ->select(
                'm.id',
                'm.created_at',
                DB::raw('NULL AS grupo'),
                DB::raw('NULL AS inciso'),
                DB::raw('NULL AS articulo'),
                'm.num_orden',
                DB::raw('NULL AS cant_dia'),
                'di.puntaje AS merito',
                DB::raw('NULL AS demerito'),
                'di.nombre AS detalle',
                DB::raw('NULL AS sancionador'),
                DB::raw('NULL AS por_orden'),
                DB::raw('NULL AS categoria')
            );

        $queryA->where('m.cadete_id', '=', $cadete->id);

        if (array_key_exists('startDate', $filters) && !is_null($filters['startDate']) &&
            array_key_exists('endDate', $filters) && !is_null($filters['endDate'])) {

            $startDate = $filters['startDate'];
            $endDate = $filters['endDate'];

            $queryA->where(function ($queryA) use ($startDate, $endDate) {
                $queryA->whereBetween('m.created_at', [$startDate, $endDate]);
            });

        }

        $queryB = Sancion::from(Sancion::getFullTableName(). ' as sa')
            ->join(Demerito::getFullTableName(). ' as d', 'sa.id', '=', 'd.sancion_id')
            ->leftJoin(Persona::getFullTableName(). ' as p', 'd.sancionador_id', '=', 'p.id')
            ->select(
                'd.id',
                'd.created_at',
                'sa.grupo AS grupo',
                'sa.inciso AS inciso',
                'sa.articulo AS articulo',
                'd.num_orden',
                'd.cant_dia AS cant_dia',
                DB::raw('NULL AS merito'),
                DB::raw('IF(d.cant_dia = 0, sa.puntaje, (sa.puntaje_dia * d.cant_dia)) AS demerito'),
                'sa.nombre AS detalle',
                DB::raw('CONCAT(p.grado, ". ", p.nombre) AS sancionador'),
                'sa.por_reposo',
                'sa.categoria')
            ->union($queryA);

        $queryB->where('d.cadete_id', '=', $cadete->id);

        if (array_key_exists('startDate', $filters) && !is_null($filters['startDate']) &&
            array_key_exists('endDate', $filters) && !is_null($filters['endDate'])) {

            $startDate = $filters['startDate'];
            $endDate = $filters['endDate'];

            $queryB->where(function ($queryB) use ($startDate, $endDate) {
                $queryB->whereBetween('d.created_at', [$startDate, $endDate]);
            });

        }

        $order = [
            ['col' => 'created_at', 'dir' => 'asc'],
        ];
        foreach($order as $orderItem) {
            $queryB->orderBy($orderItem['col'], $orderItem['dir']);
        }

        return $queryB->get();
    }
}