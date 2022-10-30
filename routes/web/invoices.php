<?php
//
//use App\Http\Controllers\invoiceController;
//use Mailgun\Mailgun;
//use Illuminate\Support\Facades\Route;
//
//Route::middleware(["auth","saveCurrentRequest"])->group(function (){
////    Route::get("/invoice/sale/createSaleInvoice","saleController@create")->name("invoice.createSaleInvoice");
////    Route::post("/invoice/sale/storeSaleInvoice","saleController@store")->name("invoice.storeSaleInvoice");
////    Route::get("/invoice/sale/viewSaleInvoices","saleController@index")->name("invoice.viewSaleInvoices");
////    Route::post("/invoice/sale/showSaleInvoice/{invoice}","saleController@show")->name("invoice.showSaleInvoice");
////    Route::post("/invoice/sale/editSaleInvoice/{invoice}","saleController@edit")->name("invoice.editSaleInvoice");
////    Route::put("/invoice/sale/updateSaleInvoice/{invoice}","saleController@update")->name("invoice.updateSaleInvoice");
////    Route::delete("/invoice/sale/deleteSaleInvoice/{invoice}","saleController@destroy")->name("invoice.deleteSaleInvoice");
////    Route::delete("/invoice/sale/softDeleteSaleInvoice/{invoice}","saleController@softDelete")->name("invoice.softDeleteSaleInvoice");
////    Route::post("/invoice/sale/restoreSaleInvoice/{invoice}","saleController@restore")->name("invoice.restoreSaleInvoice");
////    Route::post("/invoice/sale/viewRecyclebin","saleController@viewRecyclebin")->name("invoice.viewRecyclebin");
////
////
////
////
////    Route::get("/invoice/purchase/createPurchaseInvoice","purchaseController@create")->name("invoice.createPurchaseInvoice");
////    Route::post("/invoice/purchase/storePurchaseInvoice","purchaseController@store")->name("invoice.storePurchaseInvoice");
////    Route::post("/invoice/purchase/viewPurchaseInvoices","purchaseController@index")->name("invoice.viewPurchaseInvoices");
////    Route::post("/invoice/purchase/showPurchaseInvoice/{invoice}","purchaseController@show")->name("invoice.showPurchaseInvoice");
////    Route::post("/invoice/purchase/editPurchaseInvoice/{invoice}","purchaseController@edit")->name("invoice.editPurchaseInvoice");
////    Route::put("/invoice/purchase/updatePurchaseInvoice/{invoice}","purchaseController@update")->name("invoice.updatePurchaseInvoice");
////    Route::delete("/invoice/purchase/deletePurchaseInvoice/{invoice}","purchaseController@destroy")->name("invoice.deletePurchaseInvoice");
////    Route::delete("/invoice/purchase/softDeletePurchaseInvoice/{invoice}","purchaseController@softDelete")->name("invoice.softDeletePurchaseInvoice");
////    Route::post("/invoice/purchase/restorePurchaseInvoice/{invoice}","purchaseController@restore")->name("invoice.restorePurchaseInvoice");
////    Route::post("/invoice/purchase/viewRecyclebin","purchaseController@viewRecyclebin")->name("invoice.viewRecyclebin");
////
////
////
////
////
////
////    Route::get("/invoice/saleReturn/createSaleReturnInvoice","saleReturnController@create")->name("invoice.createSaleReturnInvoice");
////    Route::post("/invoice/saleReturn/storeSaleReturnInvoice","saleReturnController@store")->name("invoice.storeSaleReturnInvoice");
////    Route::post("/invoice/saleReturn/viewSaleReturnInvoices","saleReturnController@index")->name("invoice.viewSaleReturnInvoices");
////    Route::post("/invoice/saleReturn/showSaleReturnInvoice/{invoice}","saleReturnController@show")->name("invoice.showSaleReturnInvoice");
////    Route::post("/invoice/saleReturn/editSaleReturnInvoice/{invoice}","saleReturnController@edit")->name("invoice.editSaleReturnInvoice");
////    Route::put("/invoice/saleReturn/updateSaleReturnInvoice/{invoice}","saleReturnController@update")->name("invoice.updateSaleReturnInvoice");
////    Route::delete("/invoice/saleReturn/deleteSaleReturnInvoice/{invoice}","saleReturnController@destroy")->name("invoice.deleteSaleReturnInvoice");
////    Route::delete("/invoice/saleReturn/softDeleteSaleReturnInvoice/{invoice}","saleReturnController@softDelete")->name("invoice.softDeleteSaleReturnInvoice");
////    Route::post("/invoice/saleReturn/restoreSaleReturnInvoice/{invoice}","saleReturnController@restore")->name("invoice.restoreSaleReturnInvoice");
////    Route::post("/invoice/saleReturn/viewRecyclebin","saleReturnController@viewRecyclebin")->name("invoice.viewRecyclebin");
////
////
////
////
////
////
////    Route::get("/invoice/purchaseReturn/createPurchaseReturnInvoice","purchaseReturnController@create")->name("invoice.createPurchaseReturnInvoice");
////    Route::post("/invoice/purchaseReturn/storePurchaseReturnInvoice","purchaseReturnController@store")->name("invoice.storePurchaseReturnInvoice");
////    Route::post("/invoice/purchaseReturn/viewPurchaseReturnInvoices","purchaseReturnController@index")->name("invoice.viewPurchaseReturnInvoices");
////    Route::post("/invoice/purchaseReturn/showPurchaseReturnInvoice/{invoice}","purchaseReturnController@show")->name("invoice.showPurchaseReturnInvoice");
////    Route::post("/invoice/purchaseReturn/editPurchaseReturnInvoice/{invoice}","purchaseReturnController@edit")->name("invoice.editPurchaseReturnInvoice");
////    Route::put("/invoice/purchaseReturn/updatePurchaseReturnInvoice/{invoice}","purchaseReturnController@update")->name("invoice.updatePurchaseReturnInvoice");
////    Route::delete("/invoice/purchaseReturn/deletePurchaseReturnInvoice/{invoice}","purchaseReturnController@destroy")->name("invoice.deletePurchaseReturnInvoice");
////    Route::delete("/invoice/purchaseReturn/softDeletePurchaseReturnInvoice/{invoice}","purchaseReturnController@softDelete")->name("invoice.softDeletePurchaseReturnInvoice");
////    Route::post("/invoice/purchaseReturn/restorePurchaseReturnInvoice/{invoice}","purchaseReturnController@restore")->name("invoice.restorePurchaseReturnInvoice");
////    Route::post("/invoice/purchaseReturn/viewRecyclebin","purchaseReturnController@viewRecyclebin")->name("invoice.viewRecyclebin");
////
////
////
////
////
////
////
////    Route::get("/invoice/payment/createPaymentInvoice","paymentController@create")->name("invoice.createPaymentInvoice");
////    Route::post("/invoice/payment/storePaymentInvoice","paymentController@store")->name("invoice.storePaymentInvoice");
////    Route::post("/invoice/payment/viewPaymentInvoices","paymentController@index")->name("invoice.viewPaymentInvoices");
////    Route::post("/invoice/payment/showPaymentInvoice/{invoice}","paymentController@show")->name("invoice.showPaymentInvoice");
////    Route::post("/invoice/payment/editPaymentInvoice/{invoice}","paymentController@edit")->name("invoice.editPaymentInvoice");
////    Route::put("/invoice/payment/updatePaymentInvoice/{invoice}","paymentController@update")->name("invoice.updatePaymentInvoice");
////    Route::delete("/invoice/payment/deletePaymentInvoice/{invoice}","paymentController@destroy")->name("invoice.deletePaymentInvoice");
////    Route::delete("/invoice/payment/softDeletePaymentInvoice/{invoice}","paymentController@softDelete")->name("invoice.softDeletePaymentInvoice");
////    Route::post("/invoice/payment/restorePaymentInvoice/{invoice}","paymentController@restore")->name("invoice.restorePaymentInvoice");
////    Route::post("/invoice/payment/viewRecyclebin","paymentController@viewRecyclebin")->name("invoice.viewRecyclebin");
////
////
////
////
////
////
////    Route::get("/invoice/receive/createReceiveInvoice","receiveController@create")->name("invoice.createReceiveInvoice");
////    Route::post("/invoice/receive/storeReceiveInvoice","receiveController@store")->name("invoice.storeReceiveInvoice");
////    Route::post("/invoice/receive/viewReceiveInvoices","receiveController@index")->name("invoice.viewReceiveInvoices");
////    Route::post("/invoice/receive/showReceiveInvoice/{invoice}","receiveController@show")->name("invoice.showReceiveInvoice");
////    Route::post("/invoice/receive/editReceiveInvoice/{invoice}","receiveController@edit")->name("invoice.editReceiveInvoice");
////    Route::put("/invoice/receive/updateReceiveInvoice/{invoice}","receiveController@update")->name("invoice.updateReceiveInvoice");
////    Route::delete("/invoice/receive/deleteReceiveInvoice/{invoice}","receiveController@destroy")->name("invoice.deleteReceiveInvoice");
////    Route::delete("/invoice/receive/softDeleteReceiveInvoice/{invoice}","receiveController@softDelete")->name("invoice.softDeleteReceiveInvoice");
////    Route::post("/invoice/receive/restoreReceiveInvoice/{invoice}","receiveController@restore")->name("invoice.restoreReceiveInvoice");
////    Route::post("/invoice/receive/viewRecyclebin","receiveController@viewRecyclebin")->name("invoice.viewRecyclebin");
////
//
//
//
//    Route::get("/invoice/createInvoice/{invoice_type}","invoiceController@create")->name("invoice.createInvoice");
//    Route::post("/invoice/storeInvoice/{invoice_type}","invoiceController@store")->name("invoice.storeInvoice");
//    Route::get("/invoice/viewInvoices{invoice_type}","invoiceController@index")->name("invoice.viewInvoices");
//    Route::get("/invoice/showInvoice/{invoice_id}/{invoice_type}","invoiceController@show")->name("invoice.showInvoice");
//    Route::post("/invoice/editInvoice/{invoice_id}","invoiceController@edit")->name("invoice.editInvoice");
//    Route::put("/invoice/updateInvoice/{invoice_type}/{invoice_id}","invoiceController@update")->name("invoice.updateInvoice");
//    Route::delete("/invoice/deleteInvoice/{invoice_id}","invoiceController@destroy")->name("invoice.deleteInvoice");
//    Route::delete("/invoice/softDeleteInvoice/{invoice_id}","invoiceController@softDelete")->name("invoice.softDeleteInvoice");
//    Route::get("/invoice/restoreInvoice/{invoice_id}","invoiceController@restore")->name("invoice.restoreInvoice");
//    Route::get("/invoice/viewRecyclebin","invoiceController@viewRecyclebin")->name("invoice.viewRecyclebin");
//    Route::get("/invoice/searchInvoice",[invoiceController::class,'search'])->name("invoice.searchInvoice");
//    Route::get("/invoice/showSearchInvoice/{invoice_type}","invoiceController@showSearchInvoice")->name("invoice.showSearchInvoice");
//
//
//
//
//
//    Route::get("/invoice/createCashInvoice","cashController@create")->name("invoice.createCashInvoice");
//    Route::post("/invoice/storeCashInvoice","cashController@store")->name("invoice.storeCashInvoice");
//    Route::get("/invoice/viewCashInvoices","cashController@index")->name("invoice.viewCashInvoices");
//    Route::get("/invoice/showCashInvoice/{invoice_id}","cashController@show")->name("invoice.showCashInvoice");
//    Route::post("/invoice/editCashInvoice/{invoice_id}","cashController@edit")->name("invoice.editCashInvoice");
//    Route::put("/invoice/updateCashInvoice/{invoice_id}","cashController@update")->name("invoice.updateCashInvoice");
//    Route::delete("/invoice/deleteCashInvoice/{invoice_id}","cashController@destroy")->name("invoice.deleteCashInvoice");
//    Route::delete("/invoice/softDeleteCashInvoice/{invoice_id}","cashController@softDelete")->name("invoice.softDeleteCashInvoice");
//    Route::get("/invoice/restoreCashInvoice/{invoice_id}","cashController@restore")->name("invoice.restoreCashInvoice");
//    Route::get("/invoice/viewCashRecyclebin","cashController@viewRecyclebin")->name("invoice.viewCashRecyclebin");
//    Route::post("/invoice/searchCashInvoice","cashController@search")->name("invoice.searchCashInvoice");
//    Route::get("/invoice/showSearchCashInvoice","cashController@showSearchInvoice")->name("invoice.showSearchCashInvoice");
//
//
//
//    Route::get("/invoice/createProductMovementInvoice","productMovementController@create")->name("invoice.createProductMovementInvoice");
//    Route::post("/invoice/storeProductMovementInvoice","productMovementController@store")->name("invoice.storeProductMovementInvoice");
//    Route::get("/invoice/viewProductMovementInvoices","productMovementController@index")->name("invoice.viewProductMovementInvoices");
//    Route::get("/invoice/showProductMovementInvoice/{invoice_id}","productMovementController@show")->name("invoice.showProductMovementInvoice");
//    Route::post("/invoice/editProductMovementInvoice/{invoice_id}","productMovementController@edit")->name("invoice.editProductMovementInvoice");
//    Route::put("/invoice/updateProductMovementInvoice/{invoice_id}","productMovementController@update")->name("invoice.updateProductMovementInvoice");
//    Route::delete("/invoice/deleteProductMovementInvoice/{invoice_id}","productMovementController@destroy")->name("invoice.deleteProductMovementInvoice");
//    Route::delete("/invoice/softDeleteProductMovementInvoice/{invoice_id}","productMovementController@softDelete")->name("invoice.softDeleteProductMovementInvoice");
//    Route::get("/invoice/restoreProductMovementInvoice/{invoice_id}","productMovementController@restore")->name("invoice.restoreProductMovementInvoice");
//    Route::get("/invoice/viewProductMovementRecyclebin","productMovementController@viewRecyclebin")->name("invoice.viewProductMovementRecyclebin");
//    Route::post("/invoice/searchProductMovementInvoice","productMovementController@search")->name("invoice.searchProductMovementInvoice");
//    Route::get("/invoice/showSearchProductMovementInvoice","productMovementController@showSearchInvoice")->name("invoice.showSearchProductMovementInvoice");
//
//});












////////////
///
///
///
///
///
///
//
//use App\Http\Controllers\invoiceController;
//use Mailgun\Mailgun;
//use Illuminate\Support\Facades\Route;
//
//Route::middleware(["auth","saveCurrentRequest"])->group(function (){
//
//    Route::get("/invoice/createInvoice/{invoice_type}","invoiceController@create")->name("invoice.createInvoice");
//    Route::post("/invoice/storeInvoice/{invoice_type}","invoiceController@store")->name("invoice.storeInvoice");
//    Route::get("/invoice/viewInvoices/{invoice_type}","invoiceController@index")->name("invoice.viewInvoices");
//    Route::get("/invoice/showInvoice/{invoice_id}",[invoiceController::class,"show"])->name("invoice.showInvoice");
//    Route::post("/invoice/editInvoice/{invoice_id}","invoiceController@edit")->name("invoice.editInvoice");
//    Route::put("/invoice/updateInvoice/{invoice_type}/{invoice_id}","invoiceController@update")->name("invoice.updateInvoice");
//    Route::delete("/invoice/deleteInvoice/{invoice_id}","invoiceController@destroy")->name("invoice.deleteInvoice");
//    Route::delete("/invoice/softDeleteInvoice/{invoice_id}","invoiceController@softDelete")->name("invoice.softDeleteInvoice");
//    Route::get("/invoice/restoreInvoice/{invoice_id}","invoiceController@restore")->name("invoice.restoreInvoice");
//    Route::get("/invoice/viewRecyclebin","invoiceController@viewRecyclebin")->name("invoice.viewRecyclebin");
//    Route::get("/invoice/searchInvoice",[invoiceController::class,'search'])->name("invoice.searchInvoice");
//    Route::get("/invoice/showSearchInvoice/{invoice_type}",[invoiceController::class,"showSearchInvoice"])->name("invoice.showSearchInvoice");
//
//
//
//
//
//    Route::get("/invoice/createCashInvoice","cashController@create")->name("invoice.createCashInvoice");
//    Route::post("/invoice/storeCashInvoice","cashController@store")->name("invoice.storeCashInvoice");
//    Route::get("/invoice/viewCashInvoices","cashController@index")->name("invoice.viewCashInvoices");
//    Route::get("/invoice/showCashInvoice/{invoice_id}","cashController@show")->name("invoice.showCashInvoice");
//    Route::post("/invoice/editCashInvoice/{invoice_id}","cashController@edit")->name("invoice.editCashInvoice");
//    Route::put("/invoice/updateCashInvoice/{invoice_id}","cashController@update")->name("invoice.updateCashInvoice");
//    Route::delete("/invoice/deleteCashInvoice/{invoice_id}","cashController@destroy")->name("invoice.deleteCashInvoice");
//    Route::delete("/invoice/softDeleteCashInvoice/{invoice_id}","cashController@softDelete")->name("invoice.softDeleteCashInvoice");
//    Route::get("/invoice/restoreCashInvoice/{invoice_id}","cashController@restore")->name("invoice.restoreCashInvoice");
//    Route::get("/invoice/viewCashRecyclebin","cashController@viewRecyclebin")->name("invoice.viewCashRecyclebin");
//    Route::post("/invoice/searchCashInvoice","cashController@search")->name("invoice.searchCashInvoice");
//    Route::get("/invoice/showSearchCashInvoice","cashController@showSearchInvoice")->name("invoice.showSearchCashInvoice");
//
//
//
//    Route::get("/invoice/createProductMovementInvoice","productMovementController@create")->name("invoice.createProductMovementInvoice");
//    Route::post("/invoice/storeProductMovementInvoice","productMovementController@store")->name("invoice.storeProductMovementInvoice");
//    Route::get("/invoice/viewProductMovementInvoices","productMovementController@index")->name("invoice.viewProductMovementInvoices");
//    Route::get("/invoice/showProductMovementInvoice/{invoice_id}","productMovementController@show")->name("invoice.showProductMovementInvoice");
//    Route::post("/invoice/editProductMovementInvoice/{invoice_id}","productMovementController@edit")->name("invoice.editProductMovementInvoice");
//    Route::put("/invoice/updateProductMovementInvoice/{invoice_id}","productMovementController@update")->name("invoice.updateProductMovementInvoice");
//    Route::delete("/invoice/deleteProductMovementInvoice/{invoice_id}","productMovementController@destroy")->name("invoice.deleteProductMovementInvoice");
//    Route::delete("/invoice/softDeleteProductMovementInvoice/{invoice_id}","productMovementController@softDelete")->name("invoice.softDeleteProductMovementInvoice");
//    Route::get("/invoice/restoreProductMovementInvoice/{invoice_id}","productMovementController@restore")->name("invoice.restoreProductMovementInvoice");
//    Route::get("/invoice/viewProductMovementRecyclebin","productMovementController@viewRecyclebin")->name("invoice.viewProductMovementRecyclebin");
//    Route::post("/invoice/searchProductMovementInvoice","productMovementController@search")->name("invoice.searchProductMovementInvoice");
//    Route::get("/invoice/showSearchProductMovementInvoice","productMovementController@showSearchInvoice")->name("invoice.showSearchProductMovementInvoice");
//
//});
