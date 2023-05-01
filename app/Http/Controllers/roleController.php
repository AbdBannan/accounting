<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Str;
use App\functions\globalFunctions;

class roleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        globalFunctions::registerUserActivityLog("seen_all","roles",null);
        return view("admin.roles.viewRoles")->with("roles",Role::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {

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

        $result = Role::create([
            "name"=>Str::ucfirst($input["name"]),
            "slug"=>Str::of(Str::ucfirst($input["name"]))->slug("-")
        ]);
//
        globalFunctions::flashMessage("create",$result,"role");
        globalFunctions::registerUserActivityLog("added","role",$result->id);

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        globalFunctions::registerUserActivityLog("seen","role",$role->id);
        return view("admin.roles.rolePermission")->with("role",$role);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Role $role)
    {
        if (strtolower($role->name) == "admin")
            abort(304,"not permitted to delete this role");
        $this->validate($request,["name"=>"required"]);
        if ($role->name != $request["name"]){
            $this->validate($request,["name"=>"unique:roles"]);
        }
        $oldRole = $request->all();
        $role->name = Str::ucfirst($oldRole["name"]);
        $role->slug = Str::of(Str::ucfirst($oldRole["name"]))->slug("-");

        $result = null;
        if ($role->isDirty(["name"])) {
//            session()->flash("success",__("messages.updated_successfully",["attribute"=>__("global.role")]));
            $result = $role->save();
        }
//        else{
//            session()->flash("success",__("messages.nothing_to_be_updated"));
//        }
        globalFunctions::flashMessage("update",$result,"role");
        globalFunctions::registerUserActivityLog("updated","role",$role->id);

        return back();
    }

    public function viewRecyclebin()
    {
        globalFunctions::registerUserActivityLog("seen","roles_recyclebin",null);
        return view("admin.roles.recyclebin")->with("deletedRoles",Role::onlyTrashed()->get());
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request,$role_id)
    {
        if (Role::find($role_id) and strtolower(Role::find($role_id)->name) == "admin")
            abort(304,"not permitted to delete this role");
        if ($role_id > 0) {
            $result = Role::withTrashed()->find($role_id)->forceDelete();
            globalFunctions::registerUserActivityLog("deleted","role",$role_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = Role::withTrashed()->whereIn("id",$ids)->forceDelete();
            foreach ($ids as $id){
                globalFunctions::registerUserActivityLog("deleted","role",$id);
            }
        } else {
            $result = null;
        }
        globalFunctions::flashMessage("delete",$result,"role");

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $account
     * @return \Illuminate\Http\RedirectResponse
     */
    public function softDelete(Request $request,$role_id)
    {
        if (Role::find($role_id) and strtolower(Role::find($role_id)->name) == "admin")
            abort(304,"not permitted to delete this role");
        if ($role_id > 0) {
            $result = Role::find($role_id)->delete();
            globalFunctions::registerUserActivityLog("recycled","role",$role_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = Role::whereIn("id",$ids)->delete();
            foreach ($ids as $id){
                globalFunctions::registerUserActivityLog("recycled","role",$id);
            }
        } else {
            $result = null;
        }

        globalFunctions::flashMessage("softDelete",$result,"role");

        return back();
    }

    public function restore(Request $request,$role_id)
    {
        if ($role_id > 0) {
            $result = Role::onlyTrashed()->find($role_id)->restore();
            globalFunctions::registerUserActivityLog("restored","role",$role_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = Role::onlyTrashed()->whereIn("id",$ids)->restore();
            foreach ($ids as $id){
                globalFunctions::registerUserActivityLog("restored","role",$id);
            }
        } else {
            $result = null;
        }
        globalFunctions::flashMessage("restore",$result,"role");

        return back();
    }


    public function attachPermission(Role $role,int $permission_id){
        $permission = Permission::find($permission_id)->name;
        globalFunctions::registerUserActivityLog("attached_permission",$permission,$role->id);
        $role->permissions()->attach($permission_id);
    }

    public function detachPermission(Role $role,int $permission_id){
        $permission = Permission::find($permission_id)->name;
        globalFunctions::registerUserActivityLog("detached_permission",$permission,$role->id);
        $role->permissions()->detach($permission_id);
    }

}
