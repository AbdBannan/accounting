<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;


class Pound extends Model
{
    use HasFactory;
    use notifiable;
    use SoftDeletes;
    protected $guarded = [];

    public function getNameAttribute($value){
        if ($this->attributes["created_by"] == "developer") {
            return __("global.$value");
        } else {
            return $value;
        }
    }

    public function setNameAttribute($value){
        if (isset($this->attributes["created_by"]) and $this->attributes["created_by"] == "developer") {
            // do not allow to modify the name of the pound created by developer
        } else {
            $this->attributes["name"] =  $value;
        }
    }
//    protected function getNameAttribute($value){
//        $trans = [
//            "Syrian"=>"ل.س",
//            "Dollar"=>"دولار",
//            "ل.س"=>"Syrian",
//            "دولار"=>"Dollar",
//        ];
//        if ($value == "Syrian" or $value == "Dollar") {
//            return $trans[$value];
//        }
//        return $value;
//    }
}
