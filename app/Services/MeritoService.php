<?php

namespace App\Services;


use App\Cadete;
use App\Models\Disciplina;
use App\Models\Encargado;
use App\Models\Merito;
use App\Repositories\MeritoRepository;
use Illuminate\Support\Facades\Validator;

class MeritoService extends BaseService
{

    /**
     * @var MeritoRepository $meritoRepository
     */
    protected $meritoRepository;

    /**
     * MeritoService constructor.
     * @param MeritoRepository $meritoRepository
     */
    public function __construct(MeritoRepository $meritoRepository)
    {
        parent::__construct();
        $this->meritoRepository = $meritoRepository;
    }

    public function getById($id) {
        return $this->meritoRepository->getById($id);
    }

    /**
     * @param array $data
     * @return Merito[]|\Illuminate\Support\Collection
     */
    public function getAll($data) {
        return $this->meritoRepository->getAll(
            array_get($data, 'limit', 10),
            array_get($data, 'offset', 0),
            array_get($data, 'order', [['col' => 'm.created_at', 'dir' => 'desc']]),
            array_get($data, 'search', null),
            array_get($data, 'criterio', null)
        );
    }

    /**
     * @param Disciplina $disciplina
     * @param Cadete $cadete
     * @param $data
     * @param Encargado|null $encargado
     * @return Merito|null
     *
     */
    public function create(Disciplina $disciplina, Cadete $cadete, $data, Encargado $encargado) {
        $merito = new Merito();
        $merito->num_orden = $data['num_orden'];
        $merito->disciplina()->associate($disciplina);
        $merito->encargado()->associate($encargado);
        $merito->cadete()->associate($cadete);
        $merito->save();
        return $merito;
    }

    /**
     * @param Merito $merito
     * @param Disciplina $disciplina
     * @param Cadete $cadete
     * @param $data
     * @param Encargado $encargado
     * @return Merito
     */
    public function update(Merito $merito, Disciplina $disciplina, Cadete $cadete, $data, Encargado $encargado) {
        $merito->num_orden = $data['num_orden'];
        $merito->disciplina()->associate($disciplina);
        $merito->encargado()->associate($encargado);
        $merito->cadete()->associate($cadete);
        $merito->save();
        return $merito;
    }

    public function delete(Merito $merito) {
        $merito->delete();
    }
}