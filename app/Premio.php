<?php

namespace App;

use App\Models\BaseModel;
use App\Models\Disciplina;
use App\Models\Merito;

class Premio extends BaseModel
{

    public function disciplinas(){
        return $this->hasMany(Disciplina::class);
    }

    public function meritos(){
        return $this->hasMany(Merito::class);
    }
}
