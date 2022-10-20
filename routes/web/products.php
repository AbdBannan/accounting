<?php
use Mailgun\Mailgun;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth","saveCurrentRequest"])->group(function (){
    Route::get("/product/viewProducts","productController@index")->name("product.viewProducts");
    Route::get("/product/showProduct/{product}","productController@show")->name("product.showProduct");
    Route::post("/product/storeProduct","productController@store")->name("product.storeProduct");
    Route::put("/product/updateProduct/{product}","productController@update")->name("product.updateProduct");
    Route::delete("/product/deleteProduct/{product_id}","productController@destroy")->name("product.deleteProduct");
    Route::delete("/product/softDeleteProduct/{product}","productController@softDelete")->name("product.softDeleteProduct");
    Route::get("/product/restoreProduct/{product_id}","productController@restore")->name("product.restoreProduct");
    Route::get("/product/viewRecyclebin","productController@viewRecyclebin")->name("product.viewRecyclebin");
});
