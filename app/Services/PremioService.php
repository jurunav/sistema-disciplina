<?php

namespace App\Services;

use App\Premio;
use App\Repositories\PremioRepository;

class PremioService extends BaseService
{

    /**
     * @var PremioRepository $premioRepository
     */
    protected $premioRepository;

    /**
     * PremioService constructor.
     * @param PremioRepository $premioRepository
     */
    public function __construct(PremioRepository $premioRepository)
    {
        parent::__construct();
        $this->premioRepository = $premioRepository;
    }

    public function getById($id) {
        return $this->premioRepository->getById($id);
    }

    /**
     * @param array $data
     * @return Premio[]|\Illuminate\Support\Collection
     */
    public function getAll($data) {
        return $this->premioRepository->getAll(
            array_get($data, 'limit', 10),
            array_get($data, 'offset', 0),
            array_get($data, 'order', [['col' => 'p.created_at', 'dir' => 'desc']]),
            array_get($data, 'buscar', null),
            array_get($data, 'criterio', null)
        );
    }

}