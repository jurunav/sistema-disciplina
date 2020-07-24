<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Services\CategoriaService;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class CategoriaController extends Controller
{

    /**
     * @var CategoriaService $categoriaService
     */
    protected $categoriaService;

    /**
     * CategoriaController constructor.
     * @param CategoriaService $categoriaService
     */
    public function __construct(CategoriaService $categoriaService)
    {
        $this->categoriaService = $categoriaService;
    }

    public function index(Request $request) {
        $apiRes = new ApiResponse('Categorias');
        $categoriaList = $this->categoriaService->getAll($request->input());

        $apiRes->results['categorias'] = $categoriaList;

        $apiRes->results['pagination'] = [
            'total' => $categoriaList->total(),
            'current_page' => $categoriaList->currentPage(),
            'per_page' => $categoriaList->perPage(),
            'last_page' => $categoriaList->lastPage(),
            'from' => $categoriaList->firstItem(),
            'to' => $categoriaList->lastItem(),
        ];

        return response()->json($apiRes);
    }
}