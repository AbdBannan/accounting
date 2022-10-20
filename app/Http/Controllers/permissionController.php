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
        $this->validate($request,["name"=>"required"]);
        $input = $request->all();

        $result = Permission::create([
            "name"=>Str::ucfirst($input["name"]),
            "slug"=>Str::of(Str::ucfirst($input["name"]))->slug("-")
        ]);
//        if ($result!=null) {
//            session()->flash("success",__("messages.created_successfully",["attribute"=>__("global.permission",[],session("lang"))],session("lang")));
//        }else{
//            session()->flash("success",__("messages.not_created_successfully",["attribute"=>__("global.permission",[],session("lang"))],session("lang")));
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
        $oldPermission = $request->all();
        $permission->name = Str::ucfirst($oldPermission["name"]);
        $permission->slug = Str::of(Str::ucfirst($oldPermission["name"]))->slug("-");

        $result = null;
        if ($permission->isDirty(["name"])) {
//            session()->flash("success",__("messages.updated_successfully",["attribute"=>__("global.permission",[],session("lang"))],session("lang")));
            $result = $permission->save();
        }
//        else{
//            session()->flash("success",__("messages.nothing_to_be_updated",[],session("lang")));
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($permission_id)
    {
        $result = Permission::onlyTrashed()->find($permission_id)->forceDelete();

//        if ($result!=null) {
//            session()->flash("success",__("messages.deleted_successfully",["attribute"=>__("global.permission",[],session("lang"))],session("lang")));
//        }else{
//            session()->flash("success",__("messages.not_deleted_successfully",["attribute"=>__("global.permission",[],session("lang"))],session("lang")));
//        }
        globalFunctions::flashMessage("delete",$result,"permission");
        globalFunctions::registerUserActivityLog("deleted","permission",$permission_id);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function softDelete(Permission $permission)
    {
        $result = $permission->delete();

//        if ($result!=null) {
//            session()->flash("success",__("messages.recycled_successfully",["attribute"=>__("global.permission",[],session("lang"))],session("lang")));
//        }else{
//            session()->flash("success",__("messages.not_recycled_successfully",["attribute"=>__("global.permission",[],session("lang"))],session("lang")));
//        }
        globalFunctions::flashMessage("softDelete",$result,"permission");
        globalFunctions::registerUserActivityLog("recycled","permission",$permission->id);

        return back();
    }

    public function restore($permission_id)
    {
        $result = Permission::onlyTrashed()->where("id",$permission_id)->restore();

//        if ($result!=null) {
//            session()->flash("success",__("messages.restored_successfully",["attribute"=>__("global.permission",[],session("lang"))],session("lang")));
//        }else{
//            session()->flash("success",__("messages.restored_successfully",["attribute"=>__("global.permission",[],session("lang"))],session("lang")));
//        }
        globalFunctions::flashMessage("restore",$result,"permission");
        globalFunctions::registerUserActivityLog("restored","permission",$permission_id);

        return back();
    }

}
