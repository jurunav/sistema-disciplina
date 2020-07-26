<?php

namespace App;

use App\Models\BaseModel;
use App\Models\Encargado;

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

    /**
     * Persona constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function cadete(){
        return $this->hasOne(Cadete::class, 'persona_id');
    }

    public function encargado(){
        return $this->hasOne(Encargado::class, 'persona_id');
    }

    public function toArray()
    {
        $array = parent::attributesToArray();
        if (!is_null($this->encargado))
            $array['encargado_id'] = $this->encargado->id;

        return $array;
    }
}
