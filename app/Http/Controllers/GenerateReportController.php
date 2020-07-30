<?php

namespace App\Http\Controllers;

use App\Services\CadeteService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GenerateReportController extends Controller
{
    /**
     * @var CadeteService $cadeteService
     */
    protected $cadeteService;

    /**
     * GenerateReportController constructor.
     * @param CadeteService $cadeteService
     */
    public function __construct(CadeteService $cadeteService)
    {
        $this->cadeteService = $cadeteService;
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
}
