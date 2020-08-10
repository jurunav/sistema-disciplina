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

    public $timestamps = false;
    
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
        return $this->belongsTo(Persona::class, 'persona_id', 'id');
    }

    public function meritos() {
        return $this->hasMany(Merito::class, 'cadete_id', 'id');
    }

    public function toArray()
    {
        $array = parent::attributesToArray();
        $nombreCadete = $this->persona->getGradoNombre($this->year_ingreso) . " " . $this->persona->nombre;
        $array['nombre'] =  $nombreCadete;
        $array['persona'] = $this->persona;
        return $array;
    }
}
