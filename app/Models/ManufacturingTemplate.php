<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManufacturingTemplate extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

    public function baseProduct(){
        return $this->hasOne("App\Models\Product");
    }

    public function components(){
        return $this->belongsToMany("App\Models\Product","manufacturing_template_product","manufacturing_template_id","product_id")->withPivot(["price","quantity"]);
    }
}
