<?php
namespace App\functions;

use App\Models\ActivityLog;
use App\Models\Pound;
use Carbon\Carbon;
use Illuminate\Support\Str;

class globalFunctions
{
    public static function flashMessage($actionType,$result,$name) {

        switch ($actionType){
            case "create":
            {
                $actionType = "created";
                break;
            }
            case "update":
            {
                $actionType = "updated";
                break;
            }
            case "softDelete":
            {
                $actionType = "recycled";
                break;
            }
            case "delete":
            {
                $actionType = "deleted";
                break;
            }
            case "restore":
            {
                $actionType = "restored";
                break;
            }
            case "save":
            {
                $actionType = "saved";
                break;
            }
            case "archive":
            {
                $actionType = "archived";
                break;
            }
        }
        if ($actionType == "updated") {
             if ("".$result == "no_item_error") {
                session()->flash("error",__("messages.no_item",["attribute"=>__("global.$name",[],session("lang"))],session("lang")));
            } elseif ($result!=null) {
                session()->flash("success",__("messages." . $actionType . "_successfully",["attribute"=>__("global." . $name,[],session("lang"))],session("lang")));
            } else {
                session()->flash("success",__("messages.nothing_to_be_updated",[],session("lang")));
            }
        } else {
             if ("".$result == "no_item_error") {
                session()->flash("error",__("messages.no_item",["attribute"=>__("global.$name",[],session("lang"))],session("lang")));
            } elseif ($result != null or "".$result == "none") {
                session()->flash("success", __("messages." . $actionType . "_successfully", ["attribute" => __("global.$name", [], session("lang"))], session("lang")));
            } elseif ("".$result == "not_found") {
                session()->flash("error",__("messages.not_found",["attribute"=>__("global.$name",[],session("lang"))],session("lang")));
            } else {
                session()->flash("error", __("messages.not_" . $actionType . "_successfully", ["attribute" => __("global." . $name, [], session("lang"))], session("lang")));
            }
        }
    }


    public static function registerUserActivityLog($action_type,$action_name,$id){
        if (auth()->user()->getConfig("user_activity_log") == "false") {
            return;
        }
        $content = "";
        $user = auth()->user()->first_name . " " . auth()->user()->last_name;

        $action_type = str_replace("_"," ",$action_type);
        $action_name = str_replace("_"," ",$action_name);
        if (in_array($action_type,["seen all","archived"]) or Str::contains($action_name,"recyclebin")) {
            $content = "the user : $user has $action_type $action_name at " . Carbon::now();
        } elseif (in_array($action_type,["attached","detached"] )and explode(" ",$action_name)[1] == "role" ) {
            $content = "the user : $user has $action_type $action_name for user whose id is " . $id . " at " . Carbon::now();
        } elseif (in_array($action_type,["attached","detached"] )and explode(" ",$action_name)[1] == "permission" ) {
            $content = "the user : $user has $action_type $action_name for permission whose id is " . $id . " at " . Carbon::now();
        } else {
            $content = "the user : $user has $action_type a/an $action_name whose id is " . $id . " at " . Carbon::now();
        }
//        $content = "the user : $user has " . $action_type . "ed a $action_name whose id is " . $id . " at " . Carbon::now();

        ActivityLog::create([
            "user_id"=>auth()->user()->id,
            "content"=>$content
        ]);
    }

    public static function getEquivalentPoundValue($pound)
    {
        return Pound::where("name",__("global.$pound",[],session("lang")))->first()->value;
    }
}
