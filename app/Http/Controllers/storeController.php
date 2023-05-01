<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use App\functions\globalFunctions;

class storeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        globalFunctions::registerUserActivityLog("seen_all","stores",null);
        return view("stores.viewStores")->with("stores",Store::all());
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
        $store = $request->all();
        $this->validate($request,
            [
                "name"=>["required",'unique:stores'],
                "id"=>"required"
            ]);

        $result = Store::create($store);
//        if ($result!=null) {
//            session()->flash("success",__("messages.created_successfully",["attribute"=>__("global.store")]));
//        }else{
//            session()->flash("success",__("messages.not_created_successfully",["attribute"=>__("global.store")]));
//        }
        globalFunctions::flashMessage("create",$result,"store");
        globalFunctions::registerUserActivityLog("added","store",$result->id);

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        globalFunctions::registerUserActivityLog("seen","store",$store->id);
        return view("stores.showStore")->with("store",$store);
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
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Store $store)
    {
        $this->validate($request,["name"=>"required"]);
        if ($store->id != $request["id"]){
            $this->validate($request,
                [
                    "id"=>["required",'unique:stores'],
                ]);
        }

        $store->id=$request["id"];
        $store->name=$request["name"];
        if(isset($request["location"])){
            $store->location=$request["location"];
        }
        $result = null;
        if ($store->isDirty(["name","location","id"])) {
//            session()->flash("success",__("messages.updated_successfully",["attribute"=>__("global.store")]));
            $result = $store->save();
        }
//        else{
//            session()->flash("success",__("messages.nothing_to_be_updated"));
//        }
        globalFunctions::flashMessage("update",$result,"store");
        globalFunctions::registerUserActivityLog("updated","store",$store->id);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request,$store_id)
    {
        if ($store_id > 0) {
            $result = Store::withTrashed()->find($store_id)->forceDelete();
            globalFunctions::registerUserActivityLog("deleted","store",$store_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = Store::withTrashed()->whereIn("id",$ids)->forceDelete();
            foreach ($ids as $id){
                globalFunctions::registerUserActivityLog("deleted","store",$id);
            }
        } else {
            $result = null;
        }
        globalFunctions::flashMessage("delete",$result,"store");

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function viewRecyclebin()
    {
        globalFunctions::registerUserActivityLog("seen","stores_recyclebin",null);
        return view("stores.recyclebin")->with("deletedStores",Store::onlyTrashed()->get());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Store $store
     * @return \Illuminate\Http\RedirectResponse
     */
    public function softDelete(Request $request,$store_id)
    {
        if ($store_id > 0) {
            $result = Store::find($store_id)->delete();
            globalFunctions::registerUserActivityLog("recycled","store",$store_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = Store::whereIn("id",$ids)->delete();
            foreach ($ids as $id){
                globalFunctions::registerUserActivityLog("recycled","store",$id);
            }
        } else {
            $result = null;
        }
        globalFunctions::flashMessage("softDelete",$result,"store");

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request,$store_id)
    {
        if ($store_id > 0) {
            $result = Store::onlyTrashed()->find($store_id)->restore();
            globalFunctions::registerUserActivityLog("restored","store",$store_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = Store::onlyTrashed()->whereIn("id",$ids)->restore();
            foreach ($ids as $id){
                globalFunctions::registerUserActivityLog("restored","store",$id);
            }
        } else {
            $result = null;
        }
        globalFunctions::flashMessage("restore",$result,"store");

        return back();
    }
}
