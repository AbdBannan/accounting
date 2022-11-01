<?php

namespace App\Http\Controllers;

use App\functions\globalFunctions;
use App\Models\Account;
use App\Models\Journal;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class cashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session(["previouse_url"=>"view_all"]);
        globalFunctions::registerUserActivityLog("seen_all", "cash_invoices",null);
        $invoices = Journal::where("detail",1)->where("invoice_type",5)->selectRaw("invoice_id,first_part_name,sum(debit) / num_for_pound as deb, sum(credit) / num_for_pound as cred , pound_type")->groupBy(["invoice_id","first_part_name","num_for_pound","pound_type"])->orderBy("invoice_id")->get();
        return view("invoices.cashes.viewInvoices")->with("invoices",$invoices);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("invoices.cashes.createInvoice");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            "invoice_id" => "required",
            "pound_type" => "required",
            "first_part_name" => "required",
        ]);
        $file_name = null;
        $path = null;
        $path_and_file_name = null;
        if($file = $request->file("image")){
            $splitedDate = explode("-",$request["closing_date"]);
            $path = "images/cashInvoices/".$splitedDate[0]."/".$splitedDate[1]."/".$splitedDate[2];
            $file_name = $request["invoice_id"] . "." . $file->getClientOriginalExtension();
            $file->move($path,$file_name);
        }
        else{
            $file_name = "default_invoice_img.phg";
            $path = "systemImages";
        }
        $path_and_file_name = Str::replace("/","#", $path . "/" . $file_name);
        $result = $this->saveJournal($request,$path_and_file_name);
//        if ($result=="none") {
//            session()->flash("success",__("messages.created_successfully",["attribute"=>"Invoice"],session("lang")));
//        }elseif($result == "adding_error"){
//            session()->flash("error",__("messages.not_created_successfully",["attribute"=>"Invoice"],session("lang")));
//        }elseif($result == "no_item_error"){
//            session()->flash("error",__("messages.no_item",["attribute"=>"Invoice"],session("lang")));
//        }

        globalFunctions::flashMessage("create",$result[0],"cash_invoice");
        globalFunctions::registerUserActivityLog("added","cash_invoice",$result[1]);

        return back();
    }

    private function saveJournal(Request $request,$file_name)
    {
//        dd($request->all());
        $ctr = 1;
        $data = $request->all();
        $error = "none";
        try {
            DB::beginTransaction();
            while (count($data)>7) {
                $result1 = $result2 = null;
                if (isset($data["second_part_name_".$ctr])){
                    $record1 = new Journal();
                    $record1["invoice_id"] = $data["invoice_id"];
                    $record1["line"] = $ctr;
                    $record1["debit"] = $data["payed_".$ctr];
                    $record1["credit"] = $data["received_".$ctr];
                    $record1["first_part_id"] = Account::where("name",$data["first_part_name"])->first()->id;
                    $record1["first_part_name"] = $data["first_part_name"];
                    $record1["second_part_id"] = Account::where("name",$data["second_part_name_".$ctr])->first()->id;
                    $record1["second_part_name"] = $data["second_part_name_".$ctr];
                    $record1["pound_type"] = $data["pound_type"];
                    $record1["num_for_pound"] = globalFunctions::getEquivalentPoundValue($data["pound_type"]);
                    $record1["notes"] = $data["notes_".$ctr];
                    $record1["invoice_type"] = 5;
                    $record1["detail"] = 1;
                    $record1["image"] = $file_name;
                    $record1["closing_date"] = $data["closing_date"];
//sub of balance
                    $result1 =  $record1->save();

                    $record2 = new Journal();
                    $record2["invoice_id"] = $data["invoice_id"];
                    $record2["line"] = $ctr;
                    $record2["debit"] = $data["received_".$ctr];
                    $record2["credit"] = $data["payed_".$ctr];
                    $record2["first_part_id"] = Account::where("name",$data["second_part_name_".$ctr])->first()->id;
                    $record2["first_part_name"] = $data["second_part_name_".$ctr];
                    $record2["second_part_id"] = Account::where("name",$data["first_part_name"])->first()->id;
                    $record2["second_part_name"] = $data["first_part_name"];
                    $record2["pound_type"] = $data["pound_type"];
                    $record2["num_for_pound"] = globalFunctions::getEquivalentPoundValue($data["pound_type"]);
                    $record2["notes"] = $data["notes_".$ctr];
                    $record2["invoice_type"] = ($data["payed_".$ctr]!=0)?6:7;
                    $record2["detail"] = 1;
                    $record2["image"] = $file_name;
                    $record2["closing_date"] = $data["closing_date"];
                    $result2 = $record2->save();

                    unset($data["second_part_name_".$ctr]);
                    unset($data["payed_".$ctr]);
                    unset($data["received_".$ctr]);
                    unset($data["notes_".$ctr]);
                }
                $ctr+=1;
            }
            DB::commit();
        }
        catch (\PDOException $e){
            $error = "adding_error";
            DB::rollBack();
        }

        if ($ctr == 1)
            $error = "no_item_error";
        return [$error,$data["invoice_id"]];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($invoice_id)
    {
        globalFunctions::registerUserActivityLog("seen","cash_invoice",$invoice_id);
        return view("invoices.cashes.showInvoice")->with("invoiceLines",Journal::where("detail",1)->where("invoice_id",$invoice_id)->where("invoice_type",5)->orderBy("line")->get());
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
    public function update(Request $request, $invoice_id)
    {
        $this->validate($request,[
            "invoice_id" => "required",
            "pound_type" => "required",
            "first_part_name" => "required",
        ]);

        if (count($request->all())<=7) {
            globalFunctions::flashMessage("update","no_item_error","cash_invoice");
            return back();
        }
        $file_name  = null;
        $path = null;
        $path_and_file_name = null;
        $oldPath = Journal::where("detail",1)->whereIn("invoice_type",[5,6,7])->find($invoice_id)->image;
        if($file = $request->file("image")){
            if (file_exists(public_path($oldPath)) and $oldPath != "images/systemImages/default_product_img.png")
                unlink(public_path($oldPath));
            $splitedPath = explode("/",$oldPath);
            if (count($splitedPath) == 5) {
                $path = $splitedPath[2] . "/" . $splitedPath[3] . "/" . $splitedPath[4];
            } else {
                $date = Journal::where("invoice_id",$request["invoice_id"])->whereIn("invoice_type",[5,6,7])->first()->closing_date;
                $path = $date->year . "/" . $date->month . "/" . $date->day;
            }
            $file_name = $request["invoice_id"] . "." . $file->getClientOriginalExtension();
            $file->move("images/cashInvoices/".$path,$file_name);
            $path_and_file_name = Str::replace("/","#", $path . "/" . $file_name);
        }
        else{
            $path_and_file_name = Str::replace("/","#", $oldPath);
        }
        Journal::where("detail",1)->whereIn("invoice_type",[5,6,7])->where("invoice_id",$invoice_id)->forceDelete();

        $result = $this->saveJournal($request,$path_and_file_name);
//        if ($result=="none") {
//            session()->flash("success",__("messages.updated_successfully",["attribute"=>"Invoice"],session("lang")));
//        }elseif($result == "adding_error") {
//            session()->flash("error", __("messages.not_updated_successfully", ["attribute" => "Invoice"], session("lang")));
//        }

        globalFunctions::flashMessage("update",$result[0],"cash_invoice");
        globalFunctions::registerUserActivityLog("updated","cash_invoice",$invoice_id);

        return redirect("/invoice/showCashInvoice/$request[invoice_id]");
    }



    public function search(Request $request)
    {
        $this->validate($request,["invoice_id"=>"required"]);
//        $invoiceLines = Journal::where("detail",1)->where("invoice_id",$request["invoice_id"])->where("invoice_type",5)->get();
        globalFunctions::registerUserActivityLog("searched","cash_invoice",$request["invoice_id"]);
        return redirect(route("invoice.showCashInvoice",$request["invoice_id"]));

//        if (count($invoiceLines)>0) {
//            return view("invoices.cashes.showInvoice")->with("invoiceLines",$invoiceLines);
//        }else{
//            globalFunctions::flashMessage("search","not_found","cash_invoice");
//            return back();
//        }
    }

    public function showSearchInvoice(){
        session(["previouse_url"=>"show_search"]);
        return view("invoices.cashes.showInvoice")->with("invoiceLines",[]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($invoice_id)
    {
        $image = Journal::onlyTrashed()->where("detail",1)->whereIn("invoice_type",[5,6,7])->where("invoice_id",$invoice_id)->first()->image;
        $result = Journal::onlyTrashed()->where("detail",1)->whereIn("invoice_type",[5,6,7])->where("invoice_id",$invoice_id)->forceDelete();

        if ($result!=null) {
            if ($image != "images/systemImages/default_product_img.png"  and file_exists(public_path($image))) {
                unlink(public_path($image));
            }
        }
//        if ($result!=null) {
//            session()->flash("success",__("messages.deleted_successfully",["attribute"=>"Invoice"],session("lang")));
//        }else{
//            session()->flash("success",__("messages.not_deleted_successfully",["attribute"=>"Invoice"],session("lang")));
//        }

        globalFunctions::flashMessage("delete",$result,"cash_invoice");
        globalFunctions::registerUserActivityLog("deleted","cash_invoice",$invoice_id);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewRecyclebin()
    {
        globalFunctions::registerUserActivityLog("seen","cash_invoices_recyclebin",null);
        $deletedInvoices = Journal::onlyTrashed()->where("detail",1)->where("invoice_type",5)->selectRaw("invoice_id,first_part_name,sum(debit) / num_for_pound as deb, sum(credit) / num_for_pound as cred , pound_type")->groupBy(["invoice_id","first_part_name","num_for_pound",'pound_type'])->get()->sortBy("line");
        return view("invoices.cashes.recyclebin")->with("deletedInvoices",$deletedInvoices);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Account $account
     * @return \Illuminate\Http\Response
     */
    public function softDelete($invoice_id)
    {
        $result = Journal::where("detail",1)->whereIn("invoice_type",[5,6,7])->where("invoice_id",$invoice_id)->delete();

//        if ($result!=null) {
//            session()->flash("success",__("messages.recycled_successfully",["attribute"=>"Invoice"],session("lang")));
//        }else{
//            session()->flash("success",__("messages.not_recycled_successfully",["attribute"=>"Invoice"],session("lang")));
//        }

        globalFunctions::flashMessage("softDelete",$result,"cash_invoice");
        globalFunctions::registerUserActivityLog("recycled","cas_invoice",$invoice_id);

        if (session("previouse_url") == "show_search") {
            return redirect(route("invoice.showSearchCashInvoice"));
        } else {
            return redirect(route("invoice.viewCashInvoices"));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($invoice_id)
    {
        $result = Journal::onlyTrashed()->where("detail",1)->whereIn("invoice_type",[5,6,7])->where("invoice_id",$invoice_id)->restore();


//        if ($result!=null) {
//            session()->flash("success",__("messages.restored_successfully",["attribute"=>"Invoice"],session("lang")));
//        }else{
//            session()->flash("success",__("messages.restored_successfully",["attribute"=>"Invoice"],session("lang")));
//        }


        globalFunctions::flashMessage("restore",$result,"cash_invoice");
        globalFunctions::registerUserActivityLog("restored","cash_invoice",$invoice_id);
        return back();
    }
}
