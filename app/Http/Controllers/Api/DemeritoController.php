<?php

namespace App\Http\Controllers\Api;


use App\Cadete;
use App\Http\Controllers\Controller;
use App\Models\Merito;
use App\Models\Disciplina;
use App\Persona;
use App\Services\CadeteService;
use App\Services\EncargadoService;
use App\Services\MeritoService;
use App\Services\DisciplinaService;
use App\Services\PersonaService;
use App\User;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class MeritoController extends Controller
{

    /**
     * @var DedemeritoService $demeritoService
     */
    protected $demeritoService;

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
     * @var PersonaService $personaService
     */
    protected $personaService;

    /**
     * MeritoController constructor.
     * @param MeritoService $demeritoService
     * @param DisciplinaService $disciplinaService
     * @param CadeteService $cadeteService
     * @param EncargadoService $encargadoService
     * @param PersonaService $personaService
     */
    public function __construct(MeritoService $demeritoService, DisciplinaService $disciplinaService,
                                CadeteService $cadeteService, EncargadoService $encargadoService,
                                PersonaService $personaService)
    {
        $this->demeritoService = $demeritoService;
        $this->disciplinaService = $disciplinaService;
        $this->cadeteService = $cadeteService;
        $this->encargadoService = $encargadoService;
        $this->personaService = $personaService;
    }

    public function index(Request $request) {
        $apiRes = new ApiResponse('Meritos');
        $demeritoList = $this->demeritoService->getAll($request->input());

        $apiRes->results['demeritos'] = $demeritoList;

        $apiRes->results['pagination'] = [
            'total' => $demeritoList->total(),
            'current_page' => $demeritoList->currentPage(),
            'per_page' => $demeritoList->perPage(),
            'last_page' => $demeritoList->lastPage(),
            'from' => $demeritoList->firstItem(),
            'to' => $demeritoList->lastItem(),
        ];

        return response()->json($apiRes);
    }

    public function show($id) {
        /**
         * @var Merito $demerito
         */
        $demerito = $this->demeritoService->getById($id);
        $apiRes = new ApiResponse('Merito');

        if (is_null($demerito)) {
            $apiRes->errors->add('general', 'El demerito no se encuentra');
            return response()->json($apiRes, 404);
        }

        $apiRes->results[] = $demerito;
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

        $sancionador = null;
        if ($request->has('sancionadorId')) {
            /**
             * @var Persona $sancionador
             */
            $sancionador = $this->personaService->getById($request->input('sancionadorId'));
        }

        if (is_null($sancionador)) {
            $apiRes->errors->add('general', 'El Sancionador no fue encontrado');
            return response()->json($apiRes, 404);
        }

        $demerito = $this->demeritoService->create($cadete, $disciplina, $sancionador, $encargado, $request->input());

        if ($this->demeritoService->hasErrors()) {
            $apiRes->errors->merge($this->demeritoService->getErrors());
            return response()->json($apiRes, 400);
        }

        $apiRes->results[] = $demerito;
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

        $sancionador = null;
        if ($request->has('sancionadorId')) {
            /**
             * @var Persona $sancionador
             */
            $sancionador = $this->personaService->getById($request->input('sancionadorId'));
        }

        if (is_null($sancionador)) {
            $apiRes->errors->add('general', 'El Sancionador no fue encontrado');
            return response()->json($apiRes, 404);
        }

        $demerito = $this->demeritoService->getById($id);

        if (is_null($demerito)) {
            $apiRes->errors->add('general', 'El demerito no se encuentra');
            return response()->json($apiRes, 404);
        }

        $demerito = $this->demeritoService->update($demerito, $cadete, $disciplina, $sancionador, $encargado, $request->input());
        if ($this->demeritoService->hasErrors()) {
            $apiRes->errors->merge($this->demeritoService->getErrors());
            return response()->json($apiRes, 400);
        }

        $apiRes->results[] = $demerito;
        return response()->json($apiRes);
    }

    public function destroy($id) {
        $demerito = $this->demeritoService->getById($id);
        if (is_null($demerito)) {
            return response()->json(null, 404);
        }
        $this->demeritoService->delete($demerito);
        return response()->json(null);
    }
}