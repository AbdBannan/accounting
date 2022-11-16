<?php

namespace App\Http\Controllers;

use App\functions\globalFunctions;
use App\Models\Pound;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpOption\None;

class poundController extends Controller
{

    public function index()
    {
        globalFunctions::registerUserActivityLog("seen_all","pounds",null);
        return view("admin.pounds.viewPounds")->with("pounds",Pound::all());
    }


    public function store(Request $request)
    {
        $this->validate($request,["name"=>["required","unique:pounds"],"value"=>"required"]);
        $input = $request->all();

        $result = Pound::create([
            "name"=>Str::ucfirst($input["name"]),
            "slug"=>Str::of(Str::ucfirst($input["name"]))->slug("-"),
            "value"=>$input["value"]
        ]);
//        if ($result!=null) {
//            session()->flash("success",__("messages.created_successfully",["attribute"=>__("global.role",[],session("lang"))],session("lang")));
//        }else{
//            session()->flash("success",__("messages.not_created_successfully",["attribute"=>__("global.role",[],session("lang"))],session("lang")));
//        }

        globalFunctions::flashMessage("create",$result,"pound");
        globalFunctions::registerUserActivityLog("added","pound",$result->id);

        return back();
    }


    public function update(Request $request, Pound $pound)
    {
        $this->validate($request,["name"=>"required","value"=>"required"]);
        if ($pound->name != $request->name){
            $this->validate($request,["name"=>"unique:pounds"]);
        }

        $oldPound = $request->all();
        $pound->name = Str::ucfirst($oldPound["name"]);
        $pound->slug = Str::of(Str::ucfirst($oldPound["name"]))->slug("-");
        $pound->value = $oldPound["value"];

        $result = null;
        if ($pound->isDirty(["name","value"])) {
//            session()->flash("success",__("messages.updated_successfully",["attribute"=>__("global.role",[],session("lang"))],session("lang")));
            $result = $pound->save();
        }
//        else{
//            session()->flash("success",__("messages.nothing_to_be_updated",[],session("lang")));
//        }
        globalFunctions::flashMessage("update",$result,"pound");
        globalFunctions::registerUserActivityLog("updated","pound",$pound->id);

        return back();
    }

    public function viewRecyclebin()
    {
        globalFunctions::registerUserActivityLog("seen","pounds_recyclebin",null);
        return view("admin.pounds.recyclebin")->with("deletedPounds",Pound::onlyTrashed()->get());
    }


    public function destroy($pound_id)
    {
        $result = Pound::withTrashed()->find($pound_id)->forceDelete();

//        if ($result!=null) {
//            session()->flash("success",__("messages.deleted_successfully",["attribute"=>__("global.role",[],session("lang"))],session("lang")));
//        }else{
//            session()->flash("success",__("messages.not_deleted_successfully",["attribute"=>__("global.role",[],session("lang"))],session("lang")));
//        }
        globalFunctions::flashMessage("delete",$result,"pound");
        globalFunctions::registerUserActivityLog("deleted","pound",$pound_id);

        return back();
    }


    public function softDelete(Pound $pound)
    {
        $result = $pound->delete();

//        if ($result!=null) {
//            session()->flash("success",__("messages.recycled_successfully",["attribute"=>__("global.role",[],session("lang"))],session("lang")));
//        }else{
//            session()->flash("success",__("messages.not_recycled_successfully",["attribute"=>__("global.role",[],session("lang"))],session("lang")));
//        }

        globalFunctions::flashMessage("softDelete",$result,"pound");
        globalFunctions::registerUserActivityLog("recycled","pound",$pound->id);

        return back();
    }

    public function restore($pound_id)
    {
        $result = Pound::onlyTrashed()->where("id",$pound_id)->restore();

//        if ($result!=null) {
//            session()->flash("success",__("messages.restored_successfully",["attribute"=>__("global.role",[],session("lang"))],session("lang")));
//        }else{
//            session()->flash("success",__("messages.restored_successfully",["attribute"=>__("global.role",[],session("lang"))],session("lang")));
//        }
        globalFunctions::flashMessage("restore",$result,"pound");
        globalFunctions::registerUserActivityLog("restored","pound",$pound_id);

        return back();
    }
}
