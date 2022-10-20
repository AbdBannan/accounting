<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;
use function PHPUnit\Framework\stringContains;

class Product extends Model
{
    use HasFactory;
    use softDeletes;
    protected $guarded = [];


    public function getImageAttribute($value):string {
        if (explode("/",$value)[0]=="systemImages"){
            return "images/" . $value;
        }
        else {
            return "images/productsImages/" . $value;
        }
    }


    public function getAccountTypeAttribute($value)
    {
        if ($value == 1){
            $value = __("global.primary",[],session("lang"));
        } elseif ($value == 0) {
            $value = __("global.secondary",[],session("lang"));
        }
        return $value;
    }


    public function category(){
        return $this->belongsTo("App\Models\Category");
    }

    public function store(){
        return $this->belongsTo("App\Models\Store");
    }
}
