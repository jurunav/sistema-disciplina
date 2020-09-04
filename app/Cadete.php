<?php

namespace App;

use App\Models\BaseModel;
use App\Models\Merito;
use Carbon\Carbon;

class Cadete extends BaseModel
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cadetes';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['year_ingreso'];

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

    public function year() {
        $yearIngreso = $this->year_ingreso->diffInYears(Carbon::now());
        $yearIngreso = $yearIngreso + 1;
        return $yearIngreso;
    }

    public function toArray()
    {
        $array = parent::attributesToArray();
        $array['nombre_original'] =  $this->persona->nombre;
        $nombreCadete = $this->persona->getGradoNombre($this->year()) . " " . $this->persona->nombre;
        $array['nombre'] =  $nombreCadete;
        $array['persona'] = $this->persona;
        $array['year_cadete'] = $this->year();
        return $array;
    }
}
