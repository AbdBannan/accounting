<?php

namespace App\Http\Controllers;

use App\functions\globalFunctions;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use mysql_xdevapi\Result;

class activityLogController extends Controller
{

    public function view() {
        $users = ActivityLog::selectRaw("users.first_name,users.id,users.email")->leftJoin("users","activity_logs.user_id","=","users.id")->groupBy(["first_name","id","email"])->get();
        return view("admin.activityLog.viewUsersActivityLog")->with("usersActivityLog",$users);
    }

    public function show($user_id) {
        return view("admin.activityLog.showUserActivityLog")->with("activityLog",ActivityLog::where("user_id",$user_id)->get());
    }

    public function softDelete($user_id) {
        $result = ActivityLog::where("user_id",$user_id)->delete();
        globalFunctions::flashMessage("softDelete",$result,"activity_log");
        return back();
    }

    public function viewRecyclebin(){
        $deletedUsersActivityLog = ActivityLog::onlyTrashed()->selectRaw("users.first_name,users.id,users.email,activity_logs.deleted_at")->leftJoin("users","activity_logs.user_id","=","users.id")->groupBy(["first_name","id","email","activity_logs.deleted_at"])->get();
        return view("admin.activityLog.recyclebin")->with("deletedUsersActivityLog",$deletedUsersActivityLog);
    }

    public function destroy($user_id) {
        $result = ActivityLog::onlyTrashed()->where("user_id",$user_id)->forcedelete();
        globalFunctions::flashMessage("delete",$result,"activity_log");
        return back();
    }


    public function restore($user_id) {
        $result = ActivityLog::onlyTrashed()->where("user_id",$user_id)->restore();
        globalFunctions::flashMessage("restore",$result,"activity_log");
        return back();
    }

}
