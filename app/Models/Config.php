<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    protected $table = "config";
    protected $guarded = [];

    public function users(){
        return $this->belongsToMany("App\Models\User")->withPivot("value");
    }

}
