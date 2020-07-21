<?php

namespace App;

use App\Models\BaseModel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Spatie\Permission\Traits\HasRoles;

class User extends BaseModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idpersona','idrol', 'usuario', 'password','condicion'
    ];

    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function rol(){
        return $this->belongsTo('App\Rol');
    }

    public function persona(){
        return $this->belongsTo('App\Persona');
    }

    public function toArray($mode = 'min')
    {
        $array = parent::toArray();
        $array['roles'] = $this->roles()->get();
        if ($mode == 'full') {
            $array['roles'] = $this->roles()->with('permissions')->get();
        }
        return $array;
    }

    public function can($permission, $requireAll = false)
    {
        if ($this->hasRole('super_admin')) {
            return true;
        } else {
            return $this->can($permission, $requireAll);
        }
    }

    public function getMaxRoleLevel() {
        $maxLevel = 0;
        foreach($this->roles as $role) {
            if ($role->level > $maxLevel) {
                $maxLevel = $role->level;
            }
        }

        return $maxLevel;
    }
}
