<?php
use Mailgun\Mailgun;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth","saveCurrentRequest"])->group(function (){

    Route::get("/archive/viewArchiveBalances","archiveBalancesController@viewArchiveBalances")->name("archive.viewArchiveBalances");
    Route::post("/archive/archiveBalances","archiveBalancesController@archiveBalances")->name("archive.archiveBalances");

});
