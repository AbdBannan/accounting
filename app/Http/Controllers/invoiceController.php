<?php
//
//namespace App\Http\Controllers;
//
//use App\functions\globalFunctions;
//use App\Models\Account;
//use App\Models\Journal;
//use App\Models\Product;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Str;
//use Illuminate\Validation\Rule;
//use Illuminate\Validation\Rules\Exists;
//
//class invoiceController extends Controller
//{
//    /**
//     * Display a listing of the resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function index($invoice_type)
//    {
//        globalFunctions::registerUserActivityLog("seen_all", $invoice_type . "_invoices", null);
//        $temp = $invoice_type;
//        $invoice_type = ($invoice_type == "sale") ? 1 : (($invoice_type == "purchase") ? 2 : (($invoice_type == "sale_return") ? 3 : 4));
//        $invoices = Journal::where("detail", 0)->where("invoice_type", $invoice_type)->selectRaw("invoice_id,second_part_name,sum(price * quantity) as value")->groupBy(["invoice_id", "second_part_name"])->orderBy("invoice_id")->get();
//        return view("invoices.products.viewInvoices")->with(["invoices" => $invoices, "invoice_type" => $temp]);
//    }
//
//    /**
//     * Show the form for creating a new resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function create($invoice_type)
//    {
//        return view("invoices.products.createInvoice")->with("invoice_type", $invoice_type);
//    }
//
//    /**
//     * Store a newly created resource in storage.
//     *
//     * @param \Illuminate\Http\Request $request
//     * @return \Illuminate\Http\Response
//     */
//    public function store(Request $request, $invoice_type)
//    {
//        $this->validate($request, [
//            "invoice_id" => "required",
//            "pound_type" => "required",
//            "second_part_name" => "required",
//        ]);
//        $file_name = null;
//        $path = null;
//        $path_and_file_name = null;
//        if ($file = $request->file("image")) {
//            $splitedDate = explode("-", $request["closing_date"]);
//            $path = "images/productInvoices/" . $splitedDate[0] . "/" . $splitedDate[1] . "/" . $splitedDate[2];
//            $file_name = $request["invoice_id"] . "." . $file->getClientOriginalExtension();
//            $file->move($path, $file_name);
//        } else {
//            $file_name = "default_invoice_img.phg";
//            $path = "systemImages";
//        }
//        $path_and_file_name = Str::replace("/", "#", $path . "/" . $file_name);
//
//
//        $result = $this->saveJournal($request, $invoice_type, $path_and_file_name);
////        if ($result=="none") {
////            session()->flash("success",__("messages.created_successfully",["attribute"=>"Invoice"],session("lang")));
////        }elseif($result == "adding_error"){
////            session()->flash("error",__("messages.not_created_successfully",["attribute"=>"Invoice"],session("lang")));
////        }elseif($result == "no_item_error"){
////            session()->flash("error",__("messages.no_item",["attribute"=>"Invoice"],session("lang")));
////        }
//
//        globalFunctions::flashMessage("create", $result[0], $invoice_type . "_invoice");
//        globalFunctions::registerUserActivityLog("added", $invoice_type . "_invoice", $result[1]);
//
//        return back();
//    }
//
//    private function saveJournal(Request $request, $invoice_type, $file_name)
//    {
//        $ctr = 1;
//        $data = $request->all();
//        $error = "none";
//        try {
//            DB::beginTransaction();
//            while (count($data) > 7) {
//                $result1 = $result2 = null;
//                if (isset($data["first_part_name_" . $ctr])) {
//                    $record1 = new Journal();
//                    $record1["invoice_id"] = $data["invoice_id"];
//                    $record1["line"] = $ctr;
//                    $record1["debit"] = ($invoice_type == "sale" || $invoice_type == "purchase_return") ? 0 : $data["total_price_" . $ctr];
//                    $record1["credit"] = ($invoice_type == "sale" || $invoice_type == "purchase_return") ? $data["total_price_" . $ctr] : 0;
//                    $record1["first_part_id"] = Account::where("name", $data["first_part_name_" . $ctr])->first()->id;
//                    $record1["first_part_name"] = $data["first_part_name_" . $ctr];
//                    $record1["second_part_id"] = Account::where("name", $data["second_part_name"])->first()->id;
//                    $record1["second_part_name"] = $data["second_part_name"];
//                    $record1["product_id"] = Product::where("name", $data["product_name_" . $ctr])->first()->id;
//                    $record1["product_name"] = $data["product_name_" . $ctr];
//                    $record1["price"] = $data["price_" . $ctr];
//                    $record1["quantity"] = $data["quantity_" . $ctr];
//                    $record1[($invoice_type == "sale" || $invoice_type == "purchase_return") ? "out_quantity" : "in_quantity"] = $data["quantity_" . $ctr];
////                $record1["posting"] = $data["posting".$ctr];
//                    $record1["pound_type"] = $data["pound_type"];
//                    $record1["num_for_pound"] = globalFunctions::getEquivalentPoundValue($data["pound_type"]);
//                    $record1["notes"] = $data["notes_" . $ctr];
//                    $record1["invoice_type"] = $invoice_type;
//                    $record1["detail"] = 0;
//                    $record1["image"] = $file_name;
//                    $record1["closing_date"] = $data["closing_date"];
////sub of balance
//                    $result1 = $record1->save();
//
//                    $record2 = new Journal();
//                    $record2["invoice_id"] = $data["invoice_id"];
//                    $record2["line"] = $ctr;
//                    $record2["debit"] = ($invoice_type == "sale" || $invoice_type == "purchase_return") ? $data["total_price_" . $ctr] : 0;
//                    $record2["credit"] = ($invoice_type == "sale" || $invoice_type == "purchase_return") ? 0 : $data["total_price_" . $ctr];
//                    $record2["first_part_id"] = Account::where("name", $data["second_part_name"])->first()->id;
//                    $record2["first_part_name"] = $data["second_part_name"];
//                    $record2["second_part_id"] = Account::where("name", $data["first_part_name_" . $ctr])->first()->id;
//                    $record2["second_part_name"] = $data["first_part_name_" . $ctr];
//                    $record2["product_id"] = Product::where("name", $data["product_name_" . $ctr])->first()->id;
//                    $record2["product_name"] = $data["product_name_" . $ctr];
//                    $record2["price"] = $data["price_" . $ctr];
//                    $record2["quantity"] = $data["quantity_" . $ctr];
////                $record2["in_quantity"] = $data["quantity_".$ctr];
////                $record2["posting"] = $data["posting_".$ctr];
//                    $record2["pound_type"] = $data["pound_type"];
//                    $record2["num_for_pound"] = globalFunctions::getEquivalentPoundValue($data["pound_type"]);
//                    $record2["notes"] = $data["notes_" . $ctr];
//                    $record2["invoice_type"] = "zero";
//                    $record2["detail"] = 0;
//                    $record2["image"] = $file_name;
//                    $record2["closing_date"] = $data["closing_date"];
//                    $result2 = $record2->save();
//
////                $first_part = Account::find($record1["first_part_id"]);
////                $second_part = Account::find($record2["first_part_id"]);
////                $first_part->debit = $first_part->debit + $record1["debit"];
////                $first_part->credit = $first_part->credit + $record1["credit"];
////                $second_part->debit = $second_part->debit + $record2["debit"];
////                $second_part->credit = $second_part->credit + $record2["credit"];
////                $first_part->save();
////                $second_part->save();
//
//                    unset($data["first_part_name_" . $ctr]);
////                unset($data["second_part_name_".$ctr]);
//                    unset($data["product_name_" . $ctr]);
//                    unset($data["quantity_" . $ctr]);
//                    unset($data["price_" . $ctr]);
//                    unset($data["total_price_" . $ctr]);
////                unset($data["posting_".$ctr]);
////                unset($data["pound_type_".$ctr]);
//                    unset($data["notes_" . $ctr]);
//                }
//                $ctr += 1;
//            }
//
//            DB::commit();
//        } catch (\PDOException $e) {
//            DB::rollBack();
//            $error = "adding_error";
//        }
//        if ($ctr == 1)
//            $error = "no_item_error";
//        return [$error, $data["invoice_id"]];
//    }
//
//    /**
//     * Display the specified resource.
//     *
//     * @param int $id
//     * @return \Illuminate\Http\Response
//     */
//    public function show($invoice_id, $invoice_type)
//    {
//        globalFunctions::registerUserActivityLog("seen", $invoice_type . "_invoice", $invoice_id);
//        return view("invoices.products.showInvoice")->with(["invoiceLines" => Journal::where("detail", 0)->where("invoice_id", $invoice_id)->whereIn("invoice_type", [1, 2, 3, 4])->orderBy("line")->get(), "invoice_type" => $invoice_type]);
//    }
//
//    /**
//     * Show the form for editing the specified resource.
//     *
//     * @param int $id
//     * @return \Illuminate\Http\Response
//     */
//    public function edit($id)
//    {
//        //
//    }
//
//    /**
//     * Update the specified resource in storage.
//     *
//     * @param \Illuminate\Http\Request $request
//     * @param int $id
//     * @return \Illuminate\Http\Response
//     */
//    public function update(Request $request, $invoice_type, $invoice_id)
//    {
//        $this->validate($request, [
//            "invoice_id" => "required",
//            "pound_type" => "required",
//            "second_part_name" => "required",
//        ]);
//
//        if (count($request->all()) <= 7) {
//            globalFunctions::flashMessage("update", "no_item_error", $invoice_type . "_invoice");
//            return back();
//        }
//        $file_name = null;
//        $path = null;
//        $path_and_file_name = null;
//        $oldPath = Journal::where("detail", 0)->whereIn("invoice_type", [0, 1, 2, 3, 4])->find($invoice_id)->image;
//        if ($file = $request->file("image")) {
//            if (file_exists(public_path($oldPath)) and $oldPath != "images/systemImages/default_product_img.png")
//                unlink(public_path($oldPath));
//            $splitedPath = explode("/", $oldPath);
//            if (count($splitedPath) == 5) {
//                $path = $splitedPath[2] . "/" . $splitedPath[3] . "/" . $splitedPath[4];
//            } else {
//                $date = Journal::where("invoice_id", $request["invoice_id"])->whereIn("invoice_type", [0, 1, 2, 3, 4])->first()->closing_date;
//                $path = $date->year . "/" . $date->month . "/" . $date->day;
//            }
//
//            $file_name = $request["invoice_id"] . "." . $file->getClientOriginalExtension();
//            $file->move("images/productInvoices/" . $path, $file_name);
//            $path_and_file_name = Str::replace("/", "#", $path . "/" . $file_name);
//        } else {
//            $path_and_file_name = Str::replace("/", "#", $oldPath);
//        }
//
//        Journal::where("detail", 0)->whereIn("invoice_type", [0, 1, 2, 3, 4])->where("invoice_id", $invoice_id)->forceDelete();
//
//        $result = $this->saveJournal($request, $invoice_type, $path_and_file_name);
////        if ($result=="none") {
////            session()->flash("success",__("messages.updated_successfully",["attribute"=>"Invoice"],session("lang")));
////        }elseif($result == "adding_error") {
////            session()->flash("error", __("messages.not_updated_successfully", ["attribute" => "Invoice"], session("lang")));
////        }
//
//        globalFunctions::flashMessage("update", $result[0], $invoice_type . "_invoice");
//        globalFunctions::registerUserActivityLog("updated", $invoice_type . "_invoice", $invoice_id);
////        return redirect("/invoice/showInvoice/$request[invoice_id]");
//        return back();
//    }
//
//
//    public function search(Request $request, Journal $journal)
//    {
//        $this->validate($request,["invoice_id"=>"required",Rule::exists('journal','invoice_id')]);
////        $invoice_type = ($invoice_type == "sale")? 1 : (($invoice_type == "purchase")? 2 : ( ($invoice_type == "sale_return")? 3 : 4));
////        $invoiceLines = Journal::where("invoice_id",$request["invoice_id"])->where("invoice_type",$invoice_type)->where("detail",7)->get();
//        $invoiceLines = Journal::where("detail", 0)->where("invoice_id", $request["invoice_id"])->whereIn("invoice_type", [1, 2, 3, 4])->get();
//
//        globalFunctions::registerUserActivityLog("searched", "invoice", $request["invoice_id"]);
//        if (count($invoiceLines) > 0) {
//            return view("invoices.products.showInvoice")->with(["invoiceLines" => $invoiceLines, "invoice_type" => $invoiceLines[0]->invoice_type]);
//        } else {
//            globalFunctions::flashMessage("search", "not_found", "invoice");
//            session()->flash("error", __("messages.not_found", ["attribute" => "Invoice"], session("lang")));
//            return back();
//        }
//    }
//
//    public function showSearchInvoice($invoice_type)
//    {
//        return view("invoices.products.showInvoice")->with(["invoiceLines" => [], "invoice_type" => $invoice_type]);
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     *
//     * @param int $id
//     * @return \Illuminate\Http\Response
//     */
//    public function destroy($invoice_id)
//    {
//        $image = Journal::onlyTrashed()->where("detail", 0)->whereIn("invoice_type", [0, 1, 2, 3, 4])->where("invoice_id", $invoice_id)->first()->image;
//        $result = Journal::onlyTrashed()->where("detail", 0)->whereIn("invoice_type", [0, 1, 2, 3, 4])->where("invoice_id", $invoice_id)->forceDelete();
//        if ($result != null) {
//            if ($image != "images/systemImages/default_product_img.png" and file_exists(public_path($image))) {
//                unlink(public_path($image));
//            }
//        }
////        if ($result!=null) {
////            session()->flash("success",__("messages.deleted_successfully",["attribute"=>"Invoice"],session("lang")));
////        }else{
////            session()->flash("error",__("messages.not_deleted_successfully",["attribute"=>"Invoice"],session("lang")));
////        }
//        globalFunctions::flashMessage("delete", $result, "invoice");
//        globalFunctions::registerUserActivityLog("deleted", "invoice", $invoice_id);
//
//        return back();
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function viewRecyclebin()
//    {
//        globalFunctions::registerUserActivityLog("seen", "invoices_recyclebin", null);
//        $deletedInvoices = Journal::onlyTrashed()->where("detail", 0)->whereIn("invoice_type", [1, 2, 3, 4])->selectRaw("invoice_id,second_part_name,sum(price * quantity) as value")->groupBy(["invoice_id", "second_part_name", "price", "quantity"])->orderBy("invoice_id")->get();
//        return view("invoices.products.recyclebin")->with("deletedInvoices", $deletedInvoices);
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     *
//     * @param Account $account
//     * @return \Illuminate\Http\Response
//     */
//    public function softDelete($invoice_id)
//    {
////        dd($invoice_id);
//        $result = Journal::where("invoice_id", $invoice_id)->where("detail", 0)->whereIn("invoice_type", [0, 1, 2, 3, 4])->delete();
//
////        if ($result!=null) {
////            session()->flash("success",__("messages.recycled_successfully",["attribute"=>"Invoice"],session("lang")));
////        }else{
////            session()->flash("error",__("messages.not_recycled_successfully",["attribute"=>"Invoice"],session("lang")));
////        }
//
//        globalFunctions::flashMessage("softDelete", $result, "invoice");
//        globalFunctions::registerUserActivityLog("recycled", "invoice", $invoice_id);
//
//        return back();
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     *
//     * @param int $id
//     * @return \Illuminate\Http\Response
//     */
//    public function restore($invoice_id)
//    {
//        $result = Journal::onlyTrashed()->where("detail", 0)->whereIn("invoice_type", [0, 1, 2, 3, 4])->where("invoice_id", $invoice_id)->restore();
//
////        if ($result!=null) {
////            session()->flash("success",__("messages.restored_successfully",["attribute"=>"Invoice"],session("lang")));
////        }else{
////            session()->flash("error",__("messages.restored_successfully",["attribute"=>"Invoice"],session("lang")));
////        }
//
//        globalFunctions::flashMessage("restore", $result, "invoice");
//        globalFunctions::registerUserActivityLog("restored", "invoice", $invoice_id);
//
//        return back();
//    }
//}
//
//
//
//



///////////////////////////////
///
///
///
///
///
//
namespace App\Http\Controllers;

use App\functions\globalFunctions;
use App\Models\Account;
use App\Models\Journal;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;

class invoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($invoice_type)
    {
        globalFunctions::registerUserActivityLog("seen_all", $invoice_type . "_invoices", null);
        $temp = $invoice_type;
        $invoice_type = ($invoice_type == "sale") ? 1 : (($invoice_type == "purchase") ? 2 : (($invoice_type == "sale_return") ? 3 : 4));
        $invoices = Journal::where("detail", 0)->where("invoice_type", $invoice_type)->selectRaw("invoice_id,second_part_name,sum(price * quantity) as value")->groupBy(["invoice_id", "second_part_name"])->orderBy("invoice_id")->get();
        return view("invoices.products.viewInvoices")->with(["invoices" => $invoices, "invoice_type" => $temp]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($invoice_type)
    {
        return view("invoices.products.createInvoice")->with("invoice_type", $invoice_type);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $invoice_type)
    {
        $this->validate($request, [
            "invoice_id" => "required",
            "pound_type" => "required",
            "second_part_name" => "required",
        ]);
        $file_name = null;
        $path = null;
        $path_and_file_name = null;
        if ($file = $request->file("image")) {
            $splitedDate = explode("-", $request["closing_date"]);
            $path = "images/productInvoices/" . $splitedDate[0] . "/" . $splitedDate[1] . "/" . $splitedDate[2];
            $file_name = $request["invoice_id"] . "." . $file->getClientOriginalExtension();
            $file->move($path, $file_name);
        } else {
            $file_name = "default_invoice_img.phg";
            $path = "systemImages";
        }
        $path_and_file_name = Str::replace("/", "#", $path . "/" . $file_name);


        $result = $this->saveJournal($request, $invoice_type, $path_and_file_name);
//        if ($result=="none") {
//            session()->flash("success",__("messages.created_successfully",["attribute"=>"Invoice"],session("lang")));
//        }elseif($result == "adding_error"){
//            session()->flash("error",__("messages.not_created_successfully",["attribute"=>"Invoice"],session("lang")));
//        }elseif($result == "no_item_error"){
//            session()->flash("error",__("messages.no_item",["attribute"=>"Invoice"],session("lang")));
//        }

        globalFunctions::flashMessage("create", $result[0], $invoice_type . "_invoice");
        globalFunctions::registerUserActivityLog("added", $invoice_type . "_invoice", $result[1]);

        return back();
    }

    private function saveJournal(Request $request, $invoice_type, $file_name)
    {
        $ctr = 1;
        $data = $request->all();
        $error = "none";
        try {
            DB::beginTransaction();
            while (count($data) > 7) {
                $result1 = $result2 = null;
                if (isset($data["first_part_name_" . $ctr])) {
                    $record1 = new Journal();
                    $record1["invoice_id"] = $data["invoice_id"];
                    $record1["line"] = $ctr;
                    $record1["debit"] = ($invoice_type == "sale" || $invoice_type == "purchase_return") ? 0 : $data["total_price_" . $ctr];
                    $record1["credit"] = ($invoice_type == "sale" || $invoice_type == "purchase_return") ? $data["total_price_" . $ctr] : 0;
                    $record1["first_part_id"] = Account::where("name", $data["first_part_name_" . $ctr])->first()->id;
                    $record1["first_part_name"] = $data["first_part_name_" . $ctr];
                    $record1["second_part_id"] = Account::where("name", $data["second_part_name"])->first()->id;
                    $record1["second_part_name"] = $data["second_part_name"];
                    $record1["product_id"] = Product::where("name", $data["product_name_" . $ctr])->first()->id;
                    $record1["product_name"] = $data["product_name_" . $ctr];
                    $record1["price"] = $data["price_" . $ctr];
                    $record1["quantity"] = $data["quantity_" . $ctr];
                    $record1[($invoice_type == "sale" || $invoice_type == "purchase_return") ? "out_quantity" : "in_quantity"] = $data["quantity_" . $ctr];
//                $record1["posting"] = $data["posting".$ctr];
                    $record1["pound_type"] = $data["pound_type"];
                    $record1["num_for_pound"] = globalFunctions::getEquivalentPoundValue($data["pound_type"]);
                    $record1["notes"] = $data["notes_" . $ctr];
                    $record1["invoice_type"] = $invoice_type;
                    $record1["detail"] = 0;
                    $record1["image"] = $file_name;
                    $record1["closing_date"] = $data["closing_date"];
//sub of balance
                    $result1 = $record1->save();

                    $record2 = new Journal();
                    $record2["invoice_id"] = $data["invoice_id"];
                    $record2["line"] = $ctr;
                    $record2["debit"] = ($invoice_type == "sale" || $invoice_type == "purchase_return") ? $data["total_price_" . $ctr] : 0;
                    $record2["credit"] = ($invoice_type == "sale" || $invoice_type == "purchase_return") ? 0 : $data["total_price_" . $ctr];
                    $record2["first_part_id"] = Account::where("name", $data["second_part_name"])->first()->id;
                    $record2["first_part_name"] = $data["second_part_name"];
                    $record2["second_part_id"] = Account::where("name", $data["first_part_name_" . $ctr])->first()->id;
                    $record2["second_part_name"] = $data["first_part_name_" . $ctr];
                    $record2["product_id"] = Product::where("name", $data["product_name_" . $ctr])->first()->id;
                    $record2["product_name"] = $data["product_name_" . $ctr];
                    $record2["price"] = $data["price_" . $ctr];
                    $record2["quantity"] = $data["quantity_" . $ctr];
//                $record2["in_quantity"] = $data["quantity_".$ctr];
//                $record2["posting"] = $data["posting_".$ctr];
                    $record2["pound_type"] = $data["pound_type"];
                    $record2["num_for_pound"] = globalFunctions::getEquivalentPoundValue($data["pound_type"]);
                    $record2["notes"] = $data["notes_" . $ctr];
                    $record2["invoice_type"] = "zero";
                    $record2["detail"] = 0;
                    $record2["image"] = $file_name;
                    $record2["closing_date"] = $data["closing_date"];
                    $result2 = $record2->save();

//                $first_part = Account::find($record1["first_part_id"]);
//                $second_part = Account::find($record2["first_part_id"]);
//                $first_part->debit = $first_part->debit + $record1["debit"];
//                $first_part->credit = $first_part->credit + $record1["credit"];
//                $second_part->debit = $second_part->debit + $record2["debit"];
//                $second_part->credit = $second_part->credit + $record2["credit"];
//                $first_part->save();
//                $second_part->save();

                    unset($data["first_part_name_" . $ctr]);
//                unset($data["second_part_name_".$ctr]);
                    unset($data["product_name_" . $ctr]);
                    unset($data["quantity_" . $ctr]);
                    unset($data["price_" . $ctr]);
                    unset($data["total_price_" . $ctr]);
//                unset($data["posting_".$ctr]);
//                unset($data["pound_type_".$ctr]);
                    unset($data["notes_" . $ctr]);
                }
                $ctr += 1;
            }

            DB::commit();
        } catch (\PDOException $e) {
            DB::rollBack();
            $error = "adding_error";
        }
        if ($ctr == 1)
            $error = "no_item_error";
        return [$error, $data["invoice_id"]];
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($invoice_id)
    {
//        globalFunctions::registerUserActivityLog("seen", $invoice_type . "_invoice", $invoice_id);
        globalFunctions::registerUserActivityLog("seen", "invoice", $invoice_id);
        return view("invoices.products.showInvoice")->with("invoiceLines",Journal::where("detail", 0)->where("invoice_id", $invoice_id)->whereIn("invoice_type", [1, 2, 3, 4])->orderBy("line")->get());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $invoice_type, $invoice_id)
    {
        $this->validate($request, [
            "invoice_id" => "required",
            "pound_type" => "required",
            "second_part_name" => "required",
        ]);

        if (count($request->all()) <= 7) {
            globalFunctions::flashMessage("update", "no_item_error", $invoice_type . "_invoice");
            return back();
        }
        $file_name = null;
        $path = null;
        $path_and_file_name = null;
        $oldPath = Journal::where("detail", 0)->whereIn("invoice_type", [0, 1, 2, 3, 4])->find($invoice_id)->image;
        if ($file = $request->file("image")) {
            if (file_exists(public_path($oldPath)) and $oldPath != "images/systemImages/default_product_img.png")
                unlink(public_path($oldPath));
            $splitedPath = explode("/", $oldPath);
            if (count($splitedPath) == 5) {
                $path = $splitedPath[2] . "/" . $splitedPath[3] . "/" . $splitedPath[4];
            } else {
                $date = Journal::where("invoice_id", $request["invoice_id"])->whereIn("invoice_type", [0, 1, 2, 3, 4])->first()->closing_date;
                $path = $date->year . "/" . $date->month . "/" . $date->day;
            }

            $file_name = $request["invoice_id"] . "." . $file->getClientOriginalExtension();
            $file->move("images/productInvoices/" . $path, $file_name);
            $path_and_file_name = Str::replace("/", "#", $path . "/" . $file_name);
        } else {
            $path_and_file_name = Str::replace("/", "#", $oldPath);
        }

        Journal::where("detail", 0)->whereIn("invoice_type", [0, 1, 2, 3, 4])->where("invoice_id", $invoice_id)->forceDelete();

        $result = $this->saveJournal($request, $invoice_type, $path_and_file_name);
//        if ($result=="none") {
//            session()->flash("success",__("messages.updated_successfully",["attribute"=>"Invoice"],session("lang")));
//        }elseif($result == "adding_error") {
//            session()->flash("error", __("messages.not_updated_successfully", ["attribute" => "Invoice"], session("lang")));
//        }

        globalFunctions::flashMessage("update", $result[0], $invoice_type . "_invoice");
        globalFunctions::registerUserActivityLog("updated", $invoice_type . "_invoice", $invoice_id);
//        return redirect("/invoice/showInvoice/$request[invoice_id]");
        return back();
    }


    public function search(Request $request, Journal $journal)
    {
        $this->validate($request,["invoice_id"=>"required",Rule::exists('journal','invoice_id')]);
//        $invoiceLines = Journal::where("detail", 0)->where("invoice_id", $request["invoice_id"])->whereIn("invoice_type", [1, 2, 3, 4])->get();
//        dd($invoiceLines);
        globalFunctions::registerUserActivityLog("searched", "invoice", $request["invoice_id"]);
        return redirect(route("invoice.showInvoice",$request["invoice_id"]));
//        return view("invoices.products.showInvoice")->with(["invoiceLines" => $invoiceLines, "invoice_type" => $invoiceLines[0]->invoice_type]);
    }

    public function showSearchInvoice($invoice_type)
    {
        return view("invoices.products.showInvoice")->with(["invoiceLines" => [], "invoice_type" => $invoice_type]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($invoice_id)
    {
        $image = Journal::onlyTrashed()->where("detail", 0)->whereIn("invoice_type", [0, 1, 2, 3, 4])->where("invoice_id", $invoice_id)->first()->image;
        $result = Journal::onlyTrashed()->where("detail", 0)->whereIn("invoice_type", [0, 1, 2, 3, 4])->where("invoice_id", $invoice_id)->forceDelete();
        if ($result != null) {
            if ($image != "images/systemImages/default_product_img.png" and file_exists(public_path($image))) {
                unlink(public_path($image));
            }
        }
//        if ($result!=null) {
//            session()->flash("success",__("messages.deleted_successfully",["attribute"=>"Invoice"],session("lang")));
//        }else{
//            session()->flash("error",__("messages.not_deleted_successfully",["attribute"=>"Invoice"],session("lang")));
//        }
        globalFunctions::flashMessage("delete", $result, "invoice");
        globalFunctions::registerUserActivityLog("deleted", "invoice", $invoice_id);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewRecyclebin()
    {
        globalFunctions::registerUserActivityLog("seen", "invoices_recyclebin", null);
        $deletedInvoices = Journal::onlyTrashed()->where("detail", 0)->whereIn("invoice_type", [1, 2, 3, 4])->selectRaw("invoice_id,second_part_name,sum(price * quantity) as value")->groupBy(["invoice_id", "second_part_name", "price", "quantity"])->orderBy("invoice_id")->get();
        return view("invoices.products.recyclebin")->with("deletedInvoices", $deletedInvoices);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Account $account
     * @return \Illuminate\Http\Response
     */
    public function softDelete($invoice_id)
    {
//        dd($invoice_id);
        $result = Journal::where("invoice_id", $invoice_id)->where("detail", 0)->whereIn("invoice_type", [0, 1, 2, 3, 4])->delete();

//        if ($result!=null) {
//            session()->flash("success",__("messages.recycled_successfully",["attribute"=>"Invoice"],session("lang")));
//        }else{
//            session()->flash("error",__("messages.not_recycled_successfully",["attribute"=>"Invoice"],session("lang")));
//        }

        globalFunctions::flashMessage("softDelete", $result, "invoice");
        globalFunctions::registerUserActivityLog("recycled", "invoice", $invoice_id);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($invoice_id)
    {
        $result = Journal::onlyTrashed()->where("detail", 0)->whereIn("invoice_type", [0, 1, 2, 3, 4])->where("invoice_id", $invoice_id)->restore();

//        if ($result!=null) {
//            session()->flash("success",__("messages.restored_successfully",["attribute"=>"Invoice"],session("lang")));
//        }else{
//            session()->flash("error",__("messages.restored_successfully",["attribute"=>"Invoice"],session("lang")));
//        }

        globalFunctions::flashMessage("restore", $result, "invoice");
        globalFunctions::registerUserActivityLog("restored", "invoice", $invoice_id);

        return back();
    }
}




///////////////////////
