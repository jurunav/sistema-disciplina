<?php

namespace App\Services;

use App\Categoria;
use App\Repositories\CategoriaRepository;

class CategoriaService extends BaseService
{

    /**
     * @var CategoriaRepository $categoriaRepository
     */
    protected $categoriaRepository;

    /**
     * CategoriaService constructor.
     * @param CategoriaRepository $categoriaRepository
     */
    public function __construct(CategoriaRepository $categoriaRepository)
    {
        parent::__construct();
        $this->categoriaRepository = $categoriaRepository;
    }

    public function getById($id) {
        return $this->categoriaRepository->getById($id);
    }

    /**
     * @param array $data
     * @return Categoria[]|\Illuminate\Support\Collection
     */
    public function getAll($data) {
        return $this->categoriaRepository->getAll(
            array_get($data, 'limit', 10),
            array_get($data, 'offset', 0),
            array_get($data, 'order', [['col' => 'c.created_at', 'dir' => 'desc']]),
            array_get($data, 'buscar', null),
            array_get($data, 'criterio', null)
        );
    }

}