<?php

namespace App\Services;


use App\Persona;
use App\Repositories\PersonaRepository;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rule;

class PersonaService extends BaseService
{

    /**
     * @var PersonaRepository $personaRepository
     */
    protected $personaRepository;

    /**
     * @var MessageBag
     */
    public $errors;

    /**
     * UserService constructor.
     * @param PersonaRepository $personaRepository
     */
    public function __construct(PersonaRepository $personaRepository)
    {
        parent::__construct();
        $this->personaRepository = $personaRepository;
        $this->errors = new MessageBag();
    }

    public function getByUser(User $user) {
        return $this->personaRepository->getByUser($user);
    }

    public function getById($id) {
        return $this->personaRepository->getById($id);
    }

    public function getAll() {
        return $this->personaRepository->getAll();
    }

    public function create($data, User $user = null) {
        $validatorRules = [
            'grado' => 'required|string|max:20',
            'nombre' => 'required|string|max:250|unique:'.Persona::getTableName(),
            'ci' => 'required|string|max:20',
        ];

        /**
         * @var \Illuminate\Validation\Validator $validator
         */
        $validator = Validator::make($data, $validatorRules);
        if($validator->fails()) {
            $this->errors->merge($validator->errors());
        }

        $persona = null;

        if ($this->errors->count() == 0) {
            $persona = new Persona();
            $persona->grado = $data['grado'];
            $persona->nombre = $data['nombre'];
            $persona->ci = $data['ci'];

            if (array_key_exists('cm', $data)){
                $persona->cm = $data['cm'];
            }

            if (array_key_exists('domicilio', $data)){
                $persona->domicilio = $data['domicilio'];
            }

            if (array_key_exists('celular', $data)){
                $persona->celular = $data['celular'];
            }

            if (array_key_exists('email', $data)){
                $persona->email = $data['email'];
            }

            if (!is_null($user)) {
                $persona->user()->associate($user);
            }

            $persona->save();
        }
        return $persona;
    }

    public function update(Persona $persona, $data, User $user = null) {
        $validatorRules = [
            'grado' => 'filled|string|max:20',
            'ci' => 'filled|string|max:20',
            'nombre' => [
                'filled',
                'string',
                'max:250',
                Rule::unique('personas')->ignore($persona->id)
            ]
        ];

        /**
         * @var \Illuminate\Validation\Validator $validator
         */
        $validator = Validator::make($data, $validatorRules);

        if($validator->fails()) {
            $this->errors->merge($validator->errors());
        }

        if ($this->errors->count() == 0) {

            $persona->grado = $data['grado'];
            $persona->nombre = $data['nombre'];
            $persona->ci = $data['ci'];

            if (array_key_exists('cm', $data)){
                $persona->cm = $data['cm'];
            }

            if (array_key_exists('domicilio', $data)){
                $persona->domicilio = $data['domicilio'];
            }

            if (array_key_exists('celular', $data)){
                $persona->celular = $data['celular'];
            }

            if (array_key_exists('email', $data)){
                $persona->email = $data['email'];
            }

            if (!is_null($user)) {
                $persona->user()->associate($user);
            }

            $persona->save();
        }
        return $persona;
    }

    public function delete(Persona $persona) {
        $persona->delete();
    }

    public function getByEmail($email) {
        return $this->personaRepository->getByEmail($email);
    }
}