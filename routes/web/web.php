<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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



Route::middleware(["auth","activated","saveRequestHistory","localization"])->group(function (){
    Route::get('/',function (){
       return redirect("/welcome");
    });
    Route::get("/help/viewHelp",function (){
        return view("help");
    })->name("help.viewHelp");
});

Route::middleware(["auth","activated","checkToClearRecyclebin","saveRequestHistory","localization"])->group(function (){
    Route::get('/welcome',function () {
        return view('welcomePage');
    })->name("welcomePage");
});

Route::middleware(["auth","activated","role:admin","saveRequestHistory","localization"])->group(function (){
    Route::get("/dashboard",function(){
        return view("admin.dashboard");
    })->name("dashboard");
});


Auth::routes(["verify"=>true]);
//Route::get("auth/verify",[App\Http\Controllers\Auth\VerificationController::class,"show"])->name("auth.verify");
//Route::post("auth/verification/resend",[App\Http\Controllers\Auth\VerificationController::class,"resend"])->name("verification.resend");
//Route::post("auth/verification/verify",[App\Http\Controllers\Auth\VerificationController::class,"verify"])->name("verification.verify");

Route::get("/t",function (){
    $name = "Abdulmoty";
    $email = "bannan51a@gmail.com";
    $message = "hay i am abdulmoty";
    $phone =  "0947276369";
    Mail::to(config('contact.email'))
        ->send(new MessageSent($name, $email, $message, $phone));
});
Route::middleware(["auth","activated"])->group(function (){
    Route::get("/back",function (){
        if(session("request_history")){
            $routes = session("request_history");
            if (count($routes)-2 < 0 or count($routes)-1 < 0){
                return back();
            }
            $route = $routes[count($routes)-2];
            unset($routes[count($routes)-1]);
            session(["request_history"=>$routes]);
            return redirect($route);
        }
    })->name("back");

});
//
//Route::middleware(["auth"])->group(function (){
//    Route::get("/translate/{word}",function ($word){
//        return __("global.$word");
//    })->name("translate");
//});
//
//
Route::get("/tt",function (){
//
//    \Illuminate\Support\Facades\Artisan::call("schedule:work");
////    return view("test");
//    dd(Carbon::now()->format("Y-m-d g:i:s a"));

    dd(Route::getRoutes());
});
