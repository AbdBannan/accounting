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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
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
     * @return \Illuminate\Http\RedirectResponse
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
//            session()->flash("success",__("messages.created_successfully",["attribute"=>__("global.category")]));
//        }else{
//            session()->flash("success",__("messages.not_created_successfully",["attribute"=>__("global.category")]));
//        }

        globalFunctions::flashMessage("create",$result,"category");
        globalFunctions::registerUserActivityLog("added","category",$result->id);
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
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
     * @return \Illuminate\Http\RedirectResponse
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
//            session()->flash("success",__("messages.updated_successfully",["attribute"=>"Category"]));
            $result = $category->save();
        }
//        else{
//            session()->flash("success",__("messages.nothing_to_be_updated"));
//        }
        globalFunctions::flashMessage("update",$result,"category");
        globalFunctions::registerUserActivityLog("updated","category",$category->id);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request,$category_id)
    {
        if ($category_id > 0) {
            $result = Category::withTrashed()->find($category_id)->forceDelete();
            globalFunctions::registerUserActivityLog("deleted","category",$category_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = Category::withTrashed()->whereIn("id",$ids)->forceDelete();
            foreach ($ids as $id){
                globalFunctions::registerUserActivityLog("deleted","category",$id);
            }
        } else {
            $result = null;
        }
        globalFunctions::flashMessage("delete",$result,"category");
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function softDelete(Request $request,$category_id)
    {
        if ($category_id > 0) {
            $result = Category::find($category_id)->delete();
            globalFunctions::registerUserActivityLog("recycled","category",$category_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = Category::whereIn("id",$ids)->delete();
            foreach ($ids as $id){
                globalFunctions::registerUserActivityLog("recycled","category",$id);
            }
        } else {
            $result = null;
        }
        globalFunctions::flashMessage("softDelete",$result,"category");
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request,$category_id)
    {
        if ($category_id > 0) {
            $result = Request::onlyTrashed()->find($category_id)->restore();
            globalFunctions::registerUserActivityLog("restored","category",$category_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = Request::onlyTrashed()->whereIn("id",$ids)->restore();
            foreach ($ids as $id){
                globalFunctions::registerUserActivityLog("restored","category",$id);
            }
        } else {
            $result = null;
        }
        globalFunctions::flashMessage("restore",$result,"category");
        return back();
    }
}
