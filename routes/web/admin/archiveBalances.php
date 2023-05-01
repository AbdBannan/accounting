<?php
use Illuminate\Support\Facades\Route;


Route::get("/archive/viewArchiveBalances","archiveBalancesController@viewArchiveBalances")->name("archive.viewArchiveBalances");
Route::post("/archive/archiveBalances","archiveBalancesController@archiveBalances")->name("archive.archiveBalances");

