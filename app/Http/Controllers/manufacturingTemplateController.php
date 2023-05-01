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
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class manufacturingTemplateController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            "product_name" => [Rule::exists("products","name")->where("deleted_at",null)],
            "produced_quantity" => "required",
            "pure_piece_price" => "required",
        ]);
        try {
            DB::beginTransaction();
            $result = $this->saveJournal($request);
            if ($result == "no_item_error"){
                DB::rollBack();
                globalFunctions::flashMessage("create","no_item_error","manufacturing_template");
            } else {
                globalFunctions::flashMessage("create",true, "manufacturing_template");
                globalFunctions::registerUserActivityLog("added","manufacturing_template",$result);
            }
            DB::commit();
        } catch (\PDOException $e)
        {
            DB::rollBack();
            globalFunctions::flashMessage("create",false,"manufacturing_template");
        }
        return back();
    }

    private function saveJournal(Request $request)
    {
        $ctr = 1;
        $data = $request->all();
        $template = null;

        if (ManufacturingTemplate::where("name",$data["product_name"])->get()->count() > 0){
            $template = ManufacturingTemplate::where("name",$data["product_name"])->get();
            $template->first()->forceDelete();
        }
        $template = new ManufacturingTemplate();
        $template->name = $data["product_name"];
        $template->quantity = $data["produced_quantity"];
        $template->price = $data["pure_piece_price"];;
        $result = Product::where('name',$data["product_name"])->first()->productTemplate()->save($template);


        while (count($data) > 10 and $template) {
            $result1 = $result2 = null;
            if (isset($data["raw_product_name_" . $ctr])) {

                $this->validate($request,
                    [
                        "name"=>Rule::exists("products")->where("deleted_at",null)
                    ]);

                $template->components()->attach(Product::where("name", $data["raw_product_name_" . $ctr])->first()->id,["quantity"=>$data["quantity_" . $ctr],"price"=>$data["price_" . $ctr]]);


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

        return $template["id"];
    }

    public function destroy(Request $request,$template_id)
    {
        if ($template_id > 0) {
            $result = ManufacturingTemplate::withTrashed()->find($template_id)->forceDelete();
            globalFunctions::registerUserActivityLog("deleted","manufacturing_template",$template_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = ManufacturingTemplate::withTrashed()->whereIn("id", $ids)->forceDelete();
            foreach ($ids as $id){
                globalFunctions::registerUserActivityLog("deleted","manufacturing_template",$id);
            }
        } else {
            $result = null;
        }
        globalFunctions::flashMessage("delete", $result, "manufacturing_template");
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function viewRecyclebin()
    {
        globalFunctions::registerUserActivityLog("seen", "manufacturing_templates_recyclebin", null);
        $deletedTemplates = ManufacturingTemplate::onlyTrashed()->get();
        return view("invoices.manufacturing.manufacturingTemplatesRecyclebin")->with("deletedTemplates", $deletedTemplates);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Account $account
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function softDelete(Request $request,$template_id)
    {
        if ($template_id > 0) {
            $result = ManufacturingTemplate::find($template_id)->delete();
            globalFunctions::registerUserActivityLog("recycled","manufacturing_template",$template_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = ManufacturingTemplate::whereIn("id", $ids)->delete();
            foreach ($ids as $id){
                globalFunctions::registerUserActivityLog("recycled","manufacturing_template",$id);
            }
        } else {
            $result = null;
        }
        globalFunctions::flashMessage("softDelete", $result, "manufacturing_template");

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request,$template_id)
    {
        if ($template_id > 0) {
            $result = ManufacturingTemplate::onlyTrashed()->find($template_id)->restore();
            globalFunctions::registerUserActivityLog("restored","manufacturing_template",$template_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = ManufacturingTemplate::onlyTrashed()->whereIn("id", $ids)->restore();
            foreach ($ids as $id){
                globalFunctions::registerUserActivityLog("restored","manufacturing_template",$id);
            }
        } else {
            $result = null;
        }

        globalFunctions::flashMessage("restore", $result, "manufacturing_template");
        return back();
    }
}
