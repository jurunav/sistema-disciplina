<?php

namespace App\Services;


use App\Cadete;
use App\Models\Encargado;
use App\Models\Demerito;
use App\Persona;
use App\Repositories\DemeritoRepository;
use App\Sancion;

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
            array_get($data, 'search', null),
            array_get($data, 'criterio', null)
        );
    }

    /**
     * @param Cadete $cadete
     * @param Sancion $sancion
     * @param Persona $sancionador
     * @param Encargado $encargado
     * @param $data
     * @return Demerito
     */
    public function create(Cadete $cadete, Sancion $sancion, Persona $sancionador, Encargado $encargado, $data) {
        $demerito = new Demerito();

        if (array_key_exists('cant_dia', $data)) {
            $demerito->cant_dia = $data['cant_dia'];
        }

        if (array_key_exists('num_orden', $data)) {
            $demerito->num_orden = $data['num_orden'];
        }

        $demerito->cadete()->associate($cadete);
        $demerito->sancion()->associate($sancion);
        $demerito->sancionador()->associate($sancionador);
        $demerito->encargado()->associate($encargado);

        $demerito->save();
        return $demerito;
    }

    /**
     * @param Demerito $demerito
     * @param Sancion $sancion
     * @param Cadete $cadete
     * @param $data
     * @param Encargado $encargado
     * @return Demerito
     */
    public function update(Demerito $demerito, Cadete $cadete, Sancion $sancion, Persona $sancionador,
                           Encargado $encargado, $data
    ) {

        if (array_key_exists('cant_dia', $data)) {
            $demerito->cant_dia = $data['cant_dia'];
        }

        if (array_key_exists('num_orden', $data)) {
            $demerito->num_orden = $data['num_orden'];
        }


        $demerito->cadete()->associate($cadete);
        $demerito->sancion()->associate($sancion);
        $demerito->sancionador()->associate($sancionador);
        $demerito->encargado()->associate($encargado);

        $demerito->save();

        return $demerito;
    }

    public function delete(Demerito $demerito) {
        $demerito->delete();
    }
}