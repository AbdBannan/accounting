<?php

namespace App\Http\Controllers;

use App\functions\globalFunctions;
use App\Models\Journal;
use App\Models\OldJournal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class archiveBalancesController extends Controller
{
    public function viewArchiveBalances(){
        return view("admin.archiveBalances.archiveBalances");
    }

    public function archiveBalances(Request $request){

        $result = true;
        try {
            DB::beginTransaction();
            $original_balances = Journal::where("created_at","<",Carbon::now())->get();
            $balances = Journal::selectRaw("first_part_id,first_part_name,sum(debit) as debit , sum(credit) as credit")->where("created_at","<",Carbon::now())->groupBy(["first_part_name","first_part_id"])->get();
            Journal::where("created_at","<",Carbon::now())->forceDelete();


            foreach ($balances as $balance){
                $blns = new Journal();
                $blns->invoice_id = 0;
                if ($balance->credit > $balance->debit ){
                    $blns->credit = $balance->credit - $balance->debit;
                    $blns->debit = 0;
                } else {
                    $blns->debit = $balance->debit - $balance->credit;
                    $blns->credit = 0;
                }

                $blns->first_part_id = $balance->first_part_id;
                $blns->first_part_name = $balance->first_part_name;
                $blns->pound_type = __("global.Syrian",[],session("lang"));
                $blns->num_for_pound = 1;
                $blns->detail = 1;
                $blns->invoice_type = 0;
                $blns->created_at = $request["date"];
//                $blns->closing_date = $request["date"];
                $blns->notes = __("global.roled_balance",[],session("lang"));
                $blns->save();
            }

            foreach ($original_balances as $balance) {
                OldJournal::create($balance->getAttributes());
            }
            DB::commit();
        } catch (\PDOException $e) {
            DB::rollBack();
            dd($e->getMessage());
            $result = null;
        }

        globalFunctions::flashMessage("archive",$result,"balances");
        globalFunctions::registerUserActivityLog("archived","balances",null);
        return back();
    }
}
