<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Mailgun\Mailgun;
use Illuminate\Support\Facades\Mail;


use Illuminate\Support\Facades\Route;

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
//    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
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


//Route::post("/t",function (){
//    return "sdfgdgrd";
//    if ($file = request()->file("image")){
////        $file->move("images","new.jpg");
//        return "good";
//    } else {
//        return "not good";
//    }
//});


//169.254.141.42/accounting/public/t
