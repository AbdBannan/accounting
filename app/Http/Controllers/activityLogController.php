<?php

namespace App\Http\Controllers;

use App\functions\globalFunctions;
use App\Models\ActivityLog;
use Illuminate\Http\Request;


class activityLogController extends Controller
{

    public function view() {
        $users = ActivityLog::selectRaw("users.first_name,users.id,users.email")->join("users","activity_logs.user_id","=","users.id")->groupBy(["first_name","id","email"])->get();
        return view("admin.activityLog.viewUsersActivityLog")->with("usersActivityLog",$users);
    }

    public function show($user_id) {
        return view("admin.activityLog.showUserActivityLog")->with("activityLog",ActivityLog::where("user_id",$user_id)->get());
    }

    public function softDelete(Request $request,$user_id) {
        if ($user_id > 0){
            $result = ActivityLog::where("user_id",$user_id)->delete();
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = ActivityLog::whereIn("user_id",$ids)->delete();
        } else {
            $result = null;
        }
        globalFunctions::flashMessage("softDelete",$result,"activity_log");
        return back();
    }

    public function viewRecyclebin(){
        $deletedUsersActivityLog = ActivityLog::onlyTrashed()->selectRaw("users.first_name,users.id,users.email,activity_logs.deleted_at")->leftJoin("users","activity_logs.user_id","=","users.id")->groupBy(["first_name","id","email","activity_logs.deleted_at"])->get();
        return view("admin.activityLog.recyclebin")->with("deletedUsersActivityLog",$deletedUsersActivityLog);
    }

    public function destroy(Request $request,$user_id) {
        if ($user_id > 0) {
            $result = ActivityLog::withTrashed()->where("user_id",$user_id)->forceDelete();
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = ActivityLog::withTrashed()->whereIn("user_id",$ids)->forceDelete();
        } else {
            $result = null;
        }
        globalFunctions::flashMessage("delete",$result,"activity_log");
        return back();
    }


    public function restore(Request $request,$user_id) {
        if ($user_id > 0) {
            $result = ActivityLog::onlyTrashed()->where("user_id",$user_id)->restore();
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = ActivityLog::onlyTrashed()->whereIn("user_id",$ids)->restore();
        } else {
            $result = null;
        }
        globalFunctions::flashMessage("restore",$result,"activity_log");
        return back();
    }

}
