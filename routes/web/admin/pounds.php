<?php
use Mailgun\Mailgun;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth","role:admin","saveCurrentRequest"])->group(function (){

    Route::get("/pound/viewPounds","poundController@index")->name("pound.viewPounds");
    Route::post("/pound/storePound","poundController@store")->name("pound.storePound");
    Route::put("/pound/updatePound/{pound}","poundController@update")->name("pound.updatePound");
    Route::delete("/pound/softDeletePound/{pound}","poundController@softDelete")->name("pound.softDeletePound");
    Route::delete("/pound/deletePound/{pound_id}","poundController@destroy")->name("pound.deletePound");
    Route::get("/pound/restorePound/{pound_id}","poundController@restore")->name("pound.restorePound");
    Route::get("/pound/viewRecyclebin","poundController@viewRecyclebin")->name("pound.viewRecyclebin");

});
