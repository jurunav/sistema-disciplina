<?php

namespace App\Models;

use App\Cadete;

class Merito extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meritos';

    /**
     * Disciplina constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function disciplina(){
        return $this->belongsTo(Disciplina::class);
    }

    public function encargado(){
        return $this->belongsTo(Encargado::class);
    }

    public function cadete() {
        return $this->belongsTo(Cadete::class);
    }

    public function toArray()
    {
        $array = parent::attributesToArray();
        $array['disciplina'] = $this->disciplina;
        $array['encargado'] = $this->encargado;
        $array['cadete'] = $this->cadete;
        return $array;
    }
}
