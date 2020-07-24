<?php

namespace App;

use App\Models\BaseModel;
use App\Models\Disciplina;

class Categoria extends BaseModel
{
    public function disciplinas(){
        return $this->hasMany(Disciplina::class);
    }

}
