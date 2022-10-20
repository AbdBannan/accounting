<?php
use Mailgun\Mailgun;
use Illuminate\Support\Facades\Route;
//Route::middleware("can:update,comment")->group(function (){
//    Route::delete("comment/deleteComment/{comment}","commentController@destroy")->name("comment.deleteComment");
////    Route::delete("comment/deleteComment/{comment}",["middleware"=>"can:delete,comment","commentController@destroy"])->name("comment.deleteComment");
//});
Route::middleware(["auth","saveCurrentRequest"])->group(function (){
    Route::get("comment/viewComments/{post}","commentController@index")->name("comment.viewComments");
    Route::post("comment/storeComment/{post}","commentController@store")->name("comment.storeComment");
    Route::delete("comment/deleteComment/{comment}","commentController@destroy")->name("comment.deleteComment");
    Route::put("comment/updateComment/{comment}","commentController@update")->name("comment.updateComment");
//    Route::get("comment/showRolePermission/{role}","roleController@show")->name("role.showRolePermission");
//    Route::get("comment/{role}/attachPermission/{permissionId}","roleController@attachPermission")->name("role.attachPermission");
//    Route::get("comment/{role}/detachPermission/{permissionId}","roleController@detachPermission")->name("role.detachPermission");


    Route::get("reply/viewReplies","replyController@index")->name("reply.viewReplies");
    Route::post("reply/storeReply/{reply}","replyController@store")->name("reply.storeReply");
    Route::delete("reply/deleteReply/{reply}","replyController@destroy")->name("reply.deleteReply");
    Route::put("reply/updateReply/{reply}","replyController@update")->name("reply.updateReply");
});
