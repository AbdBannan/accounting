<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\functions\globalFunctions;
class categoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        globalFunctions::registerUserActivityLog("seen_all","categories",null);
        return view("categories.viewCategories")->with("categories",Category::all());
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
    public function store(Request $request)
    {
        $this->validate($request,
            [
                "name"=>["required",'unique:stores'],
                "id"=>"required"
            ]);
        $result = Category::create($request->all());
//        if ($result!=null) {
//            session()->flash("success",__("messages.created_successfully",["attribute"=>__("global.category",[],session("lang"))],session("lang")));
//        }else{
//            session()->flash("success",__("messages.not_created_successfully",["attribute"=>__("global.category",[],session("lang"))],session("lang")));
//        }

        globalFunctions::flashMessage("create",$result,"category");
        globalFunctions::registerUserActivityLog("added","category",$result->id);
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return view("categories.showCategory")->with("category",$category);
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
    public function update(Request $request, Category $category)
    {
        $this->validate($request,["name"=>"required"]);
        if ($category->id != $request["id"]){
            $this->validate($request,
                [
                    "id"=>["required",'unique:stores'],
                ]);
        }
        $category->id=$request["id"];
        $category->name=$request["name"];

        $result = null;
        if ($category->isDirty(["name","id"])) {
//            session()->flash("success",__("messages.updated_successfully",["attribute"=>"Category"],session("lang")));
            $result = $category->save();
        }
//        else{
//            session()->flash("success",__("messages.nothing_to_be_updated",[],session("lang")));
//        }
        globalFunctions::flashMessage("update",$result,"category");
        globalFunctions::registerUserActivityLog("updated","category",$category->id);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($category_id)
    {
        $result = Category::onlyTrashed()->where("id",$category_id)->forceDelete();

//        if ($result!=null) {
//            session()->flash("success",__("messages.deleted_successfully",["attribute"=>"Category"],session("lang")));
//        }else{
//            session()->flash("success",__("messages.not_deleted_successfully",["attribute"=>"Category"],session("lang")));
//        }
        globalFunctions::flashMessage("delete",$result,"category");
        globalFunctions::registerUserActivityLog("deleted","category",$category_id);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewRecyclebin()
    {
        globalFunctions::registerUserActivityLog("seen","categories_recyclebin",null);
        return view("categories.recyclebin")->with("deletedCategories",Category::onlyTrashed()->get());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $account
     * @return \Illuminate\Http\Response
     */
    public function softDelete(Category $category)
    {
        $result = $category->delete();

//        if ($result!=null) {
//            session()->flash("success",__("messages.recycled_successfully",["attribute"=>"Category"],session("lang")));
//        }else{
//            session()->flash("success",__("messages.not_recycled_successfully",["attribute"=>"Category"],session("lang")));
//        }
        globalFunctions::flashMessage("softDelete",$result,"category");
        globalFunctions::registerUserActivityLog("recycled","category",$category->id);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($category_id)
    {
        $result = Category::onlyTrashed()->where("id",$category_id)->restore();

//        if ($result!=null) {
//            session()->flash("success",__("messages.restored_successfully",["attribute"=>"Category"],session("lang")));
//        }else{
//            session()->flash("success",__("messages.restored_successfully",["attribute"=>"Category"],session("lang")));
//        }
        globalFunctions::flashMessage("restore",$result,"category");
        globalFunctions::registerUserActivityLog("restored","category",$category_id);
        return back();
    }
}
