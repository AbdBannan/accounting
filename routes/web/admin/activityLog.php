<?php
use Illuminate\Support\Facades\Route;

Route::get("/activityLog/viewUsersActivityLog","activityLogController@view")->name("activityLog.viewUsersActivityLog");
Route::get("/activityLog/showActivityLog/{user_id}","activityLogController@show")->name("activityLog.showActivityLog");
Route::delete("/activityLog/softDeleteActivityLog/{user_id}","activityLogController@softDelete")->name("activityLog.softDeleteActivityLog");
Route::delete("/activityLog/deleteActivityLog/{user_id}","activityLogController@destroy")->name("activityLog.deleteActivityLog");
Route::post("/activityLog/restoreActivityLog/{user_id}","activityLogController@restore")->name("activityLog.restoreActivityLog");
Route::get("/activityLog/viewRecyclebin","activityLogController@viewRecyclebin")->name("activityLog.viewRecyclebin");
