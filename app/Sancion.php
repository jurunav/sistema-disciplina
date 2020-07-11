<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sancion extends Model
{
    protected $table = 'sanciones';
    protected $fillable =['nombre','puntaje','puntaje_dia','categoria','articulo','grupo','inciso','condicion'];
    

}
