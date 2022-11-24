<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Role;
use Carbon\Carbon;
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
            'file' => ['image','mimes:jpg,png,jpeg,gif,svg']
        ]);

        $fileName = "default_user_img.png";
        if($file = request()->file("file")){
            $fileName = Carbon::now()->format("d_m_Y_h_i_s") . "_" . $request["first_name"] . " " . $request['last_name'] . "." . request()->file("file")->getClientOriginalExtension();
            $file->move("images/usersImages",$fileName);
        }
        $result = User::create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
//            "profile_image" => isset($request["file"]) ? request()->file("file")->getClientOriginalName() : "systemImages/default_user_img.png"
            "profile_image" => $fileName
        ]);
        globalFunctions::initialUserConfig($result);
//        if ($result != null) {
//            session()->flash("success",__("messages.created_successfully",["attribute"=>__("global.user")]));
//        } else {
//            session()->flash("error", __("messages.not_created_successfully",["attribute"=>__("global.user")]));
//        }
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
            'file' => ['image','mimes:jpg,png,jpeg,gif,svg']
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
            $fileName = Carbon::now()->format("d_m_Y_h_i_s") . "_" . $request["first_name"] . " " . $request['last_name'] . "." . $request->file("file")->getClientOriginalExtension();
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
        if ($user->isDirty(["first_name","last_name","profile_image","email","password"])) {
            $result = $user->save();
//            session()->flash("success",__("messages.updated_successfully",["attribute"=>__("global.user")]));
        }
//        else {
//            session()->flash("success",__("messages.nothing_to_be_updated"));
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyUser(Request $request,$user_id)
    {
        if ($user_id > 0) {
            $result = User::withTrashed()->find($user_id)->forceDelete();
            globalFunctions::registerUserActivityLog("deleted","user",$user_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = User::withTrashed()->whereIn("id",$ids)->forceDelete();
            foreach ($ids as $id){
                globalFunctions::registerUserActivityLog("deleted","user",$id);
            }
        } else {
            $result = null;
        }
        globalFunctions::flashMessage("delete",$result,"user");

        return back();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function softDeleteUser(Request $request,$user_id)
    {
        if ($user_id > 0) {
            $result = User::find($user_id)->delete();
            globalFunctions::registerUserActivityLog("recycled","user",$user_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = User::whereIn("id",$ids)->delete();
            foreach ($ids as $id){
                globalFunctions::registerUserActivityLog("recycled","user",$id);
            }
        } else {
            $result = null;
        }

        globalFunctions::flashMessage("softDelete",$result,"user");

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restoreUser(Request $request,$user_id)
    {
        if ($user_id > 0) {
            $result = User::onlyTrashed()->find($user_id)->restore();
            globalFunctions::registerUserActivityLog("restored","user",$user_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = User::onlyTrashed()->whereIn("id",$ids)->restore();
            foreach ($ids as $id){
                globalFunctions::registerUserActivityLog("restored","user",$id);
            }
        } else {
            $result = null;
        }

        globalFunctions::flashMessage("restore",$result,"user");

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

        globalFunctions::registerUserActivityLog("enabled","track_activity",$user->id);
        $user->config()->detach(Config::where("name",$name)->first()->id);
        $user->config()->attach(Config::where("name",$name)->first()->id,["value"=>"true"]);
    }

    public function noTrackUserActivity(User $user){
        $name = "user_activity_log";
//        $config_type = "admin_control";
//        if (!isset(Config::where("name",$name)->first()->name)){
//            Config::create(
//                [
//                    "name" => $name,
//                    "controlled_by" => "user",
//                    "type" => $config_type
//                ]
//            );
//        }
        globalFunctions::registerUserActivityLog("disabled","track_activity",$user->id);
        $user->config()->detach(Config::where("name",$name)->first()->id);
        $user->config()->attach(Config::where("name",$name)->first()->id,["value"=>"false"]);
    }

}
