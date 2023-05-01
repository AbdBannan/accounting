<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

class notificationController extends Controller
{
    public function index(){
        $aa = auth()->user()->notifications;
        $aa = $aa->sortBy(["created_at"]);
        $aa = $aa->reverse();
        return view("notifications.viewNotifications")->with(["notifications"=>$aa]);
    }

    public function seenAll(){
        DB::update("update notifications set has_seen = 1");
        return true;
    }


}
