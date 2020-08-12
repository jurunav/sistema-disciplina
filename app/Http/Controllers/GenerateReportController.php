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

        $francoYArrestoConfig = [
            [
                "code" => "franco_de_honor",
                "titular" => "I. RELACION NOMINAL DE LAS DAMAS Y CABALLEROS CADETES QUE TIENEN SALIDA DE FRANCO",
                "puntaje" => config('services.salidas.franco_de_honor'),
                "type" => "salida"
            ],
            [
                "code" => "franco_domingo",
                "titular" => "II. RELACION NOMINAL DE LAS DAMAS Y CABALLEROS CADETES QUE TIENEN SALIDA DE FRANCO",
                "puntaje" => [
                    "min" => config('services.salidas.franco_domingo.min'),
                    "max" => config('services.salidas.franco_domingo.max')
                ],
                "type" => "salida"
            ],
            [
                "code" => "franco_medio_domingo",
                "titular" => "III. RELACION NOMINAL DE LAS DAMAS Y CABALLEROS CADETES QUE TIENEN SALIDA DE FRANCO",
                "puntaje" => [
                    "min" => config('services.salidas.franco_medio_domingo.min'),
                    "max" => config('services.salidas.franco_medio_domingo.max')
                ],
                "type" => "salida"
            ],
            [
                "code" => "sin_salida",
                "titular" => "IV. RELACION NOMINAL DE LAS DAMAS Y CABALLEROS CADETES QUE TIENEN UN DOMINGO DE ARRESTO",
                "puntaje" => config('services.salidas.sin_salida'),
                "type" => "arresto"
            ],
            [
                "code" => "sancion_orden_dia",
                "titular" => "V. RELACION NOMINAL DE LAS DAMAS Y CABALLEROS CADETES SANCIONADOS POR ORDEN DEL DÍA",
                "type" => "arresto"
            ],
            [
                "code" => "reposo",
                "titular" => "VI. RELACION NOMINAL DE LAS DAMAS Y CABALLEROS CADETES QUE INGRESARON A REPOSO DURANTE LA SEMANA",
                "type" => "arresto"
            ],
        ];

        $francoYArrestoList = [];

        foreach ($francoYArrestoConfig as $config) {
            $resultData = $this->cadeteService->listarSalidaDeFrancoYArresto($config, $filters);
            $francoYArrestoList[] = $resultData;
        }

        $pdf = \PDF::loadView(
            'report.lista-franco-honor',
            [
                'francoYArrestoList'=>$francoYArrestoList,
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

                /**
                 * Aqui se verifica si en el rango de fecha no tiene ningun demerito, entonces se añade 3 puntos por franco de honor
                 */
                $puntajeMerito = 0;
                $detalleMerito = null;
                $tieneFrancoDeHonor = false;
                if (count($meritoDemeritoList) == 0) {
                    $puntajeMerito = 3;
                    $detalleMerito = "Franco de Honor";
                } else {
                    foreach ($meritoDemeritoList as $meritoDemerito) {
                        if (is_null($meritoDemerito->demerito) && (is_null($meritoDemerito->cant_dia) || $meritoDemerito->cant_dia == 0)) {
                            $tieneFrancoDeHonor = true;
                        }
                    }

                    if (!$tieneFrancoDeHonor) {
                        $puntajeMerito = 3;
                        $detalleMerito = "Franco de Honor";
                    }
                }

                $weekData = [
                    "titulo" => "CIERRE DE LIBRO",
                    "fecha" => Carbon::parse($week[0])->format('Y-m-d'),
                    "results" => $meritoDemeritoList,
                    "puntajeMerito" => $puntajeMerito,
                    "detalleMerito" => $detalleMerito
                ];

                $meritoDemeritoData[] = $weekData;
            }
        }

        $pdf = \PDF::loadView(
            'report.control-merito-demerito',
            [
                'meritoDemeritoData'=> $meritoDemeritoData,
                'cadete'=> $cadete->toArray(),
                'jefeDeSeccion'=> $jefeDeSeccion->toArray(),
                'comandanteEscuadron'=> $comandanteEscuadron->toArray()
            ]
        )->setPaper('letter', 'landscape');

        $now = new Carbon();

        return $pdf->download('control_meritos_demeritos_'.$now->format('Y-m-d-H:i:s').'.pdf');
    }
}
