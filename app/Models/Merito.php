<?php

namespace App\Models;

use App\Cadete;
use App\Premio;

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

    public function cadetes() {
        return $this->belongsToMany(
            Cadete::class,
            'cadetes_meritos',
            'merito_id',
            'cadete_id'
        );
    }

    public function toArray()
    {
        $array = parent::attributesToArray();
        $array['disciplina'] = $this->disciplina;
        $array['encargado'] = $this->encargado;
        return $array;
    }
}
