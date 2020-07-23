<?php

namespace App\Services;


use App\Cadete;
use App\Models\Disciplina;
use App\Models\Encargado;
use App\Models\Demerito;
use App\Persona;
use App\Repositories\DemeritoRepository;

class DemeritoService extends BaseService
{

    /**
     * @var DemeritoRepository $demeritoRepository
     */
    protected $demeritoRepository;

    /**
     * DemeritoService constructor.
     * @param DemeritoRepository $demeritoRepository
     */
    public function __construct(DemeritoRepository $demeritoRepository)
    {
        parent::__construct();
        $this->demeritoRepository = $demeritoRepository;
    }

    public function getById($id) {
        return $this->demeritoRepository->getById($id);
    }

    /**
     * @param array $data
     * @return Demerito[]|\Illuminate\Support\Collection
     */
    public function getAll($data) {
        return $this->demeritoRepository->getAll(
            array_get($data, 'limit', 10),
            array_get($data, 'offset', 0),
            array_get($data, 'order', [['col' => 'd.created_at', 'dir' => 'desc']]),
            array_get($data, 'buscar', null),
            array_get($data, 'criterio', null)
        );
    }

    /**
     * @param Cadete $cadete
     * @param Disciplina $disciplina
     * @param Persona $sancionador
     * @param Encargado $encargado
     * @param $data
     * @return Demerito
     */
    public function create(Cadete $cadete, Disciplina $disciplina, Persona $sancionador, Encargado $encargado, $data) {
        $demerito = new Demerito();

        if (array_key_exists('can_dia', $data)) {
            $demerito->num_orden = $data['can_dia'];
        }

        if (array_key_exists('num_orden', $data)) {
            $demerito->num_orden = $data['num_orden'];
        }

        $demerito->cadete()->associate($cadete);
        $demerito->disciplina()->associate($disciplina);
        $demerito->sancionador()->associate($sancionador);
        $demerito->encargado()->associate($encargado);

        $demerito->save();
        return $demerito;
    }

    /**
     * @param Demerito $demerito
     * @param Disciplina $disciplina
     * @param Cadete $cadete
     * @param $data
     * @param Encargado $encargado
     * @return Demerito
     */
    public function update(Demerito $demerito, Cadete $cadete, Disciplina $disciplina, Persona $sancionador,
                           Encargado $encargado, $data
    ) {

        if (array_key_exists('can_dia', $data)) {
            $demerito->num_orden = $data['can_dia'];
        }

        if (array_key_exists('num_orden', $data)) {
            $demerito->num_orden = $data['num_orden'];
        }


        $demerito->cadete()->associate($cadete);
        $demerito->disciplina()->associate($disciplina);
        $demerito->sancionador()->associate($sancionador);
        $demerito->encargado()->associate($encargado);

        $demerito->save();

        return $demerito;
    }

    public function delete(Demerito $demerito) {
        $demerito->delete();
    }
}