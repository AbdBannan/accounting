<?php
use Illuminate\Support\Facades\Route;

Route::get("/config/viewUserConfig","configController@index")->name("config.viewUserConfig");
Route::post("/config/saveConfig","configController@store")->name("config.saveConfig");
