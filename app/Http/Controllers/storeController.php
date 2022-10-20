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
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
//            session()->flash("success",__("messages.created_successfully",["attribute"=>__("global.store",[],session("lang"))],session("lang")));
//        }else{
//            session()->flash("success",__("messages.not_created_successfully",["attribute"=>__("global.store",[],session("lang"))],session("lang")));
//        }
        globalFunctions::flashMessage("create",$result,"store");
        globalFunctions::registerUserActivityLog("added","store",$result->id);

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
//            session()->flash("success",__("messages.updated_successfully",["attribute"=>__("global.store",[],session("lang"))],session("lang")));
            $result = $store->save();
        }
//        else{
//            session()->flash("success",__("messages.nothing_to_be_updated",[],session("lang")));
//        }
        globalFunctions::flashMessage("update",$result,"store");
        globalFunctions::registerUserActivityLog("updated","store",$store->id);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($store_id)
    {
        $result = Store::onlyTrashed()->where("id",$store_id)->forceDelete();

//        if ($result!=null) {
//            session()->flash("success",__("messages.deleted_successfully",["attribute"=>__("global.store",[],session("lang"))],session("lang")));
//        }else{
//            session()->flash("success",__("messages.not_deleted_successfully",["attribute"=>__("global.store",[],session("lang"))],session("lang")));
//        }
        globalFunctions::flashMessage("delete",$result,"store");
        globalFunctions::registerUserActivityLog("deleted","store",$store_id);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function softDelete(Store $store)
    {
        $result = $store->delete();

//        if ($result!=null) {
//            session()->flash("success",__("messages.recycled_successfully",["attribute"=>__("global.store",[],session("lang"))],session("lang")));
//        }else{
//            session()->flash("success",__("messages.not_recycled_successfully",["attribute"=>__("global.store",[],session("lang"))],session("lang")));
//        }
        globalFunctions::flashMessage("softDelete",$result,"store");
        globalFunctions::registerUserActivityLog("recycled","store",$store->id);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($store_id)
    {
        $result = Store::onlyTrashed()->where("id",$store_id)->restore();

//        if ($result!=null) {
//            session()->flash("success",__("messages.restored_successfully",["attribute"=>__("global.store",[],session("lang"))],session("lang")));
//        }else{
//            session()->flash("success",__("messages.restored_successfully",["attribute"=>__("global.store",[],session("lang"))],session("lang")));
//        }
        globalFunctions::flashMessage("restore",$result,"store");
        globalFunctions::registerUserActivityLog("restored","store",$store_id);

        return back();
    }
}
