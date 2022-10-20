<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Reply;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Null_;

class replyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $reply)
    {

        $this->validate($request,["reply_body"=>"required|min:20"]);
        $arr = explode("_",$reply);
        $owner = Null;
        if ($arr[0] == "comment"){
            $owner = Comment::find((int)$arr[1]);
        }
        elseif ($arr[0] == "reply"){
            $owner = Reply::find((int)$arr[1]);
        }
        $reply = Reply::create($request->all());
        auth()->user()->replies()->save($reply);
        $result = $owner->replies()->save($reply);
        if ($result!=null) {
            session()->flash("success","reply has been added successfully");
        }else{
            session()->flash("error","reply has not been added successfully");
        }
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Reply $reply)
    {
        $this->validate($request,["reply_body"=>["required","min:20"]]);
        $reply->reply_body=$request->reply_body;
        if ($reply->isDirty("reply_body")){
            $reply->save();
            session()->flash("success","reply has been updated successfully");
        }else{
            session()->flash("success","nothing to be updated");
        }
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply)
    {
        $result = $reply->delete();

        if ($result!=null) {
            session()->flash("success","reply has been deleted successfully");
        }else{
            session()->flash("error","reply has not been deleted successfully");
        }
        return back();
    }
}
