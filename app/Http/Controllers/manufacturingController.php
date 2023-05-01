<?php


namespace App\Http\Controllers;

use App\functions\globalFunctions;
use App\Models\Account;
use App\Models\Journal;
use App\Models\ManufacturingTemplate;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class manufacturingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        globalFunctions::registerUserActivityLog("seen_all", "manufacturing_actions", null);
        $invoices = Journal::where("detail", 0)->whereIn("invoice_type", [15])->selectRaw("invoice_id,second_part_name,product_name,quantity,price,price * quantity as value")->orderBy("invoice_id")->get();
        return view("invoices.manufacturing.viewInvoices")->with(["invoices" => $invoices]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        File::cleanDirectory("temp_images");
        return view("invoices.manufacturing.createInvoice");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "invoice_id" => ["required"],
            "pound_type" => "required",
            "first_part_name" => ["required", Rule::exists("accounts", "name")->where("deleted_at", null)],
            "product_name" => [Rule::exists("products", "name")->where("deleted_at", null)],
            'file' => ['image', 'mimes:jpg,png,jpeg,gif,svg', "max" => "2048mB"],
            "produced_quantity" => "required",
            "pure_piece_price" => "required",
        ]);

        $file_name = null;
        $path = null;
        $path_and_file_name = null;
        if ($file = $request->file("image") and $request["upload_image_method"] == "image") {
            $splitedDate = explode("-", $request["closing_date"]);
            $path = $splitedDate[0] . "/" . $splitedDate[1] . "/" . $splitedDate[2];
            $file_name = $request["invoice_id"] . "." . $file->getClientOriginalExtension();
            $file->move("images/manufacturingInvoices/" . $path, $file_name);
            $path_and_file_name = Str::replace("/", "#", $path . "/" . $file_name);
        } elseif ($uploaded_file = glob(public_path("images/temp_images/manufacturing_$request[invoice_id].*")) and $request["upload_image_method"] == "qr") {
            $splitedDate = explode("-", $request["closing_date"]);
            $path = $splitedDate[0] . "/" . $splitedDate[1] . "/" . $splitedDate[2];
            $extention = File::extension($uploaded_file[0]);
            $file_name = $request["invoice_id"] . "." . $extention;
            File::ensureDirectoryExists("images/manufacturingInvoices/$path");
            File::move(public_path("images/temp_images/manufacturing_$request[invoice_id].$extention"), public_path("images/manufacturingInvoices/" . $path . "/" . $file_name));
            $path_and_file_name = Str::replace("/", "#", $path . "/" . $file_name);
        } else {
            $file_name = "default_invoice_img.png";
            $path_and_file_name = $file_name;
        }

        try {
            DB::beginTransaction();
            $result = $this->saveJournal($request, $path_and_file_name);
            if ($result == "no_item_error") {
                globalFunctions::flashMessage("create", "no_item_error", "manufacturing_action");
                DB::rollBack();
            } else {
                globalFunctions::flashMessage("create", true, "manufacturing_action");
                globalFunctions::registerUserActivityLog("added", "manufacturing_action", $result);
            }
            DB::commit();
        } catch (\PDOException $e) {
            dd($e);
            DB::rollBack();
            globalFunctions::flashMessage("create", false, "manufacturing_action");
        }
        return back();
    }

    private function saveJournal(Request $request, $file_name)
    {
        $ctr = 1;
        $data = $request->all();
        $template = null;
        $this->validate($request, [
            "product_name" => [
                Rule::exists("products", "name")->where("deleted_at", null),
            ],
        ]);
        $record1 = new Journal();
        $record1["invoice_id"] = $data["invoice_id"];
        $record1["line"] = 1;
        $record1["debit"] = $data["produced_quantity"] * $data["pure_piece_price"];
        $record1["credit"] = 0;
        $record1["first_part_id"] = -1;
        $record1["first_part_name"] = __("global.manufacturing");
        $record1["second_part_id"] = Account::where("name", $data["first_part_name"])->first()->id;
        $record1["second_part_name"] = $data["first_part_name"];
        $record1["product_id"] = Product::where("name", $data["product_name"])->first()->id;
        $record1["product_name"] = $data["product_name"];
        $record1["price"] = $data["pure_piece_price"];
        $record1["quantity"] = $data["produced_quantity"];
        $record1["sum_of_balance"] = $record1["credit"] - $record1["debit"];
        $record1["in_quantity"] = $data["produced_quantity"];
        $record1["pound_type"] = $data["pound_type"];
        $record1["num_for_pound"] = globalFunctions::getEquivalentPoundValue($data["pound_type"]);
        $record1["notes"] = __("global.manufactured_product");
        $record1["invoice_type"] = 15;
        $record1["detail"] = 0;
        $record1["image"] = $file_name;
        $record1["closing_date"] = $data["closing_date"];
        $result1 = $record1->save();

        $record2 = new Journal();
        $record2["invoice_id"] = $data["invoice_id"];
        $record2["line"] = $ctr;
        $record2["debit"] = 0;
        $record2["credit"] = $data["produced_quantity"] * $data["pure_piece_price"];
        $record2["first_part_id"] = Account::where("name", $data["first_part_name"])->first()->id;
        $record2["first_part_name"] = $data["first_part_name"];
        $record2["second_part_id"] = -1;
        $record2["second_part_name"] = __("global.manufacturing");
        $record2["product_id"] = Product::where("name", $data["product_name"])->first()->id;
        $record2["product_name"] = $data["product_name"];
        $record2["price"] = $data["pure_piece_price"];
        $record2["quantity"] = $data["produced_quantity"];
        $record2["sum_of_balance"] = $record2["credit"] - $record2["debit"];
        $record2["pound_type"] = $data["pound_type"];
        $record2["num_for_pound"] = globalFunctions::getEquivalentPoundValue($data["pound_type"]);
        $record2["notes"] = __("global.manufactured_product");
        $record2["invoice_type"] = 16;
        $record2["detail"] = 0;
        $record2["image"] = $file_name;
        $record2["closing_date"] = $data["closing_date"];
        $result2 = $record2->save();
        $gainful_value = (isset($data["gainful_value"])) ? $data["gainful_value"] : 0;
        Product::where("name", $data["product_name"])->update(["price" => $data["pure_piece_price"], "gainful_value" => $gainful_value]);

        if (ManufacturingTemplate::where("name", $data["product_name"])->get()->count() == 0) {
            $template = new ManufacturingTemplate();
            $template->name = $data["product_name"];
            $template->quantity = $data["produced_quantity"];
            $template->price = $data["pure_piece_price"];;
            $result = Product::where('name', $data["product_name"])->first()->productTemplate()->save($template);
        }


        while (count($data) > 12) {
            $result1 = $result2 = null;
            if (isset($data["raw_product_name_" . $ctr])) {
                $this->validate($request,
                    [
                        "raw_product_name_$ctr" => [
                            Rule::exists("products", "name")->where("deleted_at", null),
                            function ($attribute, $value, $fail) use ($ctr, $data) {
                                $balance = Journal::where("product_name", $value)->selectRaw("sum(in_quantity) - sum(out_quantity) as balance")->first()->balance;
                                if ($balance < $data["quantity_$ctr"]) {
                                    $fail(__("messages.the_quantity_is_not_enough", ["attribute" => $data["raw_product_name_$ctr"]]));
                                }
                            }
                        ]
                    ]
                );
                $record1 = new Journal();
                $record1["invoice_id"] = $data["invoice_id"];
                $record1["line"] = $ctr;
                $record1["debit"] = 0;
                $record1["credit"] = $data["total_price_$ctr"];
                $record1["first_part_id"] = Account::where("name", $data["first_part_name"])->first()->id;
                $record1["first_part_name"] = $data["first_part_name"];
                $record1["second_part_id"] = -1;
                $record1["second_part_name"] = __("global.manufacturing");
                $record1["product_id"] = Product::where("name", $data["raw_product_name_" . $ctr])->first()->id;
                $record1["product_name"] = $data["raw_product_name_" . $ctr];
                $record1["price"] = $data["price_" . $ctr];
                $record1["quantity"] = $data["quantity_" . $ctr];
                $record1["sum_of_balance"] = $record1["credit"] - $record1["debit"];
                $record1["out_quantity"] = $data["quantity_" . $ctr];
                $record1["pound_type"] = $data["pound_type"];
                $record1["num_for_pound"] = globalFunctions::getEquivalentPoundValue($data["pound_type"]);
                $record1["notes"] = $data["notes_" . $ctr];
                $record1["invoice_type"] = 13;
                $record1["detail"] = 0;
                $record1["image"] = $file_name;
                $record1["closing_date"] = $data["closing_date"];
                $result1 = $record1->save();

                $record2 = new Journal();
                $record2["invoice_id"] = $data["invoice_id"];
                $record2["line"] = $ctr;
                $record2["debit"] = $data["total_price_$ctr"];
                $record2["credit"] = 0;
                $record2["first_part_id"] = -1;
                $record2["first_part_name"] = __("global.manufacturing");
                $record2["second_part_id"] = Account::where("name", $data["first_part_name"])->first()->id;
                $record2["second_part_name"] = $data["first_part_name"];
                $record2["product_id"] = Product::where("name", $data["raw_product_name_" . $ctr])->first()->id;
                $record2["product_name"] = $data["raw_product_name_" . $ctr];
                $record2["price"] = $data["price_" . $ctr];
                $record2["quantity"] = $data["quantity_" . $ctr];
                $record2["sum_of_balance"] = $record2["credit"] - $record2["debit"];
                $record2["pound_type"] = $data["pound_type"];
                $record2["num_for_pound"] = globalFunctions::getEquivalentPoundValue($data["pound_type"]);
                $record2["notes"] = $data["notes_" . $ctr];
                $record2["invoice_type"] = 14;
                $record2["detail"] = 0;
                $record2["image"] = $file_name;
                $record2["closing_date"] = $data["closing_date"];
                $result2 = $record2->save();

                if ($template) {
                    $template->components()->attach(Product::where("name", $data["raw_product_name_" . $ctr])->first()->id, ["quantity" => $data["quantity_" . $ctr], "price" => $data["price_" . $ctr]]);
                }


                unset($data["raw_product_name_" . $ctr]);
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
        session(["previous_previous_url" => URL::previous()]);
        globalFunctions::registerUserActivityLog("seen", "manufacturing_actions", $invoice_id);
        $invoice_lines = Journal::where("detail", 0)->where("invoice_id", $invoice_id)->whereIn("invoice_type", [13])->orderBy("line")->get();
        $produced_product_lines = Journal::where("detail", 0)->where("invoice_id", $invoice_id)->whereIn("invoice_type", [15])->orderBy("line")->get();
        return view("invoices.manufacturing.showInvoice")->with(["invoiceLines" => $invoice_lines, "producedProductLines" => $produced_product_lines]);
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
    public function update(Request $request, $invoice_id)
    {

        $this->validate($request, [
            "invoice_id" => ["required"],
            "pound_type" => "required",
            "first_part_name" => ["required", Rule::exists("accounts", "name")->where("deleted_at", null)],
            "product_name" => [Rule::exists("products", "name")->where("deleted_at", null)],
            'file' => ['image', 'mimes:jpg,png,jpeg,gif,svg', "max" => "2048mB"],
            "produced_quantity" => "required",
            "pure_piece_price" => "required",
        ]);


        $file_name = null;
        $path = null;
        $path_and_file_name = null;
        $oldPath = Journal::where("detail", 0)->whereIn("invoice_type", [13, 14, 15, 16])->find($invoice_id)->image;
        if ($file = $request->file("image") and $request["upload_image_method"] == "image") {
            if (file_exists(public_path($oldPath)) and $oldPath != "images/systemImages/default_invoice_img.png")
                unlink(public_path($oldPath));
            $splitedPath = explode("/", $oldPath);
            if (count($splitedPath) == 6) {
                $path = $splitedPath[2] . "/" . $splitedPath[3] . "/" . $splitedPath[4];
            } else {
                $date = Journal::where("invoice_id", $request["invoice_id"])->whereIn("invoice_type", [13, 14, 15, 16])->first()->closing_date;
                $path = $date->year . "/" . $date->month . "/" . $date->day;
            }

            $file_name = $request["invoice_id"] . "." . $file->getClientOriginalExtension();
            $file->move("images/manufacturingInvoices/" . $path, $file_name);
            $path_and_file_name = Str::replace("/", "#", $path . "/" . $file_name);
        } elseif ($uploaded_file = glob(public_path("images/temp_images/manufacturing_$request[invoice_id].*")) and $request["upload_image_method"] == "qr") {
            if (file_exists(public_path($oldPath)) and $oldPath != "images/systemImages/default_invoice_img.png")
                unlink(public_path($oldPath));
            $splitedPath = explode("/", $oldPath);
            if (count($splitedPath) == 6) {
                $path = $splitedPath[2] . "/" . $splitedPath[3] . "/" . $splitedPath[4];
            } else {
                $date = Journal::where("invoice_id", $request["invoice_id"])->whereIn("invoice_type", [13, 14, 15, 16])->first()->closing_date;
                $path = $date->year . "/" . $date->month . "/" . $date->day;
            }
            $extention = File::extension($uploaded_file[0]);
            $file_name = $request["invoice_id"] . "." . $extention;
            File::ensureDirectoryExists("images/manufacturingInvoices/$path");
            File::move(public_path("images/temp_images/manufacturing_$request[invoice_id].$extention"), public_path("images/manufacturingInvoices/" . $path . "/" . $file_name));
            $path_and_file_name = Str::replace("/", "#", $path . "/" . $file_name);
        } else {
            $oldPath = Str::replace("images/", "", $oldPath);
            $oldPath = Str::replace("manufacturingInvoices/", "", $oldPath);
            $oldPath = Str::replace("systemImages/", "", $oldPath);
            $path_and_file_name = Str::replace("/", "#", $oldPath);
        }

        try {
            DB::beginTransaction();

            $num_for_pound_temp = Journal::where("detail", 0)->whereIn("invoice_type", [13, 14, 15, 16])->where("invoice_id", $invoice_id)->first()->num_for_pound;

            Journal::where("detail", 0)->whereIn("invoice_type", [13, 14, 15, 16])->where("invoice_id", $invoice_id)->forceDelete();
            $result = $this->saveJournal($request, $path_and_file_name);
            if ($result == "no_item_error") {
                globalFunctions::flashMessage("update", "no_item_error", "manufacturing_action");
                DB::rollBack();
            } else {
                $new_invoice = Journal::where("detail", 0)->whereIn("invoice_type", [13, 14, 15, 16])->where("invoice_id", $invoice_id);
                if ($num_for_pound_temp > 1 && $new_invoice->first()->num_for_pound > 1) {
                    foreach ($new_invoice->get() as $invoice) {
                        $invoice->num_for_pound = $num_for_pound_temp;
                        $invoice->save();
                    }
                }
                globalFunctions::flashMessage("update", true, "manufacturing_action");
                globalFunctions::registerUserActivityLog("update", "manufacturing_action", $result);
                DB::commit();
            }
        } catch (\PDOException $e) {
            DB::rollBack();
            globalFunctions::flashMessage("update", false, "manufacturing_action");
        }

        return back();
    }


    public function search(Request $request)
    {
        $this->validate($request,
            [
                "invoice_id" => ["required", Rule::exists('journal', 'invoice_id')->whereIn("invoice_type", [13, 14, 15, 16])->where("deleted_at", null)]
            ]
        );
        globalFunctions::registerUserActivityLog("searched", "manufacturing_action", $request["invoice_id"]);
        return redirect(route("invoice.showManufacturingInvoice", $request["invoice_id"]));
    }

    public function showSearchInvoice()
    {
        return view("invoices.manufacturing.showInvoice")->with(["invoiceLines" => []]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function destroy(Request $request, $invoice_id)
    {
        if ($invoice_id > 0) {
            $image = Journal::withTrashed()->where("detail", 0)->whereIn("invoice_type", [13, 14, 15, 16])->where("invoice_id", $invoice_id)->first()->image;
            $result = Journal::withTrashed()->where("detail", 0)->whereIn("invoice_type", [13, 14, 15, 16])->where("invoice_id", $invoice_id)->forceDelete();
            if ($result != null) {
                if ($image != "images/systemImages/default_invoice_img.png" and file_exists(public_path($image))) {
                    unlink(public_path($image));
                }
            }
            globalFunctions::registerUserActivityLog("deleted", "manufacturing_action", $invoice_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            foreach ($ids as $id) {
                $image = Journal::withTrashed()->where("detail", 0)->whereIn("invoice_type", [13, 14, 15, 16])->where("invoice_id", $id)->first()->image;
                $result = Journal::withTrashed()->where("detail", 0)->whereIn("invoice_type", [13, 14, 15, 16])->where("invoice_id", $id)->forceDelete();
                if ($result != null) {
                    if ($image != "images/systemImages/default_invoice_img.png" and file_exists(public_path($image))) {
                        unlink(public_path($image));
                    }
                }
                globalFunctions::registerUserActivityLog("deleted", "manufacturing_action", $id);
            }
        } else {
            $result = null;
        }
        globalFunctions::flashMessage("delete", $result, "manufacturing_action");

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
        globalFunctions::registerUserActivityLog("seen", "manufacturing_actions_recyclebin", null);
        $deletedInvoices = Journal::onlyTrashed()->where("detail", 0)->whereIn("invoice_type", [15])->selectRaw("invoice_id,second_part_name,product_name,quantity,price,price * quantity as value")->orderBy("invoice_id")->get();
        return view("invoices.manufacturing.recyclebin")->with("deletedInvoices", $deletedInvoices);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Account $account
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function softDelete(Request $request, $invoice_id)
    {
        if ($invoice_id > 0) {
            $result = Journal::where("invoice_id", $invoice_id)->where("detail", 0)->whereIn("invoice_type", [13, 14, 15, 16])->delete();
            globalFunctions::registerUserActivityLog("recycled", "manufacturing_action", $invoice_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = Journal::whereIn("invoice_id", $ids)->where("detail", 0)->whereIn("invoice_type", [13, 14, 15, 16])->delete();
            foreach ($ids as $id) {
                globalFunctions::registerUserActivityLog("recycled", "manufacturing_action", $id);
            }
        } else {
            $result = null;
        }
        globalFunctions::flashMessage("softDelete", $result, "manufacturing_action");
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
    public function restore(Request $request, $invoice_id)
    {
        if ($invoice_id > 0) {
            $result = Journal::onlyTrashed()->where("detail", 0)->whereIn("invoice_type", [13, 14, 15, 16])->where("invoice_id", $invoice_id)->restore();
            globalFunctions::registerUserActivityLog("restored", "manufacturing_action", $invoice_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = Journal::onlyTrashed()->where("detail", 0)->whereIn("invoice_type", [13, 14, 15, 16])->whereIn("invoice_id", $ids)->restore();
            foreach ($ids as $id) {
                globalFunctions::registerUserActivityLog("restored", "manufacturing_action", $id);
            }
        } else {
            $result = null;
        }

        globalFunctions::flashMessage("restore", $result, "manufacturing_action");
        return back();
    }
}

