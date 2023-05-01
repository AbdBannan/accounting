<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Account extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];


    public function getAccountTypeAttribute($value)
    {
        if ($value == 1){
            $value = __("global.primary");
        } elseif ($value == 0) {
            $value = __("global.secondary");
        }
        return $value;
    }

    public function getGroupAttribute($value)
    {
        if ($value == 0){
            $value = __("global.budget");
        } elseif ($value == 1) {
            $value = __("global.gain_loss");
        } elseif ($value == 2) {
            $value = __("global.trades");
        }
        return $value;
    }


}
