<?php

namespace App\Models;

use App\Cadete;
use App\Persona;

class Demerito extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'demeritos';

    /**
     * Demerito constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function cadete() {
        return $this->belongsTo(Cadete::class);
    }

    public function disciplina(){
        return $this->belongsTo(Disciplina::class);
    }

    public function sancionador(){
        return $this->belongsTo(Persona::class);
    }

    public function encargado(){
        return $this->belongsTo(Encargado::class);
    }

    public function toArray()
    {
        $array = parent::attributesToArray();
        $array['cadete'] = $this->cadete;
        $array['disciplina'] = $this->disciplina;
        $array['sancionador'] = $this->persona;
        $array['encargado'] = $this->encargado;
        return $array;
    }
}