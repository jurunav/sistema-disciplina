<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Encargado;
use App\Services\EncargadoService;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class EncargadoController extends Controller
{

    /**
     * @var EncargadoService $encargadoService
     */
    protected $encargadoService;

    /**
     * EncargadoController constructor.
     * @param EncargadoService $encargadoService
     */
    public function __construct(EncargadoService $encargadoService)
    {
        $this->encargadoService = $encargadoService;
    }

    public function index(Request $request) {
        $apiRes = new ApiResponse('Encargados');
        $personaList = $this->encargadoService->getAll($request->input());

        $apiRes->results['personas'] = $personaList;

        $apiRes->results['pagination'] = [
            'total'        => $personaList->total(),
            'current_page' => $personaList->currentPage(),
            'per_page'     => $personaList->perPage(),
            'last_page'    => $personaList->lastPage(),
            'from'         => $personaList->firstItem(),
            'to'           => $personaList->lastItem(),
        ];

        return response()->json($apiRes);
    }

    public function show($id) {
        /**
         * @var Encargado $encargado
         */
        $encargado = $this->encargadoService->getById($id);
        $apiRes = new ApiResponse('Encargado');

        if (is_null($encargado)) {
            $apiRes->errors->add('general', 'el Oficial no se encuentra');
            return response()->json($apiRes, 404);
        }

        $apiRes->results[] = $encargado;
        return response()->json($apiRes);
    }

    public function store(Request $request) {
        $encargado = $this->encargadoService->create($request->input());
        $apiRes = new ApiResponse('Encargado');

        if ($this->encargadoService->hasErrors()) {
            $apiRes->errors->merge($this->encargadoService->getErrors());
            return response()->json($apiRes, 400);
        }

        $apiRes->results[] = $encargado;
        return response()->json($apiRes);
    }

    public function update(Request $request, $id) {
        $encargado = $this->encargadoService->getById($id);
        $apiRes = new ApiResponse('Encargado');

        if (is_null($encargado)) {
            $apiRes->errors->add('general', 'el Oficial no se encuentra');
            return response()->json($apiRes, 404);
        }

        $encargado = $this->encargadoService->update($encargado, $request->input());
        if ($this->encargadoService->hasErrors()) {
            $apiRes->errors->merge($this->encargadoService->getErrors());
            return response()->json($apiRes, 400);
        }

        $apiRes->results[] = $encargado;
        return response()->json($apiRes);
    }

    public function destroy($id) {
        $encargado = $this->encargadoService->getById($id);
        if (is_null($encargado)) {
            return response()->json(null, 404);
        }
        $this->encargadoService->delete($encargado);
        return response()->json(null);
    }
}