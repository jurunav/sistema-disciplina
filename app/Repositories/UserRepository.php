<?php

namespace App\Repositories;


use App\User;

class UserRepository
{
    public function getAll() {
        return User::all();
    }

    public function getByEmail($email) {
        return User::where('email', '=', $email)->first();
    }
}