<?php

namespace App\Services;

use App\Repositories\CadeteRepository;
use App\Cadete;
use Carbon\Carbon;

class CadeteService extends BaseService
{

    /**
     * @var CadeteRepository $cadeteRepository
     */
    protected $cadeteRepository;

    /**
     * CadeteService constructor.
     * @param CadeteRepository $cadeteRepository
     */
    public function __construct(CadeteRepository $cadeteRepository)
    {
        parent::__construct();
        $this->cadeteRepository = $cadeteRepository;
    }

    public function getById($id) {
        return $this->cadeteRepository->getById($id);
    }

    /**
     * @param array $data
     * @return \Illuminate\Support\Collection|Cadete[]
     */
    public function getAllByFilter($data) {
        return $this->cadeteRepository->getAllByFilter(
            array_get($data, 'limit', 10),
            array_get($data, 'offset', 0),
            array_get($data, 'order', [['col' => 'c.year_ingreso', 'dir' => 'desc']]),
            array_get($data, 'search', null)
        );
    }

    /**
     * @param array $filters
     * @return Cadete[]|\Illuminate\Support\Collection
     */
    public function getAllFrancoDeHonor($filters) {
        return $this->cadeteRepository->getAllFrancoDeHonor($filters);
    }

    public function countAllFrancoDeHonor($filters) {
        return $this->cadeteRepository->countAllFrancoDeHonor($filters);
    }

    public function getAllDemeritoByFilter(Cadete $cadete, $filters) {
        return $this->cadeteRepository->getAllDemeritoByFilter($cadete, $filters);
    }

    public function listarSalidaDeFrancoYArresto($config, $filters) {

        if(array_key_exists('type', $config) && $config['type'] === 'salida') {
            $titular = "RELACION NOMINAL DE LAS DAMAS Y CABALLEROS CADETES QUE TIENEN SALIDA DE FRANCO";
            if (array_key_exists('titular', $config)) {
                $titular = $config['titular'];
            }

            if (array_key_exists('fechaSalida', $filters) && !is_null($filters['fechaSalida'])) {
                $fechaSalida = $filters['fechaSalida'];

                if ((array_key_exists('code', $config) && $config['code'] === "franco_de_honor")) {
                    if (array_key_exists('sabado_inicio', $fechaSalida) && !is_null($fechaSalida['sabado_inicio'])
                        && array_key_exists('sabado_fin', $fechaSalida) && !is_null($fechaSalida['sabado_fin'])
                    ) {
                        $sabadoInicio = new Carbon($fechaSalida['sabado_inicio']);
                        $sabadoFin = new Carbon($fechaSalida['sabado_fin']);

                        $titular .= " DE HONOR DESDE EL DIA SABADO " . $sabadoInicio->format('d-m-Y') . " DE HRS. " .
                            $sabadoInicio->format('H:i') . " A " . $sabadoFin->format('H:i') . " HRS.";
                    }

                    $titular .= " Y ";
                }

                if (array_key_exists('code', $config) && $config['code'] === "franco_medio_domingo") {
                    $titular .= " MEDIO DOMINGO ";
                }

                if (array_key_exists('domingo_inicio', $fechaSalida) && !is_null($fechaSalida['domingo_inicio'])
                    && array_key_exists('domingo_fin', $fechaSalida) && !is_null($fechaSalida['domingo_fin'])
                ) {
                    $domingoInicio = new Carbon($fechaSalida['domingo_inicio']);
                    $domingoFin = new Carbon($fechaSalida['domingo_fin']);

                    $titular .= " EL DIA DOMINGO " . $domingoInicio->format('d-m-Y') . " DE HRS. " .
                        $domingoInicio->format('H:i') . " A " . $domingoFin->format('H:i') . " HRS.";
                }
            }
            $config['titular'] = $titular;
        }

        if(array_key_exists('code', $config)) {
            $filters['code'] = $config['code'];
        }

        if(array_key_exists('puntaje', $config)) {
            $filters['puntaje'] = $config['puntaje'];
        }

        if (array_key_exists('startDate', $filters) && !is_null($filters['startDate']))
            $filters['startDate'] = Carbon::parse($filters['startDate'])->format('Y-m-d H:i');

        if (array_key_exists('endDate', $filters) && !is_null($filters['endDate']))
            $filters['endDate'] = Carbon::parse($filters['endDate'])->format('Y-m-d H:i');

        $cadeteList = $this->getAllFrancoDeHonor($filters);

        $groupCadeteList = [];
        $totalCadetes = 0;
        if (array_key_exists('code', $config)
            && in_array($config['code'], ['sancion_orden_dia', 'reposo'])) {
            if ($cadeteList->count() > 0) {
                $groupCadeteList[] = $cadeteList;
                $totalCadetes = count($cadeteList);
            }
        } else {
            foreach ($cadeteList as $cadete) {
                if (!array_key_exists($cadete->year(), $groupCadeteList))
                    $groupCadeteList[$cadete->year()] = [];
                $groupCadeteList[$cadete->year()][] = $cadete;
                $totalCadetes++;
            }
        }

        $resultData = [
            "config" => $config,
            "groupCadeteList" => $groupCadeteList,
            "totalCadetes" => $totalCadetes
        ];


        return $resultData;
    }
}