<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use function PHPUnit\Framework\stringContains;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];


    public function getImageAttribute($value):string {
        if (str::contains($value,"default_")){
            return "images/systemImages/" . $value;
        }
        else {
            return "images/productsImages/" . $value;
        }
    }


    public function getAccountTypeAttribute($value)
    {
        if ($value == 1){
            $value = __("global.primary");
        } elseif ($value == 0) {
            $value = __("global.secondary");
        }
        return $value;
    }

    public function getIsRawAttribute($value){
        if ($value == 1){
            return __("global.raw");
        } else {
            return __("global.manufactured");
        }
    }

    public function category(){
        return $this->belongsTo("App\Models\Category");
    }

    public function store(){
        return $this->belongsTo("App\Models\Store");
    }

    public function productTemplate(){
        return $this->hasOne("App\Models\ManufacturingTemplate");
    }

    public function templates(){
        return $this->belongsToMany("App\Models\ManufacturingTemplate","manufacturing_template_product","product_id","manufacturing_template_id")->withPivot(["price","quantity"]);
    }
}
