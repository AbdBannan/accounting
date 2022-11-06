<?php
use Mailgun\Mailgun;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth"])->group(function (){
    Route::get("/config/viewUserConfig","configController@index")->name("config.viewUserConfig");
    Route::post("/config/saveConfig","configController@store")->name("config.saveConfig");

});
