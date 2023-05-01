<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\functions\globalFunctions;

class
permissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        globalFunctions::registerUserActivityLog("seen_all","permissions",null);
        return view("admin.permissions.viewPermissions")->with("permissions",Permission::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request,["name"=>["required","unique:roles"]]);
        $input = $request->all();

        $result = Permission::create([
            "name"=>Str::ucfirst($input["name"]),
            "slug"=>Str::of(Str::ucfirst($input["name"]))->slug("-")
        ]);
//        if ($result!=null) {
//            session()->flash("success",__("messages.created_successfully",["attribute"=>__("global.permission")]));
//        }else{
//            session()->flash("success",__("messages.not_created_successfully",["attribute"=>__("global.permission")]));
//        }
        globalFunctions::flashMessage("create",$result,"permission");
        globalFunctions::registerUserActivityLog("added","permission",$result->id);

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $this->validate($request,["name"=>"required"]);
        if ($permission->name != $request["name"]){
            $this->validate($request,["name"=>"unique:roles"]);
        }
        $oldPermission = $request->all();
        $permission->name = Str::ucfirst($oldPermission["name"]);
        $permission->slug = Str::of(Str::ucfirst($oldPermission["name"]))->slug("-");

        $result = null;
        if ($permission->isDirty(["name"])) {
//            session()->flash("success",__("messages.updated_successfully",["attribute"=>__("global.permission")]));
            $result = $permission->save();
        }
//        else{
//            session()->flash("success",__("messages.nothing_to_be_updated"));
//        }
        globalFunctions::flashMessage("update",$result,"permission");
        globalFunctions::registerUserActivityLog("updated","permission",$permission->id);
        return back();
    }

    public function viewRecyclebin()
    {
        globalFunctions::registerUserActivityLog("seen","permissions_recyclebin",null);
        return view("admin.permissions.recyclebin")->with("deletedPermissions",Permission::onlyTrashed()->get());
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request,$permission_id)
    {
        if ($permission_id > 0) {
            $result = Permission::withTrashed()->find($permission_id)->forceDelete();
            globalFunctions::registerUserActivityLog("deleted","permission",$permission_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = Permission::withTrashed()->whereIn("id",$ids)->forceDelete();
            foreach ($ids as $id){
                globalFunctions::registerUserActivityLog("deleted","permission",$id);
            }
        } else {
            $result = null;
        }
        globalFunctions::flashMessage("delete",$result,"permission");

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Permission $permission
     * @return \Illuminate\Http\RedirectResponse
     */
    public function softDelete(Request $request,$permission_id)
    {
        if ($permission_id > 0) {
            $result = Permission::find($permission_id)->delete();
            globalFunctions::registerUserActivityLog("recycled","permission",$permission_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = Permission::whereIn("id",$ids)->delete();
            foreach ($ids as $id){
                globalFunctions::registerUserActivityLog("recycled","permission",$id);
            }
        } else {
            $result = null;
        }
        globalFunctions::flashMessage("softDelete",$result,"permission");

        return back();
    }

    public function restore(Request $request,$permission_id)
    {
        if ($permission_id > 0) {
            $result = Permission::onlyTrashed()->find($permission_id)->restore();
            globalFunctions::registerUserActivityLog("restored","permission",$permission_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = Permission::onlyTrashed()->whereIn("id",$ids)->restore();
            foreach ($ids as $id){
                globalFunctions::registerUserActivityLog("restored","permission",$id);
            }
        } else {
            $result = null;
        }
        globalFunctions::flashMessage("restore",$result,"permission");

        return back();
    }

}
