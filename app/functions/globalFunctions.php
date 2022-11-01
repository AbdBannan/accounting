<?php
namespace App\functions;

use App\Models\ActivityLog;
use App\Models\Config;
use App\Models\Pound;
use Carbon\Carbon;
use http\Exception\UnexpectedValueException;
use Illuminate\Support\Str;
use App\Models\User;
use phpDocumentor\Reflection\PseudoTypes\List_;
use phpDocumentor\Reflection\Types\This;

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
//        if (in_array($action_type,["seen all","archived"]) or Str::contains($action_name,"recyclebin")) {
        if ($id == null) {
            $content = "the user : $user has $action_type $action_name at " . Carbon::now();
        } elseif (in_array($action_type,["discovered"])) {
            $content = "the user : $user has $action_type '$action_name' whose id is " . $id . " at " . Carbon::now();
        } elseif (in_array($action_type,["made"])) {
            $content = "the user : $user has $action_type '$action_name' with row id " . $id . " at " . Carbon::now();
        } elseif (in_array($action_type,["attached","detached"] ) and explode(" ",$action_name)[1] == "permission" ) {
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
        $translate = [
            'ل.س'=>'syrian',
            'دولار'=>'dollar',
            'syrian'=>'syrian',
            'dollar'=>'dollar'
        ];
        return Pound::where("name",$translate[$pound])->first()->value;
    }

    public static function initialUserConfig(User $user){
        $config = [
            ["name"=>"language","controlled_by"=>"user", "type" => "global", "default_value" => "arabic"],
            ["name"=>"add_method","controlled_by"=>"user", "type" => "global", "default_value" => "modal"],
            ["name"=>"user_activity_log","controlled_by"=>"admin", "type" => "admin_control", "default_value" => "true"],
            ["name"=>"default_pound","controlled_by"=>"user", "type" => "global", "default_value" => "Syrian"],
            ["name"=>"dark_mode","controlled_by"=>"user", "type" => "look", "default_value" => "0"],
            ["name"=>"fixed_header","controlled_by"=>"user", "type" => "look", "default_value" => "1"],
            ["name"=>"drop_down_legacy_offset","controlled_by"=>"user", "type" => "look", "default_value" => "0"],
            ["name"=>"no_border","controlled_by"=>"user", "type" => "look", "default_value" => "0"],
            ["name"=>"collapsed","controlled_by"=>"user", "type" => "look", "default_value" => "1"],
            ["name"=>"fixed_sidebar","controlled_by"=>"user", "type" => "look", "default_value" => "1"],
            ["name"=>"sidebar_mini","controlled_by"=>"user", "type" => "look", "default_value" => "0"],
            ["name"=>"sidebar_mini_md","controlled_by"=>"user", "type" => "look", "default_value" => "1"],
            ["name"=>"sidebar_mini_xs","controlled_by"=>"user", "type" => "look", "default_value" => "0"],
            ["name"=>"nav_legacy_style","controlled_by"=>"user", "type" => "look", "default_value" => "0"],
            ["name"=>"nav_compact","controlled_by"=>"user", "type" => "look", "default_value" => "0"],
            ["name"=>"nav_child_indent","controlled_by"=>"user", "type" => "look", "default_value" => "1"],
            ["name"=>"nav_child_hide_on_collapse","controlled_by"=>"user", "type" => "look", "default_value" => "0"],
            ["name"=>"disable_hover_or_focus_auto_expand","controlled_by"=>"user", "type" => "look", "default_value" => "0"],
            ["name"=>"fixed_footer","controlled_by"=>"user", "type" => "look", "default_value" => "0"],
            ["name"=>"body_small_text_options","controlled_by"=>"user", "type" => "look", "default_value" => "0"],
            ["name"=>"navbar_small_text_options","controlled_by"=>"user", "type" => "look", "default_value" => "0"],
            ["name"=>"brand_small_text_options","controlled_by"=>"user", "type" => "look", "default_value" => "0"],
            ["name"=>"slide_bar_nav_small_text_options","controlled_by"=>"user", "type" => "look", "default_value" => "0"],
            ["name"=>"footer_small_text_options","controlled_by"=>"user", "type" => "look", "default_value" => "0"],
            ["name"=>"navbar_variants","controlled_by"=>"user", "type" => "look", "default_value" => "Light"],
            ["name"=>"accent_color_variants","controlled_by"=>"user", "type" => "look", "default_value" => "Purple"],
            ["name"=>"dark_sidebar_variants","controlled_by"=>"user", "type" => "look", "default_value" => "Purple"],
            ["name"=>"light_sidebar_variants","controlled_by"=>"user", "type" => "look", "default_value" => "None Selected"],
            ["name"=>"brand_logo_variants","controlled_by"=>"user", "type" => "look", "default_value" => "Gray"],
        ];
        $id = [];
        foreach ($config as $cfg) {
            $id = globalFunctions::createNewConfigIfNotExist($cfg);
            if ($cfg["default_value"] != "0") {
                $user->config()->attach($id,["value"=>$cfg["default_value"]]);
            }
        }
    }

    public static function createNewConfigIfNotExist($cfg) {
        $config = Config::where("name",$cfg["name"])->first();
        if ($config == null) {
            $new_config = Config::create(
                [
                    "name" => $cfg["name"],
                    "controlled_by" => $cfg["controlled_by"],
                    "type" => $cfg["type"]
                ]
            );
            return $new_config->id;
        }
        return $config->id;
    }

    public static function fixTranslation($message){
        $splited = explode("#",$message);
        if (count($splited)>1){
            $splited[1] = str_replace(" ","_",$splited[1]);
            return $splited[0] . __("global.".$splited[1],[],session("lang")) . $splited[2];
        }
        else
            return $message;
    }
}
