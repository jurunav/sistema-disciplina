<?php

namespace App\Models;

use App\Persona;

class Encargado extends Persona
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'encargados';

    /**
     * Persona constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function persona(){
        return $this->belongsTo(Persona::class);
    }

    public function toArray()
    {
        $array = parent::attributesToArray();
        $array['persona'] = $this->persona;
        return $array;
    }
}
