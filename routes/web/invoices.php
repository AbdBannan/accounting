<?php

use App\Http\Controllers\invoiceController;
use Mailgun\Mailgun;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth"])->group(function (){

    Route::get("/invoice/createInvoice/{invoice_type}","invoiceController@create")->name("invoice.createInvoice");
    Route::post("/invoice/storeInvoice/{invoice_type}","invoiceController@store")->name("invoice.storeInvoice");
    Route::get("/invoice/viewInvoices/{invoice_type}","invoiceController@index")->name("invoice.viewInvoices");
    Route::get("/invoice/showInvoice/{invoice_id}",[invoiceController::class,"show"])->name("invoice.showInvoice");
//    Route::get("/invoice/editInvoice/{invoice_id}","invoiceController@edit")->name("invoice.editInvoice");
    Route::put("/invoice/updateInvoice/{invoice_type}/{invoice_id}","invoiceController@update")->name("invoice.updateInvoice");
    Route::delete("/invoice/deleteInvoice/{invoice_id}","invoiceController@destroy")->name("invoice.deleteInvoice");
    Route::delete("/invoice/softDeleteInvoice/{invoice_id}","invoiceController@softDelete")->name("invoice.softDeleteInvoice");
    Route::get("/invoice/restoreInvoice/{invoice_id}","invoiceController@restore")->name("invoice.restoreInvoice");
    Route::get("/invoice/viewInvoiceRecyclebin","invoiceController@viewRecyclebin")->name("invoice.viewInvoiceRecyclebin");
    Route::get("/invoice/searchInvoice",[invoiceController::class,'search'])->name("invoice.searchInvoice");
    Route::get("/invoice/showSearchInvoice/{invoice_type}",[invoiceController::class,"showSearchInvoice"])->name("invoice.showSearchInvoice");





    Route::get("/invoice/createCashInvoice","cashController@create")->name("invoice.createCashInvoice");
    Route::post("/invoice/storeCashInvoice","cashController@store")->name("invoice.storeCashInvoice");
    Route::get("/invoice/viewCashInvoices","cashController@index")->name("invoice.viewCashInvoices");
    Route::get("/invoice/showCashInvoice/{invoice_id}","cashController@show")->name("invoice.showCashInvoice");
//    Route::post("/invoice/editCashInvoice/{invoice_id}","cashController@edit")->name("invoice.editCashInvoice");
    Route::put("/invoice/updateCashInvoice/{invoice_id}","cashController@update")->name("invoice.updateCashInvoice");
    Route::delete("/invoice/deleteCashInvoice/{invoice_id}","cashController@destroy")->name("invoice.deleteCashInvoice");
    Route::delete("/invoice/softDeleteCashInvoice/{invoice_id}","cashController@softDelete")->name("invoice.softDeleteCashInvoice");
    Route::get("/invoice/restoreCashInvoice/{invoice_id}","cashController@restore")->name("invoice.restoreCashInvoice");
    Route::get("/invoice/viewCashRecyclebin","cashController@viewRecyclebin")->name("invoice.viewCashRecyclebin");
    Route::get("/invoice/searchCashInvoice","cashController@search")->name("invoice.searchCashInvoice");
    Route::get("/invoice/showSearchCashInvoice","cashController@showSearchInvoice")->name("invoice.showSearchCashInvoice");



    Route::get("/invoice/createProductMovementInvoice","productMovementController@create")->name("invoice.createProductMovementInvoice");
    Route::post("/invoice/storeProductMovementInvoice","productMovementController@store")->name("invoice.storeProductMovementInvoice");
    Route::get("/invoice/viewProductMovementInvoices","productMovementController@index")->name("invoice.viewProductMovementInvoices");
    Route::get("/invoice/showProductMovementInvoice/{invoice_id}","productMovementController@show")->name("invoice.showProductMovementInvoice");
    Route::put("/invoice/updateProductMovementInvoice/{invoice_id}","productMovementController@update")->name("invoice.updateProductMovementInvoice");
    Route::delete("/invoice/deleteProductMovementInvoice/{invoice_id}","productMovementController@destroy")->name("invoice.deleteProductMovementInvoice");
    Route::delete("/invoice/softDeleteProductMovementInvoice/{invoice_id}","productMovementController@softDelete")->name("invoice.softDeleteProductMovementInvoice");
    Route::get("/invoice/restoreProductMovementInvoice/{invoice_id}","productMovementController@restore")->name("invoice.restoreProductMovementInvoice");
    Route::get("/invoice/viewProductMovementRecyclebin","productMovementController@viewRecyclebin")->name("invoice.viewProductMovementRecyclebin");
    Route::get("/invoice/searchProductMovementInvoice","productMovementController@search")->name("invoice.searchProductMovementInvoice");
    Route::get("/invoice/showSearchProductMovementInvoice","productMovementController@showSearchInvoice")->name("invoice.showSearchProductMovementInvoice");




    Route::get("/invoice/createManufacturingInvoice","manufacturingController@create")->name("invoice.createManufacturingInvoice");
    Route::post("/invoice/storeManufacturingInvoice","manufacturingController@store")->name("invoice.storeManufacturingInvoice");
    Route::get("/invoice/viewManufacturingInvoices","manufacturingController@index")->name("invoice.viewManufacturingInvoices");
    Route::get("/invoice/showManufacturingInvoice/{invoice_id}","manufacturingController@show")->name("invoice.showManufacturingInvoice");
    Route::put("/invoice/updateManufacturingInvoice/{invoice_id}","manufacturingController@update")->name("invoice.updateManufacturingInvoice");
    Route::delete("/invoice/deleteManufacturingInvoice/{invoice_id}","manufacturingController@destroy")->name("invoice.deleteManufacturingInvoice");
    Route::delete("/invoice/softDeleteManufacturingInvoice/{invoice_id}","manufacturingController@softDelete")->name("invoice.softDeleteManufacturingInvoice");
    Route::get("/invoice/restoreManufacturingInvoice/{invoice_id}","MmanufacturingController@restore")->name("invoice.restoreManufacturingInvoice");
    Route::get("/invoice/viewManufacturingRecyclebin","manufacturingController@viewRecyclebin")->name("invoice.viewManufacturingRecyclebin");
    Route::get("/invoice/searchManufacturingInvoice","manufacturingController@search")->name("invoice.searchManufacturingInvoice");
    Route::get("/invoice/showSearchManufacturingInvoice","manufacturingController@showSearchInvoice")->name("invoice.showSearchManufacturingInvoice");




    Route::post("/manufacturingTemplate/storeManufacturingTemplate","manufacturingTemplateController@store")->name("manufacturingTemplate.storeManufacturingTemplate");
//    Route::put("/manufacturingTemplate/updateManufacturingTemplate/{invoice_id}","manufacturingTemplateController@update")->name("manufacturingTemplate.updateManufacturingTemplate");
    Route::delete("/invoice/deleteManufacturingTemplate/{invoice_id}","manufacturingTemplateController@destroy")->name("manufacturingTemplate.deleteManufacturingTemplate");
    Route::delete("/invoice/softDeleteManufacturingTemplate/{invoice_id}","manufacturingTemplateController@softDelete")->name("manufacturingTemplate.softDeleteManufacturingTemplate");
    Route::get("/invoice/restoreManufacturingTemplate/{invoice_id}","manufacturingTemplateController@restore")->name("manufacturingTemplate.restoreManufacturingTemplate");
    Route::get("/invoice/viewManufacturingTemplatesRecyclebin","manufacturingTemplateController@viewRecyclebin")->name("manufacturingTemplate.viewManufacturingTemplatesRecyclebin");

});
