<?php
use Illuminate\Support\Facades\Route;

Route::get("/notifications/viewNotifications",[\App\Http\Controllers\notificationController::class,"index"])->name("notifications.viewNotifications")->middleware(['saveRequestHistory']);
Route::post("/notifications/seenAllNotifications",[\App\Http\Controllers\notificationController::class,"seenAll"])->name("notifications.seenAllNotifications");
