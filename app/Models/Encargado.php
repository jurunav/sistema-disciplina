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

    public function persona(){
        return $this->belongsTo(Persona::class);
    }

}
