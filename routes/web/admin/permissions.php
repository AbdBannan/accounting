<?php
use Illuminate\Support\Facades\Route;

Route::get("permission/viewPermissions","permissionController@index")->name("permission.viewPermissions");
Route::post("permission/storePermission","permissionController@store")->name("permission.storePermission");
Route::delete("permission/deletePermission/{permission_id}","permissionController@destroy")->name("permission.deletePermission");
Route::delete("permission/softDeleteRole/{permission}","permissionController@softDelete")->name("permission.softDeletePermission");
Route::post("permission/restoreRole/{permission_id}","permissionController@restore")->name("permission.restorePermission");

Route::put("permission/updatePermission/{permission}","permissionController@update")->name("permission.updatePermission");
Route::get("permission/viewRecyclebin/","permissionController@viewRecyclebin")->name("permission.viewRecyclebin");

