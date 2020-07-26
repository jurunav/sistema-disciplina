<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Services\SancionService;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class SancionController extends Controller
{

    /**
     * @var SancionService $sancionService
     */
    protected $sancionService;

    /**
     * SancionController constructor.
     * @param SancionService $sancionService
     */
    public function __construct(SancionService $sancionService)
    {
        $this->sancionService = $sancionService;
    }

    public function index(Request $request) {
        $apiRes = new ApiResponse('Sanciones');
        $sancionList = $this->sancionService->getAll($request->input());

        $apiRes->results['sanciones'] = $sancionList;

        $apiRes->results['pagination'] = [
            'total' => $sancionList->total(),
            'current_page' => $sancionList->currentPage(),
            'per_page' => $sancionList->perPage(),
            'last_page' => $sancionList->lastPage(),
            'from' => $sancionList->firstItem(),
            'to' => $sancionList->lastItem(),
        ];

        return response()->json($apiRes);
    }
}