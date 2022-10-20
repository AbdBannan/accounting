<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class commentController extends Controller
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
    public function store(Request $request,Post $post)
    {
        $this->validate($request,["comment_body"=>"required|min:20"]);
        $comment = Comment::create($request->all());
        auth()->user()->comments()->save($comment);

        $result = $post->comments()->save($comment);
        if ($result!=null) {
            session()->flash("success","comment has been added successfully");
        }else{
            session()->flash("error","comment has not been added successfully");
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
    public function update(Request $request, Comment $comment)
    {
        $this->validate($request,["comment_body"=>["required","min:20"]]);
        $comment->comment_body=$request->comment_body;
        if ($comment->isDirty("comment_body")){
            $comment->save();
            session()->flash("success","comment has been updated successfully");
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
    public function destroy(Comment $comment)
    {
        $result = $comment->delete();

        if ($result!=null) {
            session()->flash("success","comment has been deleted successfully");
        }else{
            session()->flash("error","comment has not been deleted successfully");
        }
        return back();
    }
}
