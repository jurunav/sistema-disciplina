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
        $cadeteList = $this->cadeteService->getAllFrancoDeHonor($request->input());
        $cadeteCount = $this->cadeteService->countAllFrancoDeHonor($request->input());
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
                'cadeteCount'=>$cadeteCount
            ]
        )->setPaper('letter');

        $now = new Carbon();

        return $pdf->download('franco_de_honor_'.$now->format('Y-m-d-H:i:s').'.pdf');
    }
}
