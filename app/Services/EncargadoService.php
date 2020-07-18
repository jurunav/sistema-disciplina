<?php

namespace App\Services;


use App\Models\Encargado;
use App\Repositories\EncargadoRepository;

class EncargadoService extends BaseService
{

    /**
     * @var EncargadoRepository $encargadoRepository
     */
    protected $encargadoRepository;

    /**
     * @var PersonaService $personaService
     */
    protected $personaService;

    /**
     * EncargadoService constructor.
     * @param EncargadoRepository $encargadoRepository
     * @param PersonaService $personaService
     */
    public function __construct(EncargadoRepository $encargadoRepository, PersonaService $personaService)
    {
        parent::__construct();
        $this->encargadoRepository = $encargadoRepository;
        $this->personaService = $personaService;
    }

    public function getById($id) {
        return $this->encargadoRepository->getById($id);
    }

    public function getByPersonaId($id) {
        return $this->encargadoRepository->getByPersonaId($id);
    }

    /**
     * @param array $data
     * @return Encargado[]|\Illuminate\Support\Collection
     */
    public function getAll($data) {
        return $this->encargadoRepository->getAll(
            array_get($data, 'limit', 10),
            array_get($data, 'offset', 0),
            array_get($data, 'order', [['col' => 'e.created_at', 'dir' => 'desc']]),
            array_get($data, 'buscar', null),
            array_get($data, 'criterio', null)
        );
    }

    public function create($data) {
        $persona = $this->personaService->create($data);
        if ($this->personaService->hasErrors()) {
            $this->errors->merge($this->personaService->getErrors());
        }
        $encargado = null;
        if (!$this->hasErrors()) {
            $encargado = new Encargado();
            $encargado->persona()->associate($persona);
            $encargado->save();
        }
        return $encargado;
    }

    /**
     * @param Encargado $encargado
     * @param $data
     * @return Encargado
     */
    public function update(Encargado $encargado, $data) {
        $persona = $this->personaService->update($encargado->persona, $data);

        if ($this->personaService->hasErrors()) {
            $this->errors->merge($this->personaService->getErrors());
        }

        if (!$this->hasErrors()) {
            $encargado->persona()->associate($persona);
            $encargado->save();
        }
        return $encargado;
    }

    public function delete(Encargado $encargado) {
        $encargado->delete();
    }
}