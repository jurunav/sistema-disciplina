<?php

namespace App\Http\Controllers;

use App\Services\CadeteService;
use App\Services\PersonaService;
use App\Services\UtilDateService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GenerateReportController extends Controller
{
    /**
     * @var CadeteService $cadeteService
     */
    protected $cadeteService;

    /**
     * @var UtilDateService $utilDateService
     */
    protected $utilDateService;

    /**
     * @var PersonaService $personaService
     */
    protected $personaService;


    /**
     * GenerateReportController constructor.
     * @param CadeteService $cadeteService
     * @param UtilDateService $utilDateService
     * @param PersonaService $personaService
     */
    public function __construct(CadeteService $cadeteService, UtilDateService $utilDateService, PersonaService $personaService)
    {
        $this->cadeteService = $cadeteService;
        $this->utilDateService = $utilDateService;
        $this->personaService = $personaService;
    }

    public function listarFrancoDeHonor(Request $request) {
        $filters = json_decode($request->input('filters'), true);

        $titular = "I. RELACION NOMINAL DE LAS DAMAS Y CABALLEROS CADETES QUE TIENEN SALIDA DE FRANCO ";

        if (array_key_exists('fechaSalida', $filters) && !is_null($filters['fechaSalida'])) {
            $fechaSalida = $filters['fechaSalida'];

            if ((array_key_exists('salida', $filters) && $filters['salida'] === "franco_de_honor")) {
                if (array_key_exists('sabado_inicio', $fechaSalida) && !is_null($fechaSalida['sabado_inicio'])
                    && array_key_exists('sabado_fin', $fechaSalida) && !is_null($fechaSalida['sabado_fin'])
                ) {
                    $sabadoInicio = new Carbon($fechaSalida['sabado_inicio']);
                    $sabadoFin = new Carbon($fechaSalida['sabado_fin']);

                    $titular .= " DE HONOR DESDE EL DIA SABADO " . $sabadoInicio->format('d-m-Y') . " DE HRS. " .
                        $sabadoInicio->format('H:i') . " A " . $sabadoFin->format('H:i') . " HRS.";
                }

                $titular .= " Y ";
                $filters['puntajeSalida'] = 0;
            }

            if (array_key_exists('salida', $filters) && $filters['salida'] === "franco_domingo") {
                $filters['puntajeSalida'] = 3;
            }

            if (array_key_exists('salida', $filters) && $filters['salida'] === "franco_medio_domingo") {
                $titular .= " MEDIO DOMINGO ";
                $filters['puntajeSalida'] = 5;
            }

            if (array_key_exists('domingo_inicio', $fechaSalida) && !is_null($fechaSalida['domingo_inicio'])
                && array_key_exists('domingo_fin', $fechaSalida) && !is_null($fechaSalida['domingo_fin'])) {
                $domingoInicio = new Carbon($fechaSalida['domingo_inicio']);
                $domingoFin = new Carbon($fechaSalida['domingo_fin']);

                $titular .= " EL DIA DOMINGO ".$domingoInicio->format('d-m-Y'). " DE HRS. ".
                    $domingoInicio->format('H:i')." A ".$domingoFin->format('H:i'). " HRS.";
            }
        }

        if (array_key_exists('startDate', $filters) && !is_null($filters['startDate']))
            $filters['startDate'] = Carbon::parse($filters['startDate'])->format('Y-m-d H:i');

        if (array_key_exists('endDate', $filters) && !is_null($filters['endDate']))
            $filters['endDate'] = Carbon::parse($filters['endDate'])->format('Y-m-d H:i');

        $cadeteList = $this->cadeteService->getAllFrancoDeHonor($filters);
        $groupCadeteList = [];
        foreach ($cadeteList as $cadete) {
            if (!array_key_exists($cadete->year_ingreso, $groupCadeteList))
                $groupCadeteList[$cadete->year_ingreso] = [];
            $groupCadeteList[$cadete->year_ingreso][] = $cadete;
        }

        $pdf = \PDF::loadView(
            'report.lista-franco-honor',
            [
                'groupCadeteList'=>$groupCadeteList,
                'titular'=>$titular
            ]
        )->setPaper('letter');

        $now = new Carbon();

        return $pdf->download('franco_de_honor_'.$now->format('Y-m-d-H:i:s').'.pdf');
    }

    public function controlMeritoDemerito(Request $request) {

        $filters = json_decode($request->input('filters'), true);

        $startDate = null;
        if (array_key_exists('startDate', $filters) && !is_null($filters['startDate']))
            $startDate = new Carbon($filters['startDate']);
        $endDate = null;
        if (array_key_exists('endDate', $filters) && !is_null($filters['endDate']))
            $endDate = new Carbon($filters['endDate']);

        $cadete = null;
        if (array_key_exists('cadeteId', $filters))
            $cadete = $this->cadeteService->getById($filters['cadeteId']);

        $jefeDeSeccion = null;
        if (array_key_exists('jefeDeSeccionId', $filters))
            $jefeDeSeccion = $this->personaService->getById($filters['jefeDeSeccionId']);

        $comandanteEscuadron = null;
        if (array_key_exists('comandanteEscuadronId', $filters))
            $comandanteEscuadron = $this->personaService->getById($filters['comandanteEscuadronId']);

        $meritoDemeritoData = [];
        if (!is_null($startDate) && !is_null($endDate)) {
            $weekList = $this->utilDateService->getWeekRangeDate($startDate, $endDate);

            foreach ($weekList as $week) {
                $meritoDemeritoList = $this->cadeteService->getAllDemeritoByFilter($cadete , ['startDate' => $week[0], 'endDate' => $week[1]]);
                $weekData = [
                    "titulo" => "CIERRE DE LIBRO",
                    "fecha" => Carbon::parse($week[0])->format('Y-m-d'),
                    "results" => $meritoDemeritoList
                ];

                $meritoDemeritoData[] = $weekData;
            }
        }

        $pdf = \PDF::loadView(
            'report.control-merito-demerito',
            [
                'meritoDemeritoData'=> $meritoDemeritoData,
                'cadete'=> $cadete,
                'jefeDeSeccion'=> $jefeDeSeccion,
                'comandanteEscuadron'=> $comandanteEscuadron
            ]
        )->setPaper('letter', 'landscape');

        $now = new Carbon();

        return $pdf->download('control_meritos_demeritos_'.$now->format('Y-m-d-H:i:s').'.pdf');
    }
}
