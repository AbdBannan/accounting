<?php

use App\Models\Journal;
use App\Models\Product;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Mailgun\Mailgun;
use Illuminate\Support\Facades\Mail;


use Illuminate\Support\Facades\Route;
use Prologue\Alerts\Facades\Alert;

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
Route::middleware(["auth","saveCurrentRequest"])->group(function (){
    Route::get('/',function (){
       return redirect("/welcome");
    });
    Route::get('/welcome',function () {
        return view('welcomePage');
    })->name("welcomePage");
//    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});
Route::middleware(["auth","role:admin","saveCurrentRequest"])->group(function (){
    Route::get("/dashboard",function(){
        return view("admin.dashboard");
    })->name("dashboard");
});


Auth::routes();


Route::get("/t",function (){
   return URL:: previous();
});
