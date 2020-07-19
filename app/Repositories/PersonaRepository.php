<?php

namespace App\Repositories;


use App\Persona;
use App\User;

class PersonaRepository
{
    public function getAll() {
        return User::all();
    }

    public function getById($id) {
        return Persona::find($id);
    }

    public function getByEmail($email) {
        return Persona::where('email', '=', $email)->first();
    }

    public function getByGrado($grado) {
        return Persona::where('grado', '=', $grado)->first();
    }
}