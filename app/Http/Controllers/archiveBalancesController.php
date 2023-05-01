<?php

namespace App\Http\Controllers;

use App\functions\globalFunctions;
use App\Models\Journal;
use App\Models\OldJournal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class archiveBalancesController extends Controller
{
    public function viewArchiveBalances(){
        return view("admin.archiveBalances.archiveBalances");
    }

    public function archiveBalances(Request $request){
        $result = true;
        try {
            Artisan::call("backup:run");
            DB::beginTransaction();
            DB::statement("delete from old_journal");
            DB::statement("insert into old_journal select * from journal");
            $account_balances_syrian = Journal::selectRaw("first_part_id,first_part_name,sum(debit) as debit , sum(credit) as credit , pound_type")->groupBy(["first_part_name","first_part_id", "pound_type"])->where("num_for_pound",1)->where("first_part_id","!=",0)->get();
            $account_balances_dollar = Journal::selectRaw("first_part_id,first_part_name,sum(debit) as debit , sum(credit) as credit,sum((debit + credit) / num_for_pound) as dollar_num , pound_type")->groupBy(["first_part_name","first_part_id", "pound_type"])->where("num_for_pound",">",1)->where("first_part_id","!=",0)->get();
//            dd($account_balances_syrian,$account_balances_dollar);
            $product_balances = Journal::selectRaw("product_id,product_name,sum(in_quantity) as in_quantity , sum(out_quantity) as out_quantity")->groupBy(["product_id","product_name"])->get();
            DB::statement("delete from journal");
            foreach ($account_balances_syrian as $balance){
                if ($balance->debit != $balance->credit){
                    $blns = new Journal();
                    $blns->invoice_id = 0;
                    if ($balance->credit > $balance->debit ){
                        $blns->credit = $balance->credit - $balance->debit;
                        $blns->debit = 0;
                    } else {
                        $blns->debit = $balance->debit - $balance->credit;
                        $blns->credit = 0;
                    }
                    $blns->sum_of_balance = $balance->credit - $balance->debit;
                    $blns->first_part_id = $balance->first_part_id;
                    $blns->first_part_name = $balance->first_part_name;
                    $blns->pound_type = $balance->pound_type;
                    $blns->num_for_pound = 1;
                    $blns->detail = -1;
                    $blns->invoice_type = -1;
                    $blns->created_at = $request["date"];
                    $blns->closing_date = $request["date"];
                    $blns->notes = __("global.roled_balance");
                    $blns->save();
                }

            }
            foreach ($account_balances_dollar as $balance){
                if ($balance->debit != $balance->credit){
                    $blns = new Journal();
                    $blns->invoice_id = 0;
                    if ($balance->credit > $balance->debit ){
                        $blns->credit = $balance->credit - $balance->debit;
                        $blns->debit = 0;
                    } else {
                        $blns->debit = $balance->debit - $balance->credit;
                        $blns->credit = 0;
                    }
                    $blns->sum_of_balance = $balance->credit - $balance->debit;
                    $blns->first_part_id = $balance->first_part_id;
                    $blns->first_part_name = $balance->first_part_name;
                    $blns->pound_type = $balance->pound_type;
                    $blns->num_for_pound = ($balance->debit + $balance->credit) / $balance->dollar_num;
                    $blns->detail = -1;
                    $blns->invoice_type = -1;
                    $blns->created_at = $request["date"];
                    $blns->closing_date = $request["date"];
                    $blns->notes = __("global.roled_balance");
                    $blns->save();
                }

            }
            foreach ($product_balances as $balance){
                if ($balance->in_quantity != $balance->out_quantity){
                    $blns = new Journal();
                    $blns->invoice_id = 0;
                    if ($balance->in_quantity > $balance->out_quantity ){
                        $blns->in_quantity = $balance->in_quantity - $balance->out_quantity;
                        $blns->out_quantity = 0;
                    } else {
                        $blns->out_quantity = $balance->out_quantity - $balance->in_quantity;
                        $blns->in_quantity = 0;
                    }

                    $blns->quantity = $balance->in_quantity - $balance->out_quantity;
                    $blns->product_id = $balance->product_id;
                    $blns->product_name = $balance->product_name;
                    $blns->detail = -1;
                    $blns->invoice_type = -1;
                    $blns->created_at = $request["date"];
                    $blns->closing_date = $request["date"];
                    $blns->notes = __("global.product_of_begin_and_end");
                    $blns->save();
                }

            }
            DB::commit();
        } catch (\PDOException $e) {
            DB::rollBack();
            $result = null;
        }

        globalFunctions::flashMessage("archive",$result,"balances");
        globalFunctions::registerUserActivityLog("archived","balances",null);
        return back();
    }
}
