<?php

namespace App\Http\Controllers\Api;


use App\Cadete;
use App\Http\Controllers\Controller;
use App\Models\Merito;
use App\Models\Disciplina;
use App\Services\CadeteService;
use App\Services\EncargadoService;
use App\Services\MeritoService;
use App\Services\DisciplinaService;
use App\User;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class MeritoController extends Controller
{

    /**
     * @var MeritoService $meritoService
     */
    protected $meritoService;

    /**
     * @var DisciplinaService $disciplinaService
     */
    protected $disciplinaService;

    /**
     * @var CadeteService $cadeteService
     */
    protected $cadeteService;

    /**
     * @var EncargadoService $encargadoService
     */
    protected $encargadoService;

    /**
     * MeritoController constructor.
     * @param MeritoService $meritoService
     * @param DisciplinaService $disciplinaService
     * @param CadeteService $cadeteService
     * @param EncargadoService $encargadoService
     */
    public function __construct(MeritoService $meritoService, DisciplinaService $disciplinaService,
                                CadeteService $cadeteService, EncargadoService $encargadoService)
    {
        $this->meritoService = $meritoService;
        $this->disciplinaService = $disciplinaService;
        $this->cadeteService = $cadeteService;
        $this->encargadoService = $encargadoService;
    }

    public function index(Request $request) {
        $apiRes = new ApiResponse('Meritos');
        $meritoList = $this->meritoService->getAll($request->input());

        $apiRes->results['meritos'] = $meritoList;

        $apiRes->results['pagination'] = [
            'total' => $meritoList->total(),
            'current_page' => $meritoList->currentPage(),
            'per_page' => $meritoList->perPage(),
            'last_page' => $meritoList->lastPage(),
            'from' => $meritoList->firstItem(),
            'to' => $meritoList->lastItem(),
        ];

        return response()->json($apiRes);
    }

    public function show($id) {
        /**
         * @var Merito $merito
         */
        $merito = $this->meritoService->getById($id);
        $apiRes = new ApiResponse('Merito');

        if (is_null($merito)) {
            $apiRes->errors->add('general', 'La Merito no se encuentra');
            return response()->json($apiRes, 404);
        }

        $apiRes->results[] = $merito;
        return response()->json($apiRes);
    }

    public function store(Request $request) {
        $user = \Auth::user();
        $apiRes = new ApiResponse('Merito');

        /**
         * Obtener el encargado u oficial por el ID del user
         */
        $encargado = $this->encargadoService->getByUser($user);

        $disciplina = null;
        if ($request->has('disciplinaId')) {
            /**
             * @var Disciplina $disciplina
             */
            $disciplina = $this->disciplinaService->getById($request->input('disciplinaId'));
        }

        if (is_null($disciplina)) {
            $apiRes->errors->add('general', 'El registro Disciplina no fue encontrado');
            return response()->json($apiRes, 404);
        }

        $cadete = null;
        if ($request->has('cadeteId')) {
            /**
             * @var Cadete $cadete
             */
            $cadete = $this->cadeteService->getById($request->input('cadeteId'));
        }

        if (is_null($cadete)) {
            $apiRes->errors->add('general', 'El Cadete no fue encontrado');
            return response()->json($apiRes, 404);
        }

        $merito = $this->meritoService->create($disciplina, $cadete, $request->input(), $encargado);

        if ($this->meritoService->hasErrors()) {
            $apiRes->errors->merge($this->meritoService->getErrors());
            return response()->json($apiRes, 400);
        }

        $apiRes->results[] = $merito;
        return response()->json($apiRes);
    }

    public function update(Request $request, $id) {
        $user = \Auth::user();
        $apiRes = new ApiResponse('Merito');

        /**
         * Obtener el encargado u oficial por el ID del user
         */
        $encargado = $this->encargadoService->getByUser($user);

        $disciplina = null;
        if ($request->has('disciplinaId')) {
            /**
             * @var Disciplina $disciplina
             */
            $disciplina = $this->disciplinaService->getById($request->input('disciplinaId'));
        }

        if (is_null($disciplina)) {
            $apiRes->errors->add('general', 'El registro Disciplina no fue encontrado');
            return response()->json($apiRes, 404);
        }

        $cadete = null;
        if ($request->has('cadeteId')) {
            /**
             * @var Cadete $cadete
             */
            $cadete = $this->cadeteService->getById($request->input('cadeteId'));
        }

        if (is_null($cadete)) {
            $apiRes->errors->add('general', 'El Cadete no fue encontrado');
            return response()->json($apiRes, 404);
        }

        $merito = $this->meritoService->getById($id);

        if (is_null($merito)) {
            $apiRes->errors->add('general', 'La Merito no se encuentra');
            return response()->json($apiRes, 404);
        }

        $merito = $this->meritoService->update($merito, $disciplina, $cadete, $request->input(), $encargado);
        if ($this->meritoService->hasErrors()) {
            $apiRes->errors->merge($this->meritoService->getErrors());
            return response()->json($apiRes, 400);
        }

        $apiRes->results[] = $merito;
        return response()->json($apiRes);
    }

    public function destroy($id) {
        $merito = $this->meritoService->getById($id);
        if (is_null($merito)) {
            return response()->json(null, 404);
        }
        $this->meritoService->delete($merito);
        return response()->json(null);
    }
}