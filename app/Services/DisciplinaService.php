<?php

namespace App\Services;


use App\Categoria;
use App\Models\Disciplina;
use App\Premio;
use App\Repositories\DisciplinaRepository;
use Illuminate\Support\Facades\Validator;

class DisciplinaService extends BaseService
{

    /**
     * @var DisciplinaRepository $disciplinaRepository
     */
    protected $disciplinaRepository;

    /**
     * DisciplinaService constructor.
     * @param DisciplinaRepository $disciplinaRepository
     */
    public function __construct(DisciplinaRepository $disciplinaRepository)
    {
        parent::__construct();
        $this->disciplinaRepository = $disciplinaRepository;
    }

    public function getById($id) {
        return $this->disciplinaRepository->getById($id);
    }

    /**
     * @param array $data
     * @return Disciplina[]|\Illuminate\Support\Collection
     */
    public function getAll($data) {
        return $this->disciplinaRepository->getAll(
            array_get($data, 'limit', 10),
            array_get($data, 'offset', 0),
            array_get($data, 'order', [['col' => 'd.created_at', 'dir' => 'desc']]),
            array_get($data, 'buscar', null),
            array_get($data, 'criterio', 'nombre')
        );
    }

    /**
     * @param Premio $premio
     * @param $data
     * @param Categoria|null $categoria
     * @return Disciplina|null
     */
    public function create(Premio $premio, $data, Categoria $categoria = null) {
        $validatorRules = [
            'nombre' => 'required|string|max:255',
            'puntaje' => 'required|numeric',
        ];

        /**
         * @var \Illuminate\Validation\Validator $validator
         */
        $validator = Validator::make($data, $validatorRules);
        if($validator->fails()) {
            $this->errors->merge($validator->errors());
        }

        $disciplina = null;
        if (!$this->hasErrors()) {
            $disciplina = new Disciplina();
            $disciplina->nombre = $data['nombre'];
            $disciplina->puntaje = $data['puntaje'];
            $disciplina->premio()->associate($premio);
            if (!is_null($categoria)) {
                $disciplina->categoria()->associate($categoria);
            }
            $disciplina->save();
        }
        return $disciplina;
    }

    /**
     * @param Disciplina $disciplina
     * @param Premio $premio
     * @param $data
     * @param Categoria|null $categoria
     * @return Disciplina
     */
    public function update(Disciplina $disciplina, Premio $premio, $data, Categoria $categoria = null) {
        $validatorRules = [
            'nombre' => 'filled|string|max:255',
            'puntaje' => 'filled|numeric',
        ];

        /**
         * @var \Illuminate\Validation\Validator $validator
         */
        $validator = Validator::make($data, $validatorRules);
        if($validator->fails()) {
            $this->errors->merge($validator->errors());
        }

        if (!$this->hasErrors()) {
            $disciplina->nombre = $data['nombre'];
            $disciplina->puntaje = $data['puntaje'];
            $disciplina->premio()->associate($premio);
            if (!is_null($categoria)) {
                $disciplina->categoria()->associate($categoria);
            }
            $disciplina->save();
        }
        return $disciplina;
    }

    public function delete(Disciplina $disciplina) {
        $disciplina->delete();
    }
}