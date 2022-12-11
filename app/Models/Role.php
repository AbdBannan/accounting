<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\softDeletes;
class Role extends Model
{
    use HasFactory;
    use softDeletes;
    protected $guarded = [];

    public function getNameAttribute($value){
        if ($this->attributes["created_by"] == "developer") {
            return __("global.$value");
        } else {
            return $value;
        }
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany("App\Models\Users");
    }

    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany("App\Models\Permission");
    }
}
