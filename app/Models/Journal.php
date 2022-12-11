<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Journal extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['closing_date'];
    protected $table = "journal";
    protected $primaryKey = "invoice_id";
    public function getImageAttribute($value)
    {
        $prefixFolder = "";
        if (Str::contains($value,"default_"))
        {
            $prefixFolder = "images/systemImages/";
        }
        elseif (in_array($this->attributes["invoice_type"],[0,1,2,3,4]))
        {
            $prefixFolder = "images/productInvoices/";
        }
        elseif (in_array($this->attributes["invoice_type"],[5,6,7]))
        {
            $prefixFolder = "images/cashInvoices/";
        }
        elseif (in_array($this->attributes["invoice_type"],[11,12]))
        {
            $prefixFolder = "images/productMovementInvoices/";
        }
        elseif (in_array($this->attributes["invoice_type"],[13,14]))
        {
            $prefixFolder = "images/manufacturingInvoices/";
        }
        return $prefixFolder . Str::replace("#","/",$value);
    }

//    public function getPostingAttribute($value)
//    {
//        return ($value == 1)? "posted":"not posted";
//    }

    public function setPostingAttribute($value)
    {
        $this->attributes['posting'] = ($value == "posted")? 1:0;
    }

    public function getInvoiceTypeAttribute($value)
    {
        if ($value == 1)
            $value = "sale";
        elseif ($value == 2)
            $value = "purchase";
        elseif ($value == 3)
            $value = "sale_return";
        elseif ($value == 4)
            $value = "purchase_return";
        elseif ($value == 0){
            if (!isset($this->attributes["invoice_id"]) and !isset($this->attributes["detail"]) and !isset($this->attributes["line"]))
            {
                return "";
            }
            $invoice_id = $this->attributes["invoice_id"];
            $line = $this->attributes["line"];
            $detail = $this->attributes["detail"];
            $result = DB::select("select sum(invoice_type) as type from journal where invoice_id = $invoice_id and line = $line and detail = $detail");
            if ($result[0]->type == 1)
                $value = "sale";
            elseif ($result[0]->type == 2)
                $value = "purchase";
            elseif ($result[0]->type == 3)
                $value = "sale_return";
            elseif ($result[0]->type == 4)
                $value = "purchase_return";
//            else dd($value);

        }
        elseif ($value == 5){
            if (!isset($this->attributes["invoice_id"]) and !isset($this->attributes["detail"]) and !isset($this->attributes["line"]))
            {
                return "";
            }
            $invoice_id = $this->attributes["invoice_id"];
            $line = $this->attributes["line"];
            $detail = $this->attributes["detail"];
            $result = DB::select("select sum(invoice_type) as type from journal where invoice_id = $invoice_id and line = $line and detail = $detail");
            if ($result[0]->type == 11)
                $value = "payment";
            elseif ($result[0]->type == 12)
                $value = "receive";
//            else dd($value);
        }
        elseif ($value == 6)
            $value = "payment";
        elseif ($value == 7)
            $value = "receive";
        elseif ($value == 11 or $value == 12)
            $value = "product_movement";
        elseif (in_array($value,[13,14,15,16]))
            $value = "manufacturing_action";
        elseif ($value == -1)
            $value = "checked";

        return $value;
    }

    public function setInvoiceTypeAttribute($value)
    {
        if ($value == "sale")
           $value = 1;
        elseif ($value == "purchase")
            $value = 2;
        elseif ($value == "sale_return")
            $value = 3;
        elseif ($value == "purchase_return")
            $value = 4;
        elseif ($value == "zero")
            $value = 0;
        elseif ($value == "cash")
            $value = 5;
        elseif ($value == "payment")
            $value = 6;
        elseif ($value == "receive")
            $value = 7;
        elseif ($value == "moved")
            $value = 11;
        elseif ($value == "moved_to")
            $value = 12;
//        elseif ($value == "manufacturing_action")
//            $value = 13;


        $this->attributes['invoice_type'] = $value;
    }

    public function setPoundTypeAttribute($value){
        $translate = [
            'ل.س'=>'syrian',
            'دولار'=>'dollar',
            'syrian'=>'syrian',
            'dollar'=>'dollar'
        ];
        $this->attributes["pound_type"] = $translate[$value];
//        return __("global.$value");
    }

    public function getPoundTypeAttribute($value){
        $translate = [
            'ل.س'=>'syrian',
            'دولار'=>'dollar',
            'syrian'=>'syrian',
            'dollar'=>'dollar',
            ''=>'',
            ' '=>''
        ];
        $value = $translate[$value];
        return __("global.$value");
    }

//    public function getDebitAttribute($value){
//        return $value * $this->attributes["num_for_pound"];
//    }
//
//    public function getCreditAttribute($value){
//        return $value * $this->attributes["num_for_pound"];
//    }
}
