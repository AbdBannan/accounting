<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\functions\globalFunctions;

class userController extends Controller
{

    public function createUser(User $user){
        return view("admin.users.createUser")->with("user",$user);
    }

    public function storeUser(Request $request){
        $this->validate($request, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);


        if($file = request()->file("file")){
            $fileName =  $file->getClientOriginalName();
            $file->move("images/usersImages",$fileName);
        }
        $result = User::create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            "profile_image" => isset($request["file"]) ? request()->file("file")->getClientOriginalName() : "systemImages/default_user_img.png"
        ]);

        $result->config()->attach(1,["value","arabic"]);
        $result->config()->attach(5,["value","Syrian"]);
//        if ($result != null) {
//            session()->flash("success",__("messages.created_successfully",["attribute"=>__("global.user",[],session("lang"))],session("lang")));
//        } else {
//            session()->flash("error", __("messages.not_created_successfully",["attribute"=>__("global.user",[],session("lang"))], session("lang")));
//        }
        globalFunctions::flashMessage("create",$result,"user");
        globalFunctions::registerUserActivityLog("added","user",$result->id);

        return redirect()->back();
    }

    public function showUser(User $user){
        globalFunctions::registerUserActivityLog("seen","user",$user->id);
        return view("admin.users.showUser")->with("user",$user);
    }

    public function updateUser(User $user,Request $request){
        $this->validate($request,[
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
        ]);
        if ($user->email != $request["email"]){
            $this->validate($request,[
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            ]);
        }
        if ($request["password"]!=null) {
            $this->validate($request,[
                'password' => [ 'min:8', 'confirmed'],
            ]);
            $user->password = Hash::make($request['password']);
        }

        $oldProfileImage = $user->profile_image;
        if($file = $request->file("file")){
            $fileName =  $file->getClientOriginalName();
            if ($oldProfileImage != "images/systemImages/default_user_img.png" and file_exists(public_path($oldProfileImage))){
                unlink(public_path($oldProfileImage));
            }
            $file->move("images/usersImages",$fileName);
            $user->profile_image = $fileName;
        }


        $user->first_name = $request['first_name'];
        $user->last_name = $request['last_name'];
        $user->email = $request['email'];

        $result = null;
        if ($user->isDirty(["firs_name","last_name","profile_image","email","password"])) {
            $result = $user->save();
//            session()->flash("success",__("messages.updated_successfully",["attribute"=>__("global.user",[],session("lang"))],session("lang")));
        }
//        else {
//            session()->flash("success",__("messages.nothing_to_be_updated",[],session("lang")));
//        }
        globalFunctions::flashMessage("update",$result,"user");
        globalFunctions::registerUserActivityLog("updated","user",$user->id);

        return redirect()->back();
    }

    public function viewUsers() {
        $users = User::all();
        globalFunctions::registerUserActivityLog("seen_all","users",null);
        return view("admin.users.viewUsers")->with("users",$users);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyUser($user_id)
    {
        $image = User::onlyTrashed()->find($user_id)->first()->profile_image;
        $result = User::onlyTrashed()->find($user_id)->forceDelete();

        if ($result!=null) {
            if ($image != "images/systemImages/default_user_img.png" and file_exists(public_path($image))){
                unlink(public_path($image));
            }
//            session()->flash("success",__("messages.deleted_successfully",["attribute"=>__("global.user",[],session("lang"))],session("lang")));
        }
//        else {
//            session()->flash("error",__("messages.not_deleted_successfully",["attribute"=>__("global.user",[],session("lang"))],session("lang")));
//        }
        globalFunctions::flashMessage("delete",$result,"user");
        globalFunctions::registerUserActivityLog("deleted","user",$user_id);

        return back();
    }

//    public function multiDelete(Request $request) {
//        $ids = $request->delete_users;
//        if (auth()->user()->getConfig("use_recyclebin") == "true"){
//            $result = User::whereIn("id",$ids)->delete();
//        } else {
//            $result = User::whereIn("id",$ids)->forceDelete();
//        }
//        globalFunctions::flashMessage("delete",$result,"user");
//        foreach ($ids as $id){
//
//        }
//        globalFunctions::registerUserActivityLog("deleted","users", $ids);
//
//        return back();
//    }


    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewRecyclebin()
    {
        $deletedUsers = User::onlyTrashed()->get();
        globalFunctions::registerUserActivityLog("seen","users_recyclebin",null);

        return view("admin.users.recyclebin")->with("deletedUsers",$deletedUsers);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $account
     * @return \Illuminate\Http\Response
     */
    public function softDeleteUser(User $user)
    {

        $result = $user->delete();

//        if ($result!=null) {
//            session()->flash("success",__("messages.recycled_successfully",["attribute"=>__("global.user",[],session("lang"))],session("lang")));
//        }else{
//            session()->flash("error",__("messages.not_recycled_successfully",["attribute"=>__("global.user",[],session("lang"))],session("lang")));
//        }
        globalFunctions::flashMessage("softDelete",$result,"user");
        globalFunctions::registerUserActivityLog("recycled","user",$user->id);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restoreUser($user_id)
    {
        $result = User::onlyTrashed()->find($user_id)->restore();
//        if ($result!=null) {
//            session()->flash("success",__("messages.restored_successfully",["attribute"=>__("global.user",[],session("lang"))],session("lang")));
//        }else{
//            session()->flash("success",__("messages.restored_successfully",["attribute"=>__("global.user",[],session("lang"))],session("lang")));
//        }
        globalFunctions::flashMessage("restore",$result,"user");
        globalFunctions::registerUserActivityLog("restored","user",$user_id);

        return back();
    }

    public function activateUser(User $user){
        $user->update(["active"=>true]);
        globalFunctions::registerUserActivityLog("activated","user",$user->id);

        return redirect()->back();
    }

    public function deactivateUser(User $user){
        $user->update(["active"=>false]);
        globalFunctions::registerUserActivityLog("deactivated","user",$user->id);

        return redirect()->back();
    }

    public function attachRole(User $user,int $role_id){
        $role = Role::find($role_id)->name;
        globalFunctions::registerUserActivityLog("attached","$role role",$user->id);

        $user->roles()->attach($role_id);
    }

    public function detachRole(User $user,int $role_id){
        $role = Role::find($role_id)->name;
        globalFunctions::registerUserActivityLog("detached","$role role",$user->id);

        $user->roles()->detach($role_id);
    }

    public function trackUserActivity(User $user){
        $name = "user_activity_log";
        $config_type = "admin_control";
        if (!isset(Config::where("name",$name)->first()->name)){
            Config::create(
                [
                    "name" => $name,
                    "controlled_by" => "user",
                    "type" => $config_type
                ]
            );
        }
        $user->config()->detach(Config::where("name",$name)->first()->id);
        $user->config()->attach(Config::where("name",$name)->first()->id,["value"=>"true"]);
    }

    public function noTrackUserActivity(User $user){
        $name = "user_activity_log";
        $config_type = "admin_control";
        if (!isset(Config::where("name",$name)->first()->name)){
            Config::create(
                [
                    "name" => $name,
                    "controlled_by" => "user",
                    "type" => $config_type
                ]
            );
        }
        $user->config()->detach(Config::where("name",$name)->first()->id);
        $user->config()->attach(Config::where("name",$name)->first()->id,["value"=>"false"]);
    }

}
