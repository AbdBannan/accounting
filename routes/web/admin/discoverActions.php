<?php
use Illuminate\Support\Facades\Route;

Route::get("/discover/showDiscoverDashboard","discoverActionsController@showDiscoverDashboard")->name("discover.showDiscoverDashboard");

Route::get("/discover/chooseListGlobalDiscover","discoverActionsController@chooseListGlobalDiscover")->name("discover.chooseListGlobalDiscover");
Route::get("/discover/globalDiscoverUntilNow","discoverActionsController@globalDiscoverUntilNow")->name("discover.globalDiscoverUntilNow");
Route::get("/discover/globalDiscoverBetweenTowDates","discoverActionsController@globalDiscoverBetweenTowDates")->name("discover.globalDiscoverBetweenTowDates");
Route::get("/discover/globalDiscoverAfterLastCheckedPoint","discoverActionsController@globalDiscoverAfterLastCheckedPoint")->name("discover.globalDiscoverAfterLastCheckedPoint");
Route::get("/discover/globalDiscoverUntilLastBalance","discoverActionsController@globalDiscoverUntilLastBalance")->name("discover.globalDiscoverUntilLastBalance");
Route::get("/discover/globalDiscoverByAccount","discoverActionsController@globalDiscoverByAccount")->name("discover.globalDiscoverByAccount");

Route::get("/discover/chooseListCashDiscover","discoverActionsController@chooseListCashDiscover")->name("discover.chooseListCashDiscover");
Route::get("/discover/cashDiscoverUntilNow","discoverActionsController@cashDiscoverUntilNow")->name("discover.cashDiscoverUntilNow");
Route::get("/discover/cashDiscoverBetweenTowDates","discoverActionsController@cashDiscoverBetweenTowDates")->name("discover.cashDiscoverBetweenTowDates");
Route::get("/discover/cashDiscoverAfterLastCheckedPoint","discoverActionsController@cashDiscoverAfterLastCheckedPoint")->name("discover.cashDiscoverAfterLastCheckedPoint");

Route::get("/discover/chooseListProductDiscover","discoverActionsController@chooseListProductDiscover" . "")->name("discover.chooseListProductDiscover");
Route::get("/discover/productDiscoverUntilNow","discoverActionsController@productDiscoverUntilNow")->name("discover.productDiscoverUntilNow");
Route::get("/discover/productDiscoverBetweenTowDates","discoverActionsController@productDiscoverBetweenTowDates")->name("discover.productDiscoverBetweenTowDates");
Route::get("/discover/productDiscoverWithAccount","discoverActionsController@productDiscoverWithAccount")->name("discover.productDiscoverWithAccount");
Route::get("/discover/productDiscoverUntilLastBalance","discoverActionsController@productDiscoverUntilLastBalance")->name("discover.productDiscoverUntilLastBalance");
Route::get("/discover/productDiscoverByStore","discoverActionsController@productDiscoverByStore")->name("discover.productDiscoverByStore");

Route::post("/discover/makeCheckPoint","discoverActionsController@makeCheckPoint")->name("discover.makeCheckPoint");

Route::get("/discover/allAccountsDiscover","discoverActionsController@allAccountsDiscover")->name("discover.allAccountsDiscover");
Route::get("/discover/allProductsDiscover","discoverActionsController@allProductsDiscover")->name("discover.allProductsDiscover");
Route::get("/discover/dailyDiscover","discoverActionsController@dailyDiscover")->name("discover.dailyDiscover");


Route::get("/discover/globalDiscoverLastYear","discoverActionsController@globalDiscoverLastYear")->name("discover.globalDiscoverLastYear");
Route::get("/discover/cashDiscoverLastYear","discoverActionsController@cashDiscoverLastYear")->name("discover.cashDiscoverLastYear");

Route::get("/discover/cashDiscoverLastYear","discoverActionsController@cashDiscoverLastYear")->name("discover.cashDiscoverLastYear");
