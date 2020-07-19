<?php

namespace App\Models;

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

    public function premio(){
        return $this->belongsTo(Premio::class);
    }

    public function toArray()
    {
        $array = parent::attributesToArray();
        return $array;
    }
}
