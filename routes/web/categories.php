<?php
use Illuminate\Support\Facades\Route;

Route::get("/category/viewCategories","categoryController@index")->name("category.viewCategories");
Route::get("/category/showCategory/{category}","categoryController@show")->name("category.showCategory");
Route::post("/category/storeCategory","categoryController@store")->name("category.storeCategory");
Route::put("/category/updateCategory/{category}","categoryController@update")->name("category.updateCategory");
Route::delete("/category/deleteCategory/{category_id}","categoryController@destroy")->name("category.deleteCategory");
Route::delete("/category/softDeleteCategory/{category}","categoryController@softDelete")->name("category.softDeleteCategory");
Route::post("/category/restoreCategory/{category_id}","categoryController@restore")->name("category.restoreCategory");
Route::get("/category/viewRecyclebin","categoryController@viewRecyclebin")->name("category.viewRecyclebin");
