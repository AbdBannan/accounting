<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class notificationController extends Controller
{
    public function index(){
        return view("notifications.viewNotifications")->with(["notifications"=>auth()->user()->notifications]);
    }

    public function seenAll(){
        DB::update("update notifications set has_seen = 1");
        return true;
    }


}
