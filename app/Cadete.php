<?php

namespace App;

use App\Models\Merito;
use Illuminate\Database\Eloquent\Model;

class Cadete extends Model
{
    protected $fillable = ['id', 'year_ingreso'];

    public $timestamps = false;

    public function persona()
    {
        return $this->belongsTo('App\Persona');
    }

    public function meritos() {
        return $this->belongsToMany(
            Merito::class,
            'cadetes_meritos',
            'cadete_id',
            'merito_id'
        );
    }
}
