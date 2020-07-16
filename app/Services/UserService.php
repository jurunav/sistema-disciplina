<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 11/24/2017
 * Time: 3:52 PM
 */

namespace App\Services;


use App\Notifications\UserCreated;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rule;

class UserService
{

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var MessageBag
     */
    public $errors;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->errors = new MessageBag();
    }

    public function getById($userId) {
        return User::find($userId);
    }

    public function getAll() {
        return $this->userRepository->getAll();
    }

    public function create($data) {
        $validatorRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ];

        /**
         * @var \Illuminate\Validation\Validator $validator
         */
        $validator = Validator::make($data, $validatorRules);
        if($validator->fails()) {
            $this->errors->merge($validator->errors());
        }

        $user = null;

        if ($this->errors->count() == 0) {
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = bcrypt($data['password']);
            $user->save();

            $user->tmp_password = $data['password'];
            if (array_key_exists('signup', $data)) {
                $user->notify(new UserCreated());
            }
        }
        return $user;
    }

    public function update(User $user, $data) {
        $validatorRules = [
            'name' => 'filled|string|max:255',
            'email' => ['filled',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ]
        ];

        /**
         * @var \Illuminate\Validation\Validator $validator
         */
        $validator = Validator::make($data, $validatorRules);
        if($validator->fails()) {
            $this->errors->merge($validator->errors());
        }

        if ($this->errors->count() == 0) {
            if (array_key_exists('name', $data)) {
                $user->name = $data['name'];
            }

            if (array_key_exists('email', $data)) {
                $user->email = $data['email'];
            }
            $user->save();
        }
        return $user;
    }

    public function delete(User $user) {
        $user->delete();
    }

    public function resetPassword() {

    }

    public function changePassword(User $user, $data) {
        /**
         * @var Validator $validator
         */
        $validationRules = [
            'password' => 'required|string|min:6|confirmed',
            'oldPassword' => 'required',
        ];

        /**
         * @var \Illuminate\Contracts\Validation\Validator $validator
         */
        $validator = Validator::make($data, $validationRules);

        if($validator->fails()) {
            $this->errors->merge($validator->errors());
        }

        if ($this->errors->count() == 0) {
            if (Hash::check($data['oldPassword'], $user->password)) {
                $user->password = (bcrypt($data['password']));
                $user->save();
            } else {
                $this->errors->add('oldPassword', "The old password is incorrect");
            }
        }
        return $user;
    }

    public function getByEmail($email) {
        return $this->userRepository->getByEmail($email);
    }
}