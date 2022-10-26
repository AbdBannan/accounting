<?php

/*
|--------------------------------------------------------------------------
| Backpack\BackupManager Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are
| handled by the Backpack\BackupManager package.
|
*/
use Illuminate\Support\Facades\Route;


Route::group([
//    'namespace'  => 'Backpack\BackupManager\app\Http\Controllers',
//    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
], function () {
    Route::get('backup/backup', 'backupController@index')->name('backup.index');
    Route::get('backup/create', 'backupController@create')->name('backup.store');
    Route::get('backup/download/{file_name?}', 'backupController@download')->name('backup.download');
    Route::get('backup/delete/{file_name?}', 'backupController@delete')->where('file_name', '(.*)')->name('backup.delete');
});
