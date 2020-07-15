<?php

namespace App;

use App\Models\BaseModel;

class Persona extends BaseModel
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'personas';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function cadete(){
        return $this->hasOne(Cadete::class);
    }

    public function encargado(){
        return $this->hasOne(Cadete::class);
    }
}
