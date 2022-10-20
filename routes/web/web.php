<?php

use App\Models\Journal;
use App\Models\Product;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
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
    Route::get('/',function () {
        return view('dashboard');
    });

    Route::get("/dashboard",function(){
        return view("dashboard");
    })->name("dashboard");

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Auth::routes();

//Route::get("/admin/backup","BackupController@index")->name("admin.backup");

//
//
//Route::get("t",function (){
//
//    try {
//        // start the backup process
//        Artisan::call('backup:run');
////        $output = Artisan::output();
//        // log the results
////        Log::info("Backpack\BackupManager -- new backup started from admin interface \r\n" . $output);
//        // return the results as a response to the ajax call
////        Alert::success('New backup created');
//        return redirect()->back();
//    } catch (Exception $e) {
//        dd($e->getMessage());
//        return redirect()->back();
//    }
//});
//
