<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;
class Account extends Model
{
    use HasFactory;
    use softDeletes;
    protected $guarded = [];


    public function getAccountTypeAttribute($value)
    {
        if ($value == 1){
            $value = __("global.primary",[],session("lang"));
        } elseif ($value == 0) {
            $value = __("global.secondary",[],session("lang"));
        }
        return $value;
    }

    public function getGroupAttribute($value)
    {
        if ($value == 0){
            $value = __("global.budget",[],session("lang"));
        } elseif ($value == 1) {
            $value = __("global.gain_loss",[],session("lang"));
        } elseif ($value == 2) {
            $value = __("global.trades",[],session("lang"));
        }
        return $value;
    }


}
