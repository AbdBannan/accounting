<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\softDeletes;
use function PHPUnit\Framework\stringContains;

class User extends Authenticatable
{
    use Notifiable;
    use HasFactory;
    use softDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany("App\Models\Role");
    }

    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany("App\Models\Permission");
    }

    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany("App\Models\Post");
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany("App\Models\Comment");
    }

    public function replies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany("App\Models\Reply");
    }

    public function config(){
        return $this->belongsToMany("App\Models\Config")->withPivot("value");
    }

    public function getConfig($config_name){
        foreach ($this->config as $config){
            if ($config->name == $config_name) {
                return $config->pivot->value;
            }
        }
    }

//    public function getConfig($config_value){
//        foreach ($this->config as $config){
//            if ($config->pivot->value == $config_value)
//                return true;
//        }
//    }

    public function isAdmin(): bool
    {
        foreach ($this->roles as $role){
            if ($role->name ==  "admin"){
                return true;
            }
        }
        return false;
    }
    public function hasRole($role_name){
        foreach ($this->roles as $role){
            if ($role->name ==  $role_name){
                return true;
            }
        }
        return false;
    }

    public function getProfileImageAttribute($value):string{
        if (explode("/",$value)[0]=="systemImages"){
            return "images/" . $value;
        }
        else {
            return "images/usersImages/" . $value;
        }
    }
}
