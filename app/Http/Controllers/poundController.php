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
            $result = $pound->save();
        }

        globalFunctions::flashMessage("update",$result,"pound");
        globalFunctions::registerUserActivityLog("updated","pound",$pound->id);
        return back();
    }

    public function viewRecyclebin()
    {
        globalFunctions::registerUserActivityLog("seen","pounds_recyclebin",null);
        return view("admin.pounds.recyclebin")->with("deletedPounds",Pound::onlyTrashed()->get());
    }


    public function destroy(Request $request,$pound_id)
    {
        if ($pound_id > 0) {
            $result = Pound::withTrashed()->find($pound_id)->forceDelete();
            globalFunctions::registerUserActivityLog("deleted","pound",$pound_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = Pound::withTrashed()->whereIn("id",$ids)->forceDelete();
            foreach ($ids as $id){
                globalFunctions::registerUserActivityLog("deleted","pound",$id);
            }
        } else {
            $result = null;
        }
        globalFunctions::flashMessage("delete",$result,"pound");

        return back();
    }


    public function softDelete(Request $request,$pound_id)
    {
        if ($pound_id > 0) {
            $result = Pound::find($pound_id)->delete();
            globalFunctions::registerUserActivityLog("recycled","pound",$pound_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = Pound::whereIn("id",$ids)->delete();
            foreach ($ids as $id){
                globalFunctions::registerUserActivityLog("recycled","pound",$id);
            }
        } else {
            $result = null;
        }

        globalFunctions::flashMessage("softDelete",$result,"pound");

        return back();
    }

    public function restore(Request $request,$pound_id)
    {
        if ($pound_id > 0) {
            $result = Pound::onlyTrashed()->find($pound_id)->restore();
            globalFunctions::registerUserActivityLog("restored","pound",$pound_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = Pound::onlyTrashed()->whereIn("id",$ids)->restore();
            foreach ($ids as $id){
                globalFunctions::registerUserActivityLog("restored","pound",$id);
            }
        } else {
            $result = null;
        }
        globalFunctions::flashMessage("restore",$result,"pound");

        return back();
    }
}
