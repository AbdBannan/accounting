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
                session()->flash("error",__("messages.no_item",["attribute"=>__("global.$name")]));
            } elseif ($result!=null and $result != false) {
                session()->flash("success",__("messages." . $actionType . "_successfully",["attribute"=>__("global." . $name)]));
            } else {
                session()->flash("success",__("messages.nothing_to_be_updated"));
            }
        } else {
            if ("".$result == "date_not_correct") {
                session()->flash("error", __("messages.date_not_correct"));
            }elseif ("".$result == "account_is_not_zero") {
                 session()->flash("error", __("messages.account_is_not_zero"));
            }elseif ("".$result == "no_item_error") {
                session()->flash("error",__("messages.no_item",["attribute"=>__("global.$name")]));
            } elseif ($result != null or $result == true) {
                session()->flash("success", __("messages." . $actionType . "_successfully", ["attribute" => __("global.$name")]));
            } elseif ("".$result == "not_found") {
                session()->flash("error",__("messages.not_found",["attribute"=>__("global.$name")]));
            } else {
                session()->flash("error", __("messages.not_" . $actionType . "_successfully", ["attribute" => __("global." . $name)]));
            }
        }
    }

    public static function registerUserActivityLog($action_type,$action_name,$id){
        if (auth()->user()->getConfig("user_activity_log") == "false") {
            return;
        }
        $content = "";
        $the_user = __("global.the_user");
        $has = __("global.has");
        $at = __("global.at");
        $whose_id_is = __("global.whose_id_is");
        $with_row_id = __("global.with_row_id");
        $for_permission_whose_id_is = __("global.for_permission_whose_id_is");


        $user = auth()->user()->first_name . " " . auth()->user()->last_name;

        $temp_action_type = $action_type;
        $action_type = __("global.$action_type");
        $action_name = __("global.$action_name");

        $date = Carbon::now()->format("Y-m-d g:i:s a");
        $AM = __("global.AM");
        $PM = __("global.PM");

        $date = Str::replace("am",$AM,$date);
        $date = Str::replace("pm",$PM,$date);
        if ($id == null) {
            $content = "$the_user : $user $has $action_type $action_name $at $date";
        } elseif (in_array($temp_action_type,["discovered"])) {
            $content = "$the_user : $user $has $action_type '$action_name' $whose_id_is $id $at $date";
        } elseif (in_array($temp_action_type,["made"])) {
            $content = "$the_user : $user $has $action_type '$action_name' $with_row_id $id $at $date";
        } elseif (in_array($temp_action_type,["attached","detached"] ) and explode(" ",$action_name)[1] == "permission" ) {
            $content = "$the_user : $user $has $action_type $action_name $for_permission_whose_id_is $id $at $date";
        } else {
            $content = "$the_user : $user $has $action_type $action_name $whose_id_is $id $at $date";
        }

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
        if (key_exists($pound, $translate)){
            $pound = $translate[$pound];
        }
        return Pound::where("name",$pound)->first()->value;
    }

    public static function initialUserConfig(User $user){
        $config = [
            ["name"=>"language","controlled_by"=>"user", "type" => "global", "default_value" => (app()->getLocale() == "ar")? "arabic":"english"],
            ["name"=>"add_method","controlled_by"=>"user", "type" => "global", "default_value" => "modal"],
            ["name"=>"user_activity_log","controlled_by"=>"admin", "type" => "admin_control", "default_value" => "true"],
            ["name"=>"default_pound","controlled_by"=>"user", "type" => "global", "default_value" => (app()->getLocale() == "ar")?__("global.syrian"):"syrian"],
            ["name"=>"gainful_percentage","controlled_by"=>"user", "type" => "global", "default_value" => 5],
            ["name"=>"level_of_product_quantity_for_notification","controlled_by"=>"user", "type" => "global", "default_value" => 100],
            ["name"=>"row_count_in_table","controlled_by"=>"user", "type" => "global", "default_value" => 10],
            ["name"=>"use_recyclebin","controlled_by"=>"user", "type" => "global", "default_value" => "true"],
            ["name"=>"clean_recyclebin_after","controlled_by"=>"user", "type" => "global", "default_value" => "5"],
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
            ["name"=>"nav_child_hide_on_collapse","controlled_by"=>"user", "type" => "look", "default_value" => "1"],
            ["name"=>"disable_hover_or_focus_auto_expand","controlled_by"=>"user", "type" => "look", "default_value" => "0"],
            ["name"=>"fixed_footer","controlled_by"=>"user", "type" => "look", "default_value" => "0"],
            ["name"=>"body_small_text_options","controlled_by"=>"user", "type" => "look", "default_value" => "0"],
            ["name"=>"navbar_small_text_options","controlled_by"=>"user", "type" => "look", "default_value" => "0"],
            ["name"=>"brand_small_text_options","controlled_by"=>"user", "type" => "look", "default_value" => "0"],
            ["name"=>"side_bar_nav_small_text_options","controlled_by"=>"user", "type" => "look", "default_value" => "0"],
            ["name"=>"footer_small_text_options","controlled_by"=>"user", "type" => "look", "default_value" => "0"],
            ["name"=>"navbar_variants","controlled_by"=>"user", "type" => "look", "default_value" => "Light"],
            ["name"=>"accent_color_variants","controlled_by"=>"user", "type" => "look", "default_value" => "Purple"],
            ["name"=>"dark_sidebar_variants","controlled_by"=>"user", "type" => "look", "default_value" => "Purple"],
            ["name"=>"light_sidebar_variants","controlled_by"=>"user", "type" => "look", "default_value" => "None Selected"],
            ["name"=>"brand_logo_variants","controlled_by"=>"user", "type" => "look", "default_value" => "Gray"],
        ];
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
        if (count($splited) <= 1) {
            return $message;
        }
        if (Str::contains($splited[1],"second part name ") || Str::contains($splited[1],"first part name ")) {
            return __("messages.can_not_update_invoice_contains_deleted_account");
        }
        if (Str::contains($splited[1],"product name ")) {
            return __("messages.can_not_update_invoice_contains_deleted_product");
        }
        $splited[1] = str_replace(" ","_",$splited[1]);
        return $splited[0] . "('" . __("global.".$splited[1]) . "')" . $splited[2];
    }

}



