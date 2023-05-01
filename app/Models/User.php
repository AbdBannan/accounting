<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use function PHPUnit\Framework\stringContains;

//class User extends Authenticatable implements MustVerifyEmail
class User extends Authenticatable
{
//    use HasApiTokens;
    use Notifiable;
    use HasFactory;
    use SoftDeletes;
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

    public function notifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany("App\Models\Notification");
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
            if (in_array(strtolower($role->name) , ["admin","مدير"])){
                return true;
            }
        }
        return false;
    }
    public function hasRole($role_name){
        foreach ($this->roles as $role){
            if (strtolower($role->name) == strtolower($role_name)){
                return true;
            }
        }
        return false;
    }

    public function getProfileImageAttribute($value):string{
        if (Str::contains($value,"default_")){
            return "images/systemImages/" . $value;
        }
        else {
            return "images/usersImages/" . $value;

        }
    }
}
