<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cadete extends Model
{
    protected $fillable = ['id', 'year_ingreso'];

    public $timestamps = false;

    public function persona()
    {
        return $this->belongsTo('App\Persona');
    }
}
