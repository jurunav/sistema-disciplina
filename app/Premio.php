<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Premio extends Model
{

    protected $fillable =['idcategoria','nombre','puntaje','condicion'];
    
    public function categoria(){
        return $this->belongsTo('App\Categoria');
    }
}
