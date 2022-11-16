<?php

namespace App\Http\Controllers;

use App\functions\globalFunctions;
use App\Models\Config;
use App\Models\User;
use Illuminate\Http\Request;

class configController extends Controller
{
    public function index(){
        $config = auth()->user()->config()->where("type","!=","admin_control")->get();
        $conf = [];
        foreach ($config as $cfg) {
            $conf[$cfg->name] = $cfg->pivot->value;
        }

        globalFunctions::registerUserActivityLog("seen","config",auth()->user()->id);
        return view("config.config")->with("config",$conf);
    }

    public function store(Request $request){
        foreach ($request->all() as $config_name => $config_value){
            $this->validate($request,[$config_name=>"required"]);
        }
        $config_type = $request->config_type;
        unset($request["config_type"]);
        unset($request["_token"]);
        foreach ($request->all() as $name=>$value){
            globalFunctions::createNewConfigIfNotExist( [
                "name" => $name,
                "controlled_by" => "user",
                "type" => $config_type
            ]);
//            if (!isset(Config::where("name",$name)->first()->name)){
//                Config::create (
//                    [
//                        "name" => $name,
//                        "controlled_by" => "user",
//                        "type" => $config_type
//                    ]
//                );
//            }
        }
//        auth()->user()->config()->where("type",$config_type)->detach();
        foreach (auth()->user()->config->where("type",$config_type) as $config){
            auth()->user()->config()->detach($config->id);
        }
        foreach ($request->all() as $name=>$value){
            $result1 = auth()->user()->config()->attach(Config::where("name",$name)->first()->id,["value"=>$value]);
        }
        globalFunctions::flashMessage("save",true,"config");
        globalFunctions::registerUserActivityLog("updated","config",auth()->user()->id);

        return back();
    }


}
