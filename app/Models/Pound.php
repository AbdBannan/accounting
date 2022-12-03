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
    use softDeletes;
    protected $guarded = [];

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
