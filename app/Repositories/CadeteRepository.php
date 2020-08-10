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
            ->join(Persona::getFullTableName(). ' as p', 'c.persona_id', '=', 'p.id')
            ->leftJoin(Demerito::getFullTableName(). ' as d', 'd.cadete_id', '=', 'c.id')
            ->leftJoin(Sancion::getFullTableName(). ' as s', 'd.sancion_id', '=', 's.id')
            ->select(
                'c.id',
                'p.nombre',
                'p.grado',
                'c.year_ingreso',
                DB::raw('COUNT(s.id) AS total_sanciones'))
            ->distinct();

        if (array_key_exists('startDate', $filters) && !is_null($filters['startDate']) &&
            array_key_exists('endDate', $filters) && !is_null($filters['endDate'])) {

            $startDate = $filters['startDate'];
            $endDate = $filters['endDate'];
            $puntajeSalida = $filters['puntajeSalida'];
            $query->where(function($query) use ($startDate, $endDate, $puntajeSalida) {
                $ofScore = ($puntajeSalida == 0) ? "IS NULL" : "<= ".$puntajeSalida;
                $query->whereRaw("(SELECT SUM(sa.puntaje + (sa.puntaje_dia * de.cant_dia)) FROM ".
                    Demerito::getFullTableName()." AS de  INNER JOIN ".Sancion::getFullTableName().
                    " AS sa ON de.sancion_id = sa.id WHERE de.cadete_id = c.id AND (de.created_at > '".
                    $startDate."' AND de.created_at < '".$endDate."')) ".$ofScore);
            });
        }
        if (array_key_exists('yearIngreso', $filters)
            && !is_null($filters['yearIngreso'])
            && $filters['yearIngreso'] != 'all') {

            $query->where('c.year_ingreso', '=', $filters['yearIngreso']);
        }

        $query->groupBy('c.id', 'p.nombre','p.grado','c.year_ingreso');
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
                DB::raw('NULL AS sancionador')
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
                DB::raw('CONCAT(p.grado, ". ", p.nombre) AS sancionador'))
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