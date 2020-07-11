<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable = ['grado','nombre','ci','cm','domicilio','celular','email','condicion'];

    public function cadete()
    {
        return $this->hasOne('App\Cadete');
    }
}
