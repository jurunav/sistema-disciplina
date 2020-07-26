<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Services\PersonaService;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class PersonaController extends Controller
{

    /**
     * @var PersonaService $personaService
     */
    protected $personaService;

    /**
     * PersonaController constructor.
     * @param PersonaService $personaService
     */
    public function __construct(PersonaService $personaService)
    {
        $this->personaService = $personaService;
    }

    public function index(Request $request) {
        $apiRes = new ApiResponse('Personas');
        $personaList = $this->personaService->getAll($request->input());

        $apiRes->results['personas'] = $personaList;

        $apiRes->results['pagination'] = [
            'total' => $personaList->total(),
            'current_page' => $personaList->currentPage(),
            'per_page' => $personaList->perPage(),
            'last_page' => $personaList->lastPage(),
            'from' => $personaList->firstItem(),
            'to' => $personaList->lastItem(),
        ];

        return response()->json($apiRes);
    }
}