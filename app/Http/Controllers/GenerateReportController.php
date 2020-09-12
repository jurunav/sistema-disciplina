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
                "nombre" => "FRANCO DE HONOR",
                "code" => "franco_de_honor",
                "titular" => "RELACION NOMINAL DE LAS DAMAS Y CABALLEROS CADETES QUE TIENEN SALIDA DE FRANCO",
                "puntaje" => config('services.salidas.franco_de_honor'),
                "type" => "salida"
            ],
            [
                "nombre" => "FRANCO",
                "code" => "franco_domingo",
                "titular" => "RELACION NOMINAL DE LAS DAMAS Y CABALLEROS CADETES QUE TIENEN SALIDA DE FRANCO",
                "puntaje" => [
                    "min" => config('services.salidas.franco_domingo.min'),
                    "max" => config('services.salidas.franco_domingo.max')
                ],
                "type" => "salida"
            ],
            [
                "nombre" => "1/2 DOMINGO",
                "code" => "franco_medio_domingo",
                "titular" => "RELACION NOMINAL DE LAS DAMAS Y CABALLEROS CADETES QUE TIENEN SALIDA DE FRANCO",
                "puntaje" => [
                    "min" => config('services.salidas.franco_medio_domingo.min'),
                    "max" => config('services.salidas.franco_medio_domingo.max')
                ],
                "type" => "salida"
            ],
            [
                "nombre" => "DOMINGO",
                "code" => "sin_salida",
                "titular" => "RELACION NOMINAL DE LAS DAMAS Y CABALLEROS CADETES QUE TIENEN UN DOMINGO DE ARRESTO",
                "puntaje" => config('services.salidas.sin_salida'),
                "type" => "arresto"
            ],
            [
                "nombre" => "CADETES SANCIONADOS",
                "code" => "sancion_orden_dia",
                "titular" => "RELACION NOMINAL DE LAS DAMAS Y CABALLEROS CADETES SANCIONADOS POR ORDEN DEL DÍA",
                "type" => "arresto"
            ],
            [
                "nombre" => "CADETES EN REPOSO",
                "code" => "reposo",
                "titular" => "RELACION NOMINAL DE LAS DAMAS Y CABALLEROS CADETES QUE INGRESARON A REPOSO DURANTE LA SEMANA",
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

        $demeritoData = [];
        $porNumOrdenList = [];
        $porReposoList = [];
        if (!is_null($startDate) && !is_null($endDate)) {
            $weekList = $this->utilDateService->getWeekRangeDate($startDate, $endDate);
            foreach ($weekList as $keyWeek => $week) {
                $meritoDemeritoList = $this->cadeteService->getAllDemeritoByFilter($cadete , ['startDate' => $week[0], 'endDate' => $week[1]]);
                $demeritoList = [];
                foreach ($meritoDemeritoList as $key => $meritoDemerito) {
                    if ($meritoDemerito->por_reposo == false
                        && is_null($meritoDemerito->num_orden)) {
                        $demeritoList[] = $meritoDemerito;
                        $meritoDemeritoList->forget($key);
                    } else if (!is_null($meritoDemerito->num_orden)) {
                        $porNumOrdenList[] = $meritoDemerito;
                        $meritoDemeritoList->forget($key);
                    } else if ($meritoDemerito->por_reposo) {
                        $porReposoList[] = $meritoDemerito;
                        $meritoDemeritoList->forget($key);
                    }
                }

                $countDemerito = 0;
                $demeritoSubTotalPorSemana = 0;
                if (count($demeritoList) > 0) {
                    foreach ($demeritoList as $key => $demerito) {
                        if (!is_null($demerito->demerito) && !is_null($demerito->categoria) && $demerito->categoria !== 'Extraordinario') {
                            if ($cadete->year() >= 4) {
                                $demerito->demerito = 2 * $demerito->demerito;
                            }
                            $countDemerito++;
                            $demeritoSubTotalPorSemana += $demerito->demerito;
                        }
                    }
                }

                $weekData = [
                    "titulo" => "CIERRE DE LIBRO",
                    "fecha" => Carbon::parse($week[0])->format('Y-m-d'),
                    "results" => $demeritoList,
                    "demeritoSubTotalPorSemana" => $demeritoSubTotalPorSemana,
                    "countDemerito" => $countDemerito
                ];

                $demeritoData[] = $weekData;

//                if ($keyWeek == count($weekList) - 1) {
//                    $weekDataFinal = [
//                        "titulo" => "CIERRE DE LIBRO",
//                        "fecha" => Carbon::parse($week[1])->format('Y-m-d'),
//                        "results" => [],
//                        "demeritoSubTotalPorSemana" => 0,
//                    ];
//                    $demeritoData[] = $weekDataFinal;
//                }

            }

        }

        $pdf = \PDF::loadView(
            'report.control-merito-demerito',
            [
                'meritoDemeritoData'=> $demeritoData,
                'porNumOrdenList'=> $porNumOrdenList,
                'porReposoList'=> $porReposoList,
                'cadete'=> $cadete->toArray(),
                'jefeDeSeccion'=> $jefeDeSeccion->toArray(),
                'comandanteEscuadron'=> $comandanteEscuadron->toArray()
            ]
        )->setPaper('letter', 'landscape');

        $now = new Carbon();

        return $pdf->download('control_meritos_demeritos_'.$now->format('Y-m-d-H:i:s').'.pdf');
    }
}
