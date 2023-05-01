<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\fileExists;
use App\functions\globalFunctions;
class productController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request,
            [
                "id"=>['required','unique:products'],
                "name"=>"required",
                "account_type"=>"required",
                "is_raw"=>"required",
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
                session()->flash("error",__("messages.invalid_reference"));
                return back();
            }
        }
        if ($file = $request->file("product_image")) {
            $fileName = Carbon::now()->format("d_m_Y_h_i_s") . "_" . $request["id"] . "_" . $request['name'] . "." . $file->getClientOriginalExtension();
            $request["image"] =  $fileName;
            $file->move("images/productsImages", $fileName);
        }
        else{
            $request["image"] =  "default_product_img.png";
        }

        $result = Product::create($request->except("product_image"));

        globalFunctions::flashMessage("create",$result,"product");
        globalFunctions::registerUserActivityLog("added","product",$result->id);
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request,
            [
                "name"=>"required",
                "account_type"=>"required",
                "is_raw"=>"required",
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
                session()->flash("error",__("messages.invalid_reference"));
                return back();
            }
        }
        $oldProductImage = $product->image;
        if ($file = $request->file("product_image")){
            if ($oldProductImage != "images/systemImages/default_product_img.png" and file_exists(public_path($oldProductImage))){
                unlink(public_path($oldProductImage));
            }
            $fileName =  Carbon::now()->format("d_m_Y_h_i_s") . "_" . $request["id"] . "_" . $request['name'] . "." . $file->getClientOriginalExtension();
            $product["image"] = $fileName;
            $file->move("images/productsImages",$fileName);
        }

        $oldProduct = $request->all();
        $product->id = $oldProduct["id"];
        $product->name = $oldProduct["name"];
        $product->reference = $oldProduct["reference"];
        $product->account_type = $oldProduct["account_type"];
        $product->store_id = $oldProduct["store_id"];
        $product->is_raw = $oldProduct["is_raw"];

        $result = null;
        if ($product->isDirty(["name","category_id","store_id","reference","is_raw","image"])) {
            $result = $product->save();
        }
        globalFunctions::flashMessage("update",$result,"product");
        globalFunctions::registerUserActivityLog("updated","product",$product->id);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request,$product_id)
    {
        try {
            DB::beginTransaction();
            if ($product_id > 0) {
                $balance = Journal::where("product_id",$product_id)->selectRaw("sum(in_quantity) - sum(out_quantity) as balance")->first()->balance;
                // here whe check if the account balance is zero or not , so if it is zero delete it and update all invoices whose first_part_id = id and set first_part_id = 0 to allow adding new account with the same fo previous id
                if ($balance != 0 and $balance != null) {
                    globalFunctions::flashMessage("softDelete","account_is_not_zero","account");
                    DB::rollBack();
                    return back();
                }

                $image = Product::withTrashed()->find($product_id)->image;
                $result = Product::withTrashed()->find($product_id)->forceDelete();
                Journal::where("product_id",$product_id)->update(["product_id"=>0]);

                if ($result!=null) {
                    if ($image != "images/systemImages/default_product_img.png"  and file_exists(public_path($image))) {
                        unlink(public_path($image));
                    }
                }
                globalFunctions::registerUserActivityLog("deleted","product",$product_id);
            } else if (isset($request["multi_ids"])) {
                $ids = $request["multi_ids"];
                foreach ($ids as $id){
                    $balance = Journal::where("product_id",$id)->selectRaw("sum(in_quantity) - sum(out_quantity) as balance")->first()->balance;
                    if ($balance != 0 and $balance != null) {
                        DB::rollBack();
                        globalFunctions::flashMessage("softDelete", "account_is_not_zero", "account");
                        return back();
                    }
                    $image = Product::withTrashed()->find($id)->image;
                    $result = Product::withTrashed()->find($id)->forceDelete();
                    Journal::whereIn("product_id",$id)->update(["product_id"=>0]);

                    if ($result!=null) {
                        if ($image != "images/systemImages/default_product_img.png"  and file_exists(public_path($image))) {
                            unlink(public_path($image));
                        }
                    }
                    globalFunctions::registerUserActivityLog("deleted","product",$id);
                }
            } else {
                $result = null;
                DB::rollBack();
            }
            DB::commit();
        }
        catch (\PDOException $e){
            DB::rollBack();
            $result = null;
        }
        globalFunctions::flashMessage("delete",$result,"product");

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function softDelete(Request $request,$product_id)
    {
        try {
            DB::beginTransaction();
            if ($product_id > 0) {
                $balance = Journal::where("product_id",$product_id)->selectRaw("sum(in_quantity) - sum(out_quantity) as balance")->first()->balance;
                // here whe check if the account balance is zero or not , so if it is zero delete it and update all invoices whose first_part_id = id and set first_part_id = 0 to allow adding new account with the same fo previous id
                if ($balance != 0 and $balance != null) {
                    globalFunctions::flashMessage("softDelete","account_is_not_zero","account");
                    DB::rollBack();
                    return back();
                }

                $result = Product::find($product_id)->delete();
                globalFunctions::registerUserActivityLog("recycled","product",$product_id);
            } else if (isset($request["multi_ids"])) {
                $ids = $request["multi_ids"];
                $result = Product::whereIn("id",$ids)->delete();
                foreach ($ids as $id){
                    $balance = Journal::where("product_id",$id)->selectRaw("sum(in_quantity) - sum(out_quantity) as balance")->first()->balance;
                    if ($balance != 0 and $balance != null) {
                        DB::rollBack();
                        globalFunctions::flashMessage("softDelete", "account_is_not_zero", "account");
                        return back();
                    }
                    globalFunctions::registerUserActivityLog("deleted","product",$id);
                }
            } else {
                $result = null;
            }
            DB::commit();
        } catch (\PDOException $e){
            DB::rollBack();
            $result = null;
        }

        globalFunctions::flashMessage("softDelete",$result,"product");

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request,$product_id)
    {
        if ($product_id > 0) {
            $result = Product::onlyTrashed()->find($product_id)->restore();
            globalFunctions::registerUserActivityLog("restored","product",$product_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = Product::onlyTrashed()->whereIn("id",$ids)->restore();
            foreach ($ids as $id){
                globalFunctions::registerUserActivityLog("restored","product",$id);
            }
        } else {
            $result = null;
        }
        globalFunctions::flashMessage("restore",$result,"product");

        return back();
    }
}
