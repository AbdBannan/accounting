<?php
use Mailgun\Mailgun;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth"])->group(function (){
    Route::get("/discover/showDiscoverDashboard","discoverActionsController@showDiscoverDashboard")->name("discover.showDiscoverDashboard");

    Route::get("/discover/chooseListGlobalDiscover","discoverActionsController@chooseListGlobalDiscover")->name("discover.chooseListGlobalDiscover");
    Route::any("/discover/globalDiscoverUntilNow","discoverActionsController@globalDiscoverUntilNow")->name("discover.globalDiscoverUntilNow");
    Route::post("/discover/globalDiscoverBetweenTowDates","discoverActionsController@globalDiscoverBetweenTowDates")->name("discover.globalDiscoverBetweenTowDates");
    Route::post("/discover/globalDiscoverAfterLastCheckedPoint","discoverActionsController@globalDiscoverAfterLastCheckedPoint")->name("discover.globalDiscoverAfterLastCheckedPoint");
    Route::post("/discover/globalDiscoverUntilLastBalance","discoverActionsController@globalDiscoverUntilLastBalance")->name("discover.globalDiscoverUntilLastBalance");
    Route::post("/discover/globalDiscoverByAccount","discoverActionsController@globalDiscoverByAccount")->name("discover.globalDiscoverByAccount");

    Route::get("/discover/chooseListCashDiscover","discoverActionsController@chooseListCashDiscover")->name("discover.chooseListCashDiscover");
    Route::post("/discover/cashDiscoverUntilNow","discoverActionsController@cashDiscoverUntilNow")->name("discover.cashDiscoverUntilNow");
    Route::post("/discover/cashDiscoverBetweenTowDates","discoverActionsController@cashDiscoverBetweenTowDates")->name("discover.cashDiscoverBetweenTowDates");
    Route::post("/discover/cashDiscoverAfterLastCheckedPoint","discoverActionsController@cashDiscoverAfterLastCheckedPoint")->name("discover.cashDiscoverAfterLastCheckedPoint");

    Route::get("/discover/chooseListProductDiscover","discoverActionsController@chooseListProductDiscover" . "")->name("discover.chooseListProductDiscover");
    Route::post("/discover/productDiscoverUntilNow","discoverActionsController@productDiscoverUntilNow")->name("discover.productDiscoverUntilNow");
    Route::post("/discover/productDiscoverBetweenTowDates","discoverActionsController@productDiscoverBetweenTowDates")->name("discover.productDiscoverBetweenTowDates");
    Route::post("/discover/productDiscoverWithAccount","discoverActionsController@productDiscoverWithAccount")->name("discover.productDiscoverWithAccount");
    Route::post("/discover/productDiscoverUntilLastBalance","discoverActionsController@productDiscoverUntilLastBalance")->name("discover.productDiscoverUntilLastBalance");
    Route::post("/discover/productDiscoverByStore","discoverActionsController@productDiscoverByStore")->name("discover.productDiscoverByStore");

    Route::post("/discover/makeCheckPoint","discoverActionsController@makeCheckPoint")->name("discover.makeCheckPoint");

    Route::get("/discover/allAccountsDiscover","discoverActionsController@allAccountsDiscover")->name("discover.allAccountsDiscover");
    Route::get("/discover/allProductsDiscover","discoverActionsController@allProductsDiscover")->name("discover.allProductsDiscover");
    Route::get("/discover/dailyDiscover","discoverActionsController@dailyDiscover")->name("discover.dailyDiscover");


    Route::post("/discover/globalDiscoverLastYear","discoverActionsController@globalDiscoverLastYear")->name("discover.globalDiscoverLastYear");
    Route::post("/discover/cashDiscoverLastYear","discoverActionsController@cashDiscoverLastYear")->name("discover.cashDiscoverLastYear");

    Route::post("/discover/cashDiscoverLastYear","discoverActionsController@cashDiscoverLastYear")->name("discover.cashDiscoverLastYear");

});
