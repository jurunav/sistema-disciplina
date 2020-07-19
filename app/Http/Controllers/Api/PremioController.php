<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Services\PremioService;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class PremioController extends Controller
{

    /**
     * @var PremioService $premioService
     */
    protected $premioService;

    /**
     * PremioController constructor.
     * @param PremioService $premioService
     */
    public function __construct(PremioService $premioService)
    {
        $this->premioService = $premioService;
    }

    public function index(Request $request) {
        $apiRes = new ApiResponse('Premios');
        $premioList = $this->premioService->getAll($request->input());

        $apiRes->results['premios'] = $premioList;

        $apiRes->results['pagination'] = [
            'total' => $premioList->total(),
            'current_page' => $premioList->currentPage(),
            'per_page' => $premioList->perPage(),
            'last_page' => $premioList->lastPage(),
            'from' => $premioList->firstItem(),
            'to' => $premioList->lastItem(),
        ];

        return response()->json($apiRes);
    }
}