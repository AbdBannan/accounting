<?php
use Mailgun\Mailgun;
use Illuminate\Support\Facades\Mail;


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
Route::middleware(["auth","saveCurrentRequest"])->group(function (){
    Route::resource("/posts","postController")->name("index","posts");
    Route::get("/sendEmail",function(){

        # Include the Autoloader (see "Libraries" for install instructions)
        // require 'vendor/autoload.php';

        # Instantiate the client.
        // $mgClient = new Mailgun('aa751a425aff9dc8e655e8e14f72968b-77985560-6b583e91');
        // $domain = "sandbox5573b3821d7c4c109f5de73b0c9d626b.mailgun.org";

        // # Make the call to the client.
        // $result = $mgClient->sendMessage("$domain",
        // 	array('from'    => 'Mailgun Sandbox <postmaster@sandbox5573b3821d7c4c109f5de73b0c9d626b.mailgun.org>',
        // 		  'to'      => 'Abdulmoty <bannan51a@gmail.com>',
        // 		  'subject' => 'Hello Abdulmoty',
        // 		  'text'    => 'Congratulations Abdulmoty, you just sent an email with Mailgun!  You are truly awesome! '));

        // You can see a record of this email in your logs: https://app.mailgun.com/app/logs.

        // You can send up to 300 emails/day from this sandbox server.
        // Next, you should add your own domain so you can send 10000 emails/month for free.


        $data = ["title"=>"test mail","content"=>"this is a test message."];
        Mail::send("email.test",$data,function($request){
            $request->to("bannan51a@gmail.com","Ahmad bannan")->subject("friend");
            // $request->to("Mahmoudmnb2000.2004@gmail.com","Mahmoud bannan")->subject("friend");
        });
    });
});
