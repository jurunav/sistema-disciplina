<?php

namespace App\Http\Controllers\Api;


use App\Categoria;
use App\Http\Controllers\Controller;
use App\Models\Disciplina;
use App\Premio;
use App\Services\CategoriaService;
use App\Services\DisciplinaService;
use App\Services\PremioService;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class DisciplinaController extends Controller
{

    /**
     * @var DisciplinaService $disciplinaService
     */
    protected $disciplinaService;

    /**
     * @var PremioService $premioService
     */
    protected $premioService;

    /**
     * @var CategoriaService $categoriaService
     */
    protected $categoriaService;

    /**
     * DisciplinaController constructor.
     * @param DisciplinaService $disciplinaService
     * @param PremioService $premioService
     * @param CategoriaService $categoriaService
     */
    public function __construct(DisciplinaService $disciplinaService, PremioService $premioService, CategoriaService $categoriaService)
    {
        $this->disciplinaService = $disciplinaService;
        $this->premioService = $premioService;
        $this->categoriaService = $categoriaService;
    }

    public function index(Request $request) {
        $apiRes = new ApiResponse('Disciplinas');
        $disciplinaList = $this->disciplinaService->getAll($request->input());

        $apiRes->results['disciplinas'] = $disciplinaList;

        $apiRes->results['pagination'] = [
            'total'        => $disciplinaList->total(),
            'current_page' => $disciplinaList->currentPage(),
            'per_page'     => $disciplinaList->perPage(),
            'last_page'    => $disciplinaList->lastPage(),
            'from'         => $disciplinaList->firstItem(),
            'to'           => $disciplinaList->lastItem(),
        ];

        return response()->json($apiRes);
    }

    public function show($id) {
        /**
         * @var Disciplina $disciplina
         */
        $disciplina = $this->disciplinaService->getById($id);
        $apiRes = new ApiResponse('Disciplina');

        if (is_null($disciplina)) {
            $apiRes->errors->add('general', 'La Disciplina no se encuentra');
            return response()->json($apiRes, 404);
        }

        $apiRes->results[] = $disciplina;
        return response()->json($apiRes);
    }

    public function store(Request $request) {
        $premio = null;
        if ($request->has('premio')) {
            /**
             * @var Premio $premio
             */
            $premio = $this->premioService->getById($request->input('premio'));
        }

        $apiRes = new ApiResponse('Disciplina');

        if (is_null($premio)) {
            $apiRes->errors->add('general', 'El registro Premio no fue encontrado');
            return response()->json($apiRes, 404);
        }

        $categoria = null;
        if ($request->has('categoria')) {
            /**
             * @var Categoria $categoria
             */
            $categoria = $this->categoriaService->getById($request->input('categoria'));
        }

        $disciplina = $this->disciplinaService->create($premio, $request->input(), $categoria);

        if ($this->disciplinaService->hasErrors()) {
            $apiRes->errors->merge($this->disciplinaService->getErrors());
            return response()->json($apiRes, 400);
        }

        $apiRes->results[] = $disciplina;
        return response()->json($apiRes);
    }

    public function update(Request $request, $id) {
        $premio = null;
        if ($request->has('premio')) {
            /**
             * @var Premio $premio
             */
            $premio = $this->premioService->getById($request->input('premio'));
        }

        $apiRes = new ApiResponse('Disciplina');

        if (is_null($premio)) {
            $apiRes->errors->add('general', 'El registro Premio no fue encontrado');
            return response()->json($apiRes, 404);
        }

        $categoria = null;
        if ($request->has('categoria')) {
            /**
             * @var Categoria $categoria
             */
            $categoria = $this->categoriaService->getById($request->input('categoria'));
        }

        $disciplina = $this->disciplinaService->getById($id);

        if (is_null($disciplina)) {
            $apiRes->errors->add('general', 'La Disciplina no se encuentra');
            return response()->json($apiRes, 404);
        }

        $disciplina = $this->disciplinaService->update($disciplina, $premio, $request->input(), $categoria);
        if ($this->disciplinaService->hasErrors()) {
            $apiRes->errors->merge($this->disciplinaService->getErrors());
            return response()->json($apiRes, 400);
        }

        $apiRes->results[] = $disciplina;
        return response()->json($apiRes);
    }

    public function destroy($id) {
        $disciplina = $this->disciplinaService->getById($id);
        if (is_null($disciplina)) {
            return response()->json(null, 404);
        }
        $this->disciplinaService->delete($disciplina);
        return response()->json(null);
    }
}