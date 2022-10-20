<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Post;
use DB;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;


class postController extends Controller
{


    public function __construct()
    {
//        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //------------session -----------------
        session(["user"=>"Abdulmoty"]);// this is using a global function,
        $session = session("user");// getting session using global function
        // $session = $request->session()->all();// get all sessions
        // $request->session()->put(["user"=>"Abdulmoty"]); anather way or we can pass (Request $request) as a parameter and use
        // $session = session()->get("user");

        // $request->session()->forget("user");//to delete session
        // $request->session()->flush();//to delete all session

        // $request->session()->flash("message","post has been added successfully");// this flash session will stay for few period and deleted
        // $request->session()->get("message");
        // $request->session()->reFlash("message"=>"post has been added successfully");// this reflash will keep flash sessions more
        // $request->session()->keep("message");// this keep will keep a spicified flash sessions

        //---------------------

        // $posts = DB::select("select * from posts");
        $posts = Post::all();
        // $posts = Post::orderBy("name","desc")->take(1)->get();
        // $posts = Post::orderBy("name","desc")->get();
        // $posts = Post::where("name","Abdulmoty")->get();
        // $posts = Post::orderBy("name","desc")->paginate(1);

        return view("posts.index")->with("posts",$posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        $this->authorize("create");
        return view("posts.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,["name"=>"required|min:5","file"=>"required|file:jpg"]);
        //------ this is a way of saving data into db ----------
        // $post = new Post;
        // $post->name = $request->input("name");
        // // $post->image = $request->file("image"); gives the temorary fileName in the memory
        // $post->image = $request->file("image")->getClientOriginalName();
        // $post->size = $request->file("image")->getClientSize();
        // $post->save();
        //----------------
        $input = $request->all();
        //----------- this is anather way -------------
        if ($file = $request->file("file")){
            $name =  $file->getClientOriginalName();
            $size = $file->getSize();
            $file->move("images", $name);
            $input["image"] = $name;
            $input["size"] = $size;
            $input["user_id"] = auth()->user()->id;
            $result = Post::create($input);
            if ($result!=null) {
                session()->flash("success","post has been added successfully");
            }else{
                session()->flash("error","post has not been added successfully");
            }
        }
        //---------------------------------------------

        return redirect("/posts")->with("message","success");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
//        $this->authorize("view",$post);
        return view("/posts.show")->with("post",$post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
//        $this->authorize("update",Post::find($id));
        return view("posts.edit")->with("post",Post::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
//dd($request->all());
        $this->validate($request,["name"=>"required|min:5","file"=>"required"]);
        $post = $request->all();
        if ($file = $request->file("file")){
            $oldProfileImage = Post::find($id)->image;
            unlink(public_path($oldProfileImage));
            $name = $file->getClientOriginalName();
            $size = $file->getSize();
            $file->move("images",$name);
    //        if (request("file")) {// anather way of file saving
    //            request("file")->store("ima");
    //        }
            $post["image"]=$name;
            $post["size"]=$size;
            $result = Post::find($id)->update($post);
            $this->authorize("update",Post::find($id));

            if ($result!=null) {
                session()->flash("success","post has been updated successfully");
            }else{
                session()->flash("error","post has not been updated successfully");
            }
        }
        return back();
//        return redirect("/posts/" . $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        unlink(public_path($post->image));
        $result = $post->delete();

        if ($result!=null) {
            session()->flash("success","post has been deleted successfully");
        }else{
            session()->flash("error","post has not been deleted successfully");
        }
        return redirect("/posts");
    }
}
