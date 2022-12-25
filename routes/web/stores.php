<?php
use Illuminate\Support\Facades\Route;

Route::get("/store/viewStores","storeController@index")->name("store.viewStores");
Route::get("/store/showStore/{store}","storeController@show")->name("store.showStore");
Route::post("/store/storeStore","storeController@store")->name("store.storeStore");
Route::put("/store/updateStore/{store}","storeController@update")->name("store.updateStore");
Route::delete("/store/deleteStore/{store_id}","storeController@destroy")->name("store.deleteStore");
Route::delete("/store/softDeleteStore/{store}","storeController@softDelete")->name("store.softDeleteStore");
Route::post("/store/restoreStore/{store_id}","storeController@restore")->name("store.restoreStore");
Route::get("/store/viewRecyclebin","storeController@viewRecyclebin")->name("store.viewRecyclebin");
