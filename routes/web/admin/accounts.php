<?php
use Illuminate\Support\Facades\Route;

Route::get("/account/viewAccounts","accountController@index")->name("account.viewAccounts");
Route::get("/account/showAccount/{account}","accountController@show")->name("account.showAccount");
Route::post("/account/storeAccount","accountController@store")->name("account.storeAccount");
Route::put("/account/updateAccount/{account}","accountController@update")->name("account.updateAccount");
Route::delete("/account/deleteAccount/{account_id}","accountController@destroy")->name("account.deleteAccount");
Route::delete("/account/softDeleteAccount/{account}","accountController@softDelete")->name("account.softDeleteAccount");
Route::post("/account/restoreAccount/{account_id}","accountController@restore")->name("account.restoreAccount");
Route::get("/account/viewRecyclebin","accountController@viewRecyclebin")->name("account.viewRecyclebin");
