<?php
use Illuminate\Support\Facades\Route;
Route::middleware(["role:admin"])->group(function (){
    Route::get("user/createUser","userController@createUser")->name("user.createUser");
    Route::post("user/storeUser","userController@storeUser")->name("user.storeUser");
    Route::get("user/viewUsers","userController@viewUsers")->name("user.viewUsers");
    Route::delete("user/deleteUser/{user_id}","userController@destroyUser")->name("user.deleteUser");
    Route::delete("user/softDeleteUser/{user}","userController@softDeleteUser")->name("user.softDeleteUser");
    Route::get("user/restoreUser/{user_id}","userController@restoreUser")->name("user.restoreUser");
    Route::get("user/viewRecyclebin","userController@viewRecyclebin")->name("user.viewRecyclebin");

    Route::post("user/activateUser/{user}","userController@activateUser")->name("user.activateUser");
    Route::post("user/deactivateUser/{user}","userController@deactivateUser")->name("user.deactivateUser");

    Route::post("user/{user}/attachRole/{role_id}","userController@attachRole")->name("user.attachRole");
    Route::post("user/{user}/detachRole/{role_id}","userController@detachRole")->name("user.detachRole");

    Route::post("/user/trackUserActivity/{user}","userController@trackUserActivity")->name("user.trackUserActivity");
    Route::post("/user/noTrackUserActivity/{user}","userController@noTrackUserActivity")->name("user.noTrackUserActivity");

});

Route::middleware(["can:view,user"])->group(function (){
    Route::get("user/{user}/showUser","userController@showUser")->name("user.showUser");
});

Route::middleware(["can:update,user"])->group(function (){
    Route::PUT("user/updateUser/{user}","userController@updateUser")->name("user.updateUser");
});


