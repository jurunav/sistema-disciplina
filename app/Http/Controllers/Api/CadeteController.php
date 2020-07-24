<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Services\CadeteService;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class CadeteController extends Controller
{

    /**
     * @var CadeteService $cadeteService
     */
    protected $cadeteService;

    /**
     * CadeteController constructor.
     * @param CadeteService $cadeteService
     */
    public function __construct(CadeteService $cadeteService)
    {
        $this->cadeteService = $cadeteService;
    }

    public function getAllByFilter(Request $request){
        $apiRes = new ApiResponse('Cadetes');
        $cadeteList = $this->cadeteService->getAllByFilter($request->input());
        $apiRes->results = $cadeteList;
        return response()->json($apiRes);
    }
}