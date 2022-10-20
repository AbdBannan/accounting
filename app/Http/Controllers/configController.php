<?php

namespace App\Http\Controllers;

use App\functions\globalFunctions;
use App\Models\Config;
use App\Models\User;
use Illuminate\Http\Request;

class configController extends Controller
{
    public function index(){
        $config = auth()->user()->config()->where("type","global")->get();
        $conf = [];
        foreach ($config as $cfg) {
            $conf[$cfg->name] = $cfg->pivot->value;
        }

        globalFunctions::registerUserActivityLog("seen","config",auth()->user()->id);
        return view("admin.config.config")->with("config",$conf);
    }

    public function store(Request $request){
//        dd($request->all());
        auth()->user()->config()->detach();
        foreach ($request->all() as $name=>$value){
            if ($name != "_token"){
                $result1 = auth()->user()->config()->attach(Config::where("name",$name)->first()->id,["value"=>$value]);
            }
        }
        globalFunctions::flashMessage("save",true,"config");
        globalFunctions::registerUserActivityLog("updated","config",auth()->user()->id);

        return back();
    }


}
