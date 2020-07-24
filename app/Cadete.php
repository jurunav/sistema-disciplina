<?php

namespace App;

use App\Models\BaseModel;
use App\Models\Merito;

class Cadete extends BaseModel
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cadetes';

    /**
     * Cadete constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function persona()
    {
        return $this->belongsTo('App\Persona');
    }

    public function meritos() {
        return $this->hasMany(Merito::class, 'cadete_id', 'id');
    }

    public function toArray()
    {
        $array = parent::attributesToArray();
        $array['nombre'] = $this->persona->nombre;
        $array['persona'] = $this->persona;
        return $array;
    }
}
