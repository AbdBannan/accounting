<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Mailgun\Mailgun;
use Illuminate\Support\Facades\Mail;

use App\Models\Notification;
use Illuminate\Support\Facades\Route;
use Ricadesign\Contact\Mail\MessageSent;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware(["auth","saveCurrentRequest","localization"])->group(function (){
    Route::get('/',function (){
       return redirect("/welcome");
    });
    Route::get("/help/viewHelp",function (){
        return view("help");
    })->name("help.viewHelp");
});

Route::middleware(["auth","cleanRecyclebinCheck","localization"])->group(function (){
    Route::get('/welcome',function () {
        return view('welcomePage');
    })->name("welcomePage");
});

Route::middleware(["auth","role:admin","saveCurrentRequest","localization"])->group(function (){
    Route::get("/dashboard",function(){
        return view("admin.dashboard");
    })->name("dashboard");
});


Auth::routes();


Route::get("/t",function (){
    $name = "Abdulmoty";
    $email = "bannan51a@gmail.com";
    $message = "hay i am abdulmoty";
    $phone =  "0947276369";
    Mail::to(config('contact.email'))
        ->send(new MessageSent($name, $email, $message, $phone));
});

Route::get("/ttt",function (){
//    dd(\auth()->user()->notifications);

    foreach(\auth()->user()->notifications as $notification){
        echo $notification->name;
    }

});
