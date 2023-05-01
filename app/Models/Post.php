<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "posts";
    public $primaryKey = "id";
    public $timestamps = true;
    protected $fillable = ["name","image","size","user_id","content"]; // do the next one insteed of writing all fields
//    protected $guarded = [];

    public function setImageAttribute($value) {
        $this->attributes["image"] = Carbon::now() . "_(#)_" . $value ;
    }
    public $imagesDirectory = "/images/";
    public function getImageAttribute($value):string {
        return $this->imagesDirectory . explode("_(#)_",$value)[1];
    }

    public function user() {
        return $this->belongsTo("App\Models\User");
    }

    public function comments(){
        return $this->hasMany("App\Models\Comment");
    }
}
