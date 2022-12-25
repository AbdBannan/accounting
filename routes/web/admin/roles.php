<?php
use Illuminate\Support\Facades\Route;


Route::get("role/viewRoles","roleController@index")->name("role.viewRoles");
Route::post("role/storeRole","roleController@store")->name("role.storeRole");
Route::put("role/updateRole/{role}","roleController@update")->name("role.updateRole");
Route::delete("role/deleteRole/{role_id}","roleController@destroy")->name("role.deleteRole");
Route::delete("role/softDeleteRole/{role}","roleController@softDelete")->name("role.softDeleteRole");
Route::post("role/restoreRole/{role_id}","roleController@restore")->name("role.restoreRole");
Route::get("role/showRolePermission/{role}","roleController@show")->name("role.showRolePermission");
Route::get("role/viewRecyclebin/","roleController@viewRecyclebin")->name("role.viewRecyclebin");

Route::post("role/{role}/attachPermission/{permission_id}","roleController@attachPermission")->name("role.attachPermission");
Route::post("role/{role}/detachPermission/{permission_id}","roleController@detachPermission")->name("role.detachPermission");
