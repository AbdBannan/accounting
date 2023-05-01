<?php

namespace App\Http\Controllers;

use App\functions\globalFunctions;
use App\Models\Account;
use App\Models\Journal;
use App\Models\Product;
use App\Rules\EnoughProductQuantityRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;
use function PHPUnit\Framework\fileExists;
use function PHPUnit\Framework\stringContains;

class invoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create($invoice_type)
    {
        File::cleanDirectory("temp_images");
        return view("invoices.products.createInvoice")->with("invoice_type", $invoice_type);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $invoice_type)
    {
        $this->validate($request, [
            "invoice_id" => ["required"],
            "pound_type" => "required",
            "second_part_name" => ["required",Rule::exists("accounts","name")->where("deleted_at",null)],
            "product_name" => [Rule::exists("products","name")->where("deleted_at",null)],
            'file' => ['image','mimes:jpg,png,jpeg,gif,svg',"max"=>"2048mB"]
        ]);

        $file_name = null;
        $path = null;
        $path_and_file_name = null;
        if ($file = $request->file("image") and $request["upload_image_method"] == "image") {
            $splitedDate = explode("-", $request["closing_date"]);
            $path = $splitedDate[0] . "/" . $splitedDate[1] . "/" . $splitedDate[2];
            $file_name = $request["invoice_id"] . "." . $file->getClientOriginalExtension();
            $file->move("images/productInvoices/" . $path, $file_name);
            $path_and_file_name = Str::replace("/", "#", $path . "/" . $file_name);
        } elseif ($uploaded_file = glob(public_path("images/temp_images/invoice_$request[invoice_id].*")) and $request["upload_image_method"] == "qr") {
            $splitedDate = explode("-", $request["closing_date"]);
            $path = $splitedDate[0] . "/" . $splitedDate[1] . "/" . $splitedDate[2];
            $extention = File::extension($uploaded_file[0]);
            $file_name = $request["invoice_id"] . "." . $extention;
            File::ensureDirectoryExists("images/productInvoices/$path");
            File::move(public_path("images/temp_images/invoice_$request[invoice_id].$extention"),public_path("images/productInvoices/" . $path . "/" . $file_name));
            $path_and_file_name = Str::replace("/", "#", $path . "/" . $file_name);
        }
        else {
            $file_name = "default_invoice_img.png";
            $path_and_file_name = $file_name;
        }

        try {
            DB::beginTransaction();
            $result = $this->saveJournal($request,$invoice_type,$path_and_file_name);
            if ($result == "no_item_error"){
                globalFunctions::flashMessage("create","no_item_error",$invoice_type . "_invoice");
                DB::rollBack();
            } else {
                globalFunctions::flashMessage("create",true,$invoice_type . "_invoice");
                globalFunctions::registerUserActivityLog("added",$invoice_type . "_invoice",$result);
            }
            DB::commit();
        } catch (\PDOException $e)
        {
            DB::rollBack();
            globalFunctions::flashMessage("create",false,$invoice_type . "_invoice");
        }
        return back();
    }

    private function saveJournal(Request $request, $invoice_type, $file_name)
    {
        $ctr = 1;
        $data = $request->all();
        while (count($data) > 8) {
            $result1 = $result2 = null;
            if (isset($data["first_part_name_" . $ctr])) {
                $this->validate(
                    $request,
                    [
                        "first_part_name_$ctr"=>Rule::exists("accounts","name")->where("deleted_at",null),
                        "product_name_$ctr"=>[function ($attribute,$value,$fail) use ($ctr,$data,$invoice_type)  {
                            $balance = Journal::where("product_name",$value)->selectRaw("sum(in_quantity) - sum(out_quantity) as balance")->first()->balance;
                            if ($balance < $data["quantity_$ctr"] and in_array($invoice_type,["sale","purchase_return"])){
                                $fail(__("messages.the_quantity_is_not_enough",["attribute"=>$data["product_name_$ctr"]]));
                            }
                        }],
                    ]
                );
                $record1 = new Journal();
                $record1["invoice_id"] = $data["invoice_id"];
                $record1["line"] = $ctr;
                $record1["debit"] = ($invoice_type == "sale" || $invoice_type == "purchase_return") ? 0 : $data["total_price_" . $ctr] * globalFunctions::getEquivalentPoundValue($data["pound_type"]);
                $record1["credit"] = ($invoice_type == "sale" || $invoice_type == "purchase_return") ? $data["total_price_" . $ctr] * globalFunctions::getEquivalentPoundValue($data["pound_type"]) : 0;
                $record1["first_part_id"] = Account::where("name", $data["first_part_name_" . $ctr])->first()->id;
                $record1["first_part_name"] = $data["first_part_name_" . $ctr];
                $record1["second_part_id"] = Account::where("name", $data["second_part_name"])->first()->id;
                $record1["second_part_name"] = $data["second_part_name"];
                $record1["product_id"] = Product::where("name", $data["product_name_" . $ctr])->first()->id;
                $record1["product_name"] = $data["product_name_" . $ctr];
                $record1["price"] = $data["price_" . $ctr];
                $record1["quantity"] = $data["quantity_" . $ctr];
                $record1["sum_of_balance"] = $record1["credit"] - $record1["debit"];
                $record1[($invoice_type == "sale" || $invoice_type == "purchase_return") ? "out_quantity" : "in_quantity"] = $data["quantity_" . $ctr];
                $record1["pound_type"] = $data["pound_type"];
                $record1["num_for_pound"] = globalFunctions::getEquivalentPoundValue($data["pound_type"]);
                $record1["notes"] = $data["notes_" . $ctr];
                $record1["invoice_type"] = $invoice_type;
                $record1["detail"] = 0;
                $record1["image"] = $file_name;
                $record1["closing_date"] = $data["closing_date"];
                $result1 = $record1->save();

                $record2 = new Journal();
                $record2["invoice_id"] = $data["invoice_id"];
                $record2["line"] = $ctr;
                $record2["debit"] = ($invoice_type == "sale" || $invoice_type == "purchase_return") ? $data["total_price_" . $ctr] * globalFunctions::getEquivalentPoundValue($data["pound_type"]): 0;
                $record2["credit"] = ($invoice_type == "sale" || $invoice_type == "purchase_return") ? 0 : $data["total_price_" . $ctr] * globalFunctions::getEquivalentPoundValue($data["pound_type"]);
                $record2["first_part_id"] = Account::where("name", $data["second_part_name"])->first()->id;
                $record2["first_part_name"] = $data["second_part_name"];
                $record2["second_part_id"] = Account::where("name", $data["first_part_name_" . $ctr])->first()->id;
                $record2["second_part_name"] = $data["first_part_name_" . $ctr];
                $record2["product_id"] = Product::where("name", $data["product_name_" . $ctr])->first()->id;
                $record2["product_name"] = $data["product_name_" . $ctr];
                $record2["price"] = $data["price_" . $ctr];
                $record2["quantity"] = $data["quantity_" . $ctr];
                $record2["sum_of_balance"] = $record2["credit"] - $record2["debit"];
                $record2["pound_type"] = $data["pound_type"];
                $record2["num_for_pound"] = globalFunctions::getEquivalentPoundValue($data["pound_type"]);
                $record2["notes"] = $data["notes_" . $ctr];
                $record2["invoice_type"] = "zero";
                $record2["detail"] = 0;
                $record2["image"] = $file_name;
                $record2["closing_date"] = $data["closing_date"];
                $result2 = $record2->save();

                unset($data["first_part_name_" . $ctr]);
                unset($data["product_name_" . $ctr]);
                unset($data["quantity_" . $ctr]);
                unset($data["price_" . $ctr]);
                unset($data["total_price_" . $ctr]);
                unset($data["notes_" . $ctr]);
            }
            $ctr += 1;
        }

        if ($ctr == 1)
            return "no_item_error";
        return $data["invoice_id"];
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show($invoice_id)
    {
        session(["previous_previous_url"=>URL::previous()]);
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $invoice_type, $invoice_id)
    {

        $this->validate($request, [
            "invoice_id" => "required",
            "pound_type" => "required",
            "second_part_name" => ["required",Rule::exists("accounts","name")->where("deleted_at",null)],
            "product_name" => [Rule::exists("products","name")->where("deleted_at",null)],
            'file' => ['image','mimes:jpg,png,jpeg,gif,svg'],
        ]);

        $file_name = null;
        $path = null;
        $path_and_file_name = null;
        $oldPath = Journal::where("detail", 0)->whereIn("invoice_type", [0, 1, 2, 3, 4])->find($invoice_id)->image;
        if ($file = $request->file("image") and $request["upload_image_method"] == "image") {
            if (file_exists(public_path($oldPath)) and $oldPath != "images/systemImages/default_invoice_img.png")
                unlink(public_path($oldPath));
            $splitedPath = explode("/", $oldPath);
            if (count($splitedPath) == 6) {
                $path = $splitedPath[2] . "/" . $splitedPath[3] . "/" . $splitedPath[4];
            } else {
                $date = Journal::where("invoice_id", $request["invoice_id"])->whereIn("invoice_type", [0, 1, 2, 3, 4])->first()->closing_date;
                $path = $date->year . "/" . $date->month . "/" . $date->day;
            }

            $file_name = $request["invoice_id"] . "." . $file->getClientOriginalExtension();
            $file->move("images/productInvoices/" . $path, $file_name);
            $path_and_file_name = Str::replace("/", "#", $path . "/" . $file_name);
        } elseif ($uploaded_file = glob(public_path("images/temp_images/invoice_$request[invoice_id].*")) and $request["upload_image_method"] == "qr") {
           if (file_exists(public_path($oldPath)) and $oldPath != "images/systemImages/default_invoice_img.png")
               unlink(public_path($oldPath));
            $splitedPath = explode("/", $oldPath);
            if (count($splitedPath) == 6) {
                $path = $splitedPath[2] . "/" . $splitedPath[3] . "/" . $splitedPath[4];
            } else {
                $date = Journal::where("invoice_id", $request["invoice_id"])->whereIn("invoice_type", [0, 1, 2, 3, 4])->first()->closing_date;
                $path = $date->year . "/" . $date->month . "/" . $date->day;
            }
            $extention = File::extension($uploaded_file[0]);
            $file_name = $request["invoice_id"] . "." . $extention;
            File::ensureDirectoryExists("images/productInvoices/$path");
            File::move(public_path("images/temp_images/invoice_$request[invoice_id].$extention"),public_path("images/productInvoices/" . $path . "/" . $file_name));
            $path_and_file_name = Str::replace("/", "#", $path . "/" . $file_name);
        } else {
            $oldPath = Str::replace("images/","",$oldPath);
            $oldPath = Str::replace("productInvoices/","",$oldPath);
            $oldPath = Str::replace("systemImages/","",$oldPath);
            $path_and_file_name = Str::replace("/","#",$oldPath);
        }

        try {
            DB::beginTransaction();

            $num_for_pound_temp = Journal::where("detail", 0)->whereIn("invoice_type", [0, 1, 2, 3, 4])->where("invoice_id", $invoice_id)->first()->num_for_pound;

            Journal::where("detail", 0)->whereIn("invoice_type", [0, 1, 2, 3, 4])->where("invoice_id", $invoice_id)->forceDelete();

            $result = $this->saveJournal($request, $invoice_type, $path_and_file_name);


            if ($result == "no_item_error"){
                globalFunctions::flashMessage("update","no_item_error",$invoice_type . "_invoice");
                DB::rollBack();
            } else {
                $new_invoice = Journal::where("detail", 0)->whereIn("invoice_type", [0, 1, 2, 3, 4])->where("invoice_id", $invoice_id);
                if ($num_for_pound_temp > 1 && $new_invoice->first()->num_for_pound > 1) {
                    foreach ($new_invoice->get() as $invoice) {
                        $invoice->num_for_pound = $num_for_pound_temp;
                        $invoice->save();
                    }
                }
                globalFunctions::flashMessage("update",true,$invoice_type . "_invoice");
                globalFunctions::registerUserActivityLog("update",$invoice_type . "_invoice",$result);
                DB::commit();
            }
        } catch (\PDOException $e)
        {
            DB::rollBack();
            globalFunctions::flashMessage("update",false,$invoice_type . "_invoice");
        }


//        $new_invoice = Journal::where("detail", 0)->whereIn("invoice_type", [0, 1, 2, 3, 4])->where("invoice_id", $invoice_id);
//        dd($new_invoice->get());
//        if ($num_for_pound_temp > 1 && $new_invoice->first()->num_for_pound) {
//            $new_invoice->update(["num_for_pound" => $num_for_pound_temp]);
//        }


        return back();
    }


    public function search(Request $request)
    {
        $this->validate($request,
            [
                "invoice_id"=>["required",Rule::exists('journal','invoice_id')->whereIn("invoice_type", [1, 2, 3, 4])->where("deleted_at",null)]
            ]
        );
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function destroy(Request $request,$invoice_id)
    {
        if ($invoice_id > 0) {
            $image = Journal::withTrashed()->where("detail", 0)->whereIn("invoice_type", [0, 1, 2, 3, 4])->where("invoice_id", $invoice_id)->first()->image;
            $result = Journal::withTrashed()->where("detail", 0)->whereIn("invoice_type", [0, 1, 2, 3, 4])->where("invoice_id", $invoice_id)->forceDelete();
            if ($result != null) {
                if ($image != "images/systemImages/default_invoice_img.png" and file_exists(public_path($image))) {
                    unlink(public_path($image));
                }
            }
            globalFunctions::registerUserActivityLog("deleted","invoice",$invoice_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            foreach ($ids as $id){
                $image = Journal::withTrashed()->where("detail", 0)->whereIn("invoice_type", [0, 1, 2, 3, 4])->where("invoice_id", $id)->first()->image;
                $result = Journal::withTrashed()->where("detail", 0)->whereIn("invoice_type", [0, 1, 2, 3, 4])->where("invoice_id", $id)->forceDelete();
                if ($result != null) {
                    if ($image != "images/systemImages/default_invoice_img.png" and file_exists(public_path($image))) {
                        unlink(public_path($image));
                    }
                }
                globalFunctions::registerUserActivityLog("deleted","invoice",$id);
            }
        } else {
            $result = null;
        }
        globalFunctions::flashMessage("delete", $result, "invoice");

        if (session("previous_previous_url") != null)
            return redirect(session("previous_previous_url"));
        else
            return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function viewRecyclebin()
    {
        globalFunctions::registerUserActivityLog("seen", "invoices_recyclebin", null);
        $deletedInvoices = Journal::onlyTrashed()->where("detail", 0)->whereIn("invoice_type", [1, 2, 3, 4])->selectRaw("invoice_id,second_part_name,sum(price * quantity) as value")->groupBy(["invoice_id", "second_part_name"])->orderBy("invoice_id")->get();
        return view("invoices.products.recyclebin")->with("deletedInvoices", $deletedInvoices);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Account $account
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function softDelete(Request $request,$invoice_id)
    {
        if ($invoice_id > 0) {
            $result = Journal::where("invoice_id", $invoice_id)->where("detail", 0)->whereIn("invoice_type", [0, 1, 2, 3, 4])->delete();
            globalFunctions::registerUserActivityLog("recycled","invoice",$invoice_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = Journal::whereIn("invoice_id", $ids)->where("detail", 0)->whereIn("invoice_type", [0, 1, 2, 3, 4])->delete();
            foreach ($ids as $id){
                globalFunctions::registerUserActivityLog("recycled","invoice",$id);
            }
        } else {
            $result = null;
        }
        globalFunctions::flashMessage("softDelete", $result, "invoice");
        if (session("previous_previous_url") != null)
            return redirect(session("previous_previous_url"));
        else
            return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request,$invoice_id)
    {
        if ($invoice_id > 0) {
            $result = Journal::onlyTrashed()->where("detail", 0)->whereIn("invoice_type", [0, 1, 2, 3, 4])->where("invoice_id", $invoice_id)->restore();
            globalFunctions::registerUserActivityLog("restored","invoice",$invoice_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = Journal::onlyTrashed()->where("detail", 0)->whereIn("invoice_type", [0, 1, 2, 3, 4])->whereIn("invoice_id", $ids)->restore();
            foreach ($ids as $id){
                globalFunctions::registerUserActivityLog("restored","invoice",$id);
            }
        } else {
            $result = null;
        }

        globalFunctions::flashMessage("restore", $result, "invoice");
        return back();
    }
}

