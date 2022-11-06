<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function PHPUnit\Framework\fileExists;
use App\functions\globalFunctions;
class productController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        globalFunctions::registerUserActivityLog("seen_all","products",null);
        return view("products.viewProducts")->with("products",Product::all());
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
                "id"=>['required','unique:products'],
                "name"=>"required",
                "account_type"=>"required",
                'product_image' => ['image','mimes:jpg,png,jpeg,gif,svg']
            ]);
//        if ($request["account_type"] == 0){
//            $this->validate($request,
//                [
//                    "reference"=>"required"
//                ]);
//        }
//        dd($request->all());
        if ($request["reference"]!=null && $request["reference"]!=0){
            $ref_account = Product::find($request["reference"]);
            if ($ref_account == null || $ref_account->account_type == 0){// means reference is invalid
                session()->flash("error",__("messages.invalid_reference",[],session("lang")));
                return back();
            }
        }
        if ($file = $request->file("product_image")) {
            $fileName = Carbon::now()->format("d_m_Y_h_i_s") . "_" . $request["id"] . "_" . $request['name'];
            $request["image"] =  $fileName;
            $file->move("images/productsImages", $fileName);
        }
        else{
            $request["image"] =  "systemImages/default_product_img.png";
        }

        $result = Product::create($request->except("product_image"));
//        if ($result!=null) {
//            session()->flash("success",__("messages.created_successfully",["attribute"=>__("global.product",[],session("lang"))],session("lang")));
//        }else{
//            session()->flash("success",__("messages.not_created_successfully",["attribute"=>__("global.product",[],session("lang"))],session("lang")));
//        }
        globalFunctions::flashMessage("create",$result,"product");
        globalFunctions::registerUserActivityLog("added","product",$result->id);

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        globalFunctions::registerUserActivityLog("seen","product",$product->id);
        return view("products.showProduct")->with("product",$product);
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
    public function update(Request $request, Product $product)
    {
        $this->validate($request,
            [
                "name"=>"required",
                "account_type"=>"required",
                'product_image' => ['image','mimes:jpg,png,jpeg,gif,svg']
            ]);
        if ($request["id"] != $product->id){
            $this->validate($request,
                [
                    "id"=>['required','unique:accounts'],
                ]);
        }

//        if ($request["account_type"] == 0){
//            $this->validate($request,
//                [
//                    "reference"=>"required"
//                ]);
//        }
//        dd($request->all());
        if ($request["reference"]!=null && $request["reference"]!=0){
            $ref_account = Product::find($request["reference"]);
            if ($ref_account == null || $ref_account->account_type == 0){// means reference is invalid
                session()->flash("error",__("messages.invalid_reference",[],session("lang")));
                return back();
            }
        }
        $oldProductImage = $product->image;
        if ($file = $request->file("product_image")){
            if ($oldProductImage != "images/systemImages/default_product_img.png" and file_exists(public_path($oldProductImage))){
                unlink(public_path($oldProductImage));
            }
            $fileName =  Carbon::now()->format("d_m_Y_h_i_s") . "_" . $request["id"] . "_" . $request['name'];
            $product["image"] = $fileName;
            $file->move("images/productsImages",$fileName);
        }

        $oldProduct = $request->all();
        $product->id = $oldProduct["id"];
        $product->name = $oldProduct["name"];
        $product->reference = $oldProduct["reference"];
        $product->account_type = $oldProduct["account_type"];
        $product->store_id = $oldProduct["store_id"];

        $result = null;
        if ($product->isDirty(["name","category_id","store_id","reference","image"])) {
//            session()->flash("success",__("messages.updated_successfully",["attribute"=>__("global.product",[],session("lang"))],session("lang")));
            $result = $product->save();
        }
//        else{
//            session()->flash("success",__("messages.nothing_to_be_updated",[],session("lang")));
//        }
        globalFunctions::flashMessage("update",$result,"product");
        globalFunctions::registerUserActivityLog("updated","product",$product->id);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($product_id)
    {
        $image = Product::onlyTrashed()->where("id",$product_id)->first()->image;
        $result = Product::onlyTrashed()->where("id",$product_id)->forceDelete();

        if ($result!=null) {
            if ($image != "images/systemImages/default_product_img.png"  and file_exists(public_path($image))) {
                unlink(public_path($image));
            }
//            session()->flash("success",__("messages.deleted_successfully",["attribute"=>__("global.product",[],session("lang"))],session("lang")));
        }
//        else{
//            session()->flash("success",__("messages.not_deleted_successfully",["attribute"=>__("global.product",[],session("lang"))],session("lang")));
//        }

        globalFunctions::flashMessage("delete",$result,"product");
        globalFunctions::registerUserActivityLog("deleted","product",$product_id);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewRecyclebin()
    {
        globalFunctions::registerUserActivityLog("seen","products_recyclebin",null);
        return view("products.recyclebin")->with("deletedProducts",Product::onlyTrashed()->get());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Account $account
     * @return \Illuminate\Http\Response
     */
    public function softDelete(Product $product)
    {
        $result = $product->delete();

//        if ($result!=null) {
//            session()->flash("success",__("messages.recycled_successfully",["attribute"=>__("global.product",[],session("lang"))],session("lang")));
//        }else{
//            session()->flash("success",__("messages.not_recycled_successfully",["attribute"=>__("global.product",[],session("lang"))],session("lang")));
//        }
        globalFunctions::flashMessage("softDelete",$result,"product");
        globalFunctions::registerUserActivityLog("recycled","product",$product->id);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($product_id)
    {
        $result = Product::onlyTrashed()->where("id",$product_id)->restore();

//        if ($result!=null) {
//            session()->flash("success",__("messages.restored_successfully",["attribute"=>__("global.product",[],session("lang"))],session("lang")));
//        }else{
//            session()->flash("success",__("messages.restored_successfully",["attribute"=>__("global.product",[],session("lang"))],session("lang")));
//        }
        globalFunctions::flashMessage("restore",$result,"product");
        globalFunctions::registerUserActivityLog("restored","product",$product_id);

        return back();
    }
}
