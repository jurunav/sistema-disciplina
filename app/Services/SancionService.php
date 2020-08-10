<?php

namespace App\Services;

use App\Sancion;
use App\Repositories\SancionRepository;

class SancionService extends BaseService
{

    /**
     * @var SancionRepository $sancionRepository
     */
    protected $sancionRepository;

    /**
     * SancionService constructor.
     * @param SancionRepository $sancionRepository
     */
    public function __construct(SancionRepository $sancionRepository)
    {
        parent::__construct();
        $this->sancionRepository = $sancionRepository;
    }

    public function getById($id) {
        return $this->sancionRepository->getById($id);
    }

    /**
     * @param array $data
     * @return Sancion[]|\Illuminate\Support\Collection
     */
    public function getAll($data) {
        return $this->sancionRepository->getAll(
            array_get($data, 'limit', 10),
            array_get($data, 'offset', 0),
            array_get($data, 'order', [['col' => 's.created_at', 'dir' => 'desc']]),
            array_get($data, 'search', null),
            array_get($data, 'criterio', 'nombre')
        );
    }

}