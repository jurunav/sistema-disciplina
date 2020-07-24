<?php

namespace App\Models;

use App\Categoria;
use App\Premio;

class Disciplina extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'disciplinas';

    /**
     * Disciplina constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function categoria(){
        return $this->belongsTo(Categoria::class);
    }

    public function premio(){
        return $this->belongsTo(Premio::class);
    }

    public function toArray()
    {
        $array = parent::attributesToArray();
        $array['premio'] = $this->premio;
        $array['categoria'] = $this->categoria;
        return $array;
    }
}
