<?php

namespace App\Services;

use App\Repositories\CadeteRepository;
use App\Cadete;

class CadeteService extends BaseService
{

    /**
     * @var CadeteRepository $cadeteRepository
     */
    protected $cadeteRepository;

    /**
     * CadeteService constructor.
     * @param CadeteRepository $cadeteRepository
     */
    public function __construct(CadeteRepository $cadeteRepository)
    {
        parent::__construct();
        $this->cadeteRepository = $cadeteRepository;
    }

    public function getById($id) {
        return $this->cadeteRepository->getById($id);
    }

    /**
     * @param array $data
     * @return \Illuminate\Support\Collection|Cadete[]
     */
    public function getAllByFilter($data) {
        return $this->cadeteRepository->getAllByFilter(
            array_get($data, 'limit', 10),
            array_get($data, 'offset', 0),
            array_get($data, 'order', [['col' => 'c.year_ingreso', 'dir' => 'desc']]),
            array_get($data, 'search', null)
        );
    }

    /**
     * @param array $filters
     * @return Cadete[]|\Illuminate\Support\Collection
     */
    public function getAllFrancoDeHonor($filters) {
        return $this->cadeteRepository->getAllFrancoDeHonor($filters);
    }

    public function countAllFrancoDeHonor($filters) {
        return $this->cadeteRepository->countAllFrancoDeHonor($filters);
    }

    public function getAllDemeritoByFilter(Cadete $cadete, $filters) {
        return $this->cadeteRepository->getAllDemeritoByFilter($cadete, $filters);
    }

}