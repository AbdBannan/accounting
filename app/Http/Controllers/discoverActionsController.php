<?php

namespace App\Http\Controllers;

use App\functions\globalFunctions;
use App\Models\Account;
use App\Models\Journal;
use App\Models\OldJournal;
use App\Models\Product;
use App\Models\Store;
use Carbon\Carbon;
use Cassandra\Date;
use DateTime;
use Illuminate\Http\Request;
use function Sodium\add;

class discoverActionsController extends Controller
{
    public function showDiscoverDashboard(){
        return view("admin.discoverActions.discoverDashboard");
    }

    public function chooseListGlobalDiscover(){
        return view("admin.discoverActions.globalDiscover")->with("actions",null);
    }

    public function globalDiscoverUntilNow(Request $request){
        $this->validate($request,
            [
               "account"=>"required"
            ]
        );
        $actions = Journal::where("first_part_name",$request["account"])->orderBy("closing_date")->orderBy("invoice_id")->orderBy("row_id")->get();
        $totals = Journal::where("first_part_name",$request["account"])->selectRaw("sum(debit) as total_debit , sum(credit) as total_credit")->first();
        $total_credit = $totals->total_credit;
        $total_debit = $totals->total_debit;
//        $total_credit = 0;
//        $total_debit = 0;
//        foreach($actions as $action){
//            $total_credit +=$action->credit;
//            $total_debit +=$action->debit;
//        }
        globalFunctions::registerUserActivityLog("discovered","global_discover_until_now",Account::where("name",$request["account"])->first()->id);
        return view("admin.discoverActions.globalDiscover")->with(["actions"=>$actions,"total_credit"=>$total_credit,"total_debit"=>$total_debit,"account_name"=>$request["account"]]);
    }

    public function globalDiscoverBetweenTowDates(Request $request){
        $this->validate($request,
            [
                "account"=>"required",
                "from"=>"required",
                "to"=>"required",
            ]
        );
        if ($request["from"]>$request["to"]){
            globalFunctions::flashMessage("discover","date_not_correct","");
            return back();
        }
        $start_date =  new DateTime($request["from"]);
        $start_date = $start_date->format("Y-m-d");
//        $start_date = $start_date->format("Y-m-d h:m:s");

        $end_date = new DateTime($request["to"]);
        $end_date = $end_date->format("Y-m-d");
        $actions = Journal::where("first_part_name",$request["account"])->whereBetween("closing_date",[$start_date,$end_date])->orderBy("closing_date")->orderBy("invoice_id")->orderBy("row_id")->get();
        $totals = Journal::where("first_part_name",$request["account"])->whereBetween("closing_date",[$start_date,$end_date])->selectRaw("sum(debit) as total_debit , sum(credit) as total_credit")->first();
        $total_credit = $totals->total_credit;
        $total_debit = $totals->total_debit;
//        $total_credit = 0;
//        $total_debit = 0;
//        foreach($actions as $action){
//            $total_credit +=$action->credit;
//            $total_debit +=$action->debit;
//        }
//        dd($total_debit,$total_credit);
        globalFunctions::registerUserActivityLog("discovered","global_discover_between_tow_dates",Account::where("name",$request["account"])->first()->id);
        return view("admin.discoverActions.globalDiscover")->with(["actions"=>$actions,"start_date"=>$request["from"],"end_date"=>$request["to"],"total_credit"=>$total_credit,"total_debit"=>$total_debit,"account_name"=>$request["account"]]);
    }

    public function globalDiscoverAfterLastCheckedPoint(Request $request)
    {
        $this->validate($request,
            [
                "account"=>"required"
            ]
        );
//        $row_id = Journal::where("first_part_name",$request["account"])->orderBy("closing_date","desc")->orderBy("row_id","desc")->firs()->row_id;// finding the index of last check point

        $actions = Journal::where("first_part_name",$request["account"])->orderBy("closing_date")->orderBy("invoice_id")->orderBy("row_id")->get();
        $index_of_last_check_point = -1;
        foreach ($actions as $key=>$action){// finding the index of last check point
            if ($action->equivalent == 1)
                $index_of_last_check_point = $key;
        }

        if ($index_of_last_check_point != -1){
            $roled_debit = 0;
            $roled_credit = 0;
            foreach ($actions as $key=>$action) { // finding the sum of roled records and removing it for the collection
                if ($key <= $index_of_last_check_point){
                    $roled_debit += $action->debit;
                    $roled_credit += $action->credit;
                    $actions->forget($key);
                }
            }

            $roled_line = new Journal();
            $roled_line->debit = $roled_debit;
            $roled_line->credit = $roled_credit;
            $roled_line->notes = __("global.checked");
            $roled_line->closing_date = new DateTime("01-01-0001");
            $roled_line->invoice_type = -1; // means checked point


            $actions->add($roled_line);
        }

        $actions = $actions->sortBy("closing_date")->sortBy("invoice_id")->sortBy("row_id");
//        $invoice_id_for_last_checked_point = $invoice->invoice_id;
//        $line_for_last_checked_point = $invoice->line;
//        $roled_balance = Journal::where("first_part_name",$request["account"])->where("invoice_id","<=",$invoice_id_for_last_checked_point)->where("line","<=",$line_for_last_checked_point)->selectRaw("sum(debit) as debit , sum(credit) as credit")->first();
//        dd($invoice,$invoice_id_for_last_checked_point,$line_for_last_checked_point,$roled_balance->debit,$roled_balance->credit);
//        $actions = Journal::where("first_part_name",$request["account"])->where("invoice_id",">",$invoice_id_for_last_checked_point)->where("line",">",$line_for_last_checked_point)->get();
//        $roled_line = new Journal();
//        $roled_line->debit = $roled_balance->debit;
//        $roled_line->credit = $roled_balance->credit;
//        $roled_line->notes = __("global.roled");
//        $roled_line->closing_date = Carbon::now();
//
//        $actions->add($roled_line);
//        dd($actions);
        $total_credit = 0;
        $total_debit = 0;
        foreach($actions as $action){
            $total_credit +=$action->credit;
            $total_debit +=$action->debit;
        }
        globalFunctions::registerUserActivityLog("discovered","global_discover_after_last_checked_point",Account::where("name",$request["account"])->first()->id);
        return view("admin.discoverActions.globalDiscover")->with(["actions"=>$actions,"start_date"=>$request["from"],"end_date"=>$request["to"],"total_credit"=>$total_credit,"total_debit"=>$total_debit,"account_name"=>$request["account"]]);
    }

    public function globalDiscoverUntilLastBalance(Request $request)
    {
        $this->validate($request,
            [
                "account"=>"required"
            ]
        );
        $actions = Journal::where("first_part_name",$request["account"])->orderBy("closing_date")->orderBy("invoice_id")->orderBy("row_id")->get();
        $total_credit = 0;
        $total_debit = 0;
        $index_of_last_balanced_debit_with_credit = -1;
        foreach($actions as $key=>$action){
            $total_credit += $action->credit;
            $total_debit += $action->debit;
            if ($total_credit == $total_debit)
                $index_of_last_balanced_debit_with_credit = $key;
        }

        if ($index_of_last_balanced_debit_with_credit != -1){
            $total_credit = 0;
            $total_debit = 0;
            foreach($actions as $key=>$action) {
                if ($key<=$index_of_last_balanced_debit_with_credit)
                    $actions->forget($key);
                else{
                    $total_credit +=$action->credit;
                    $total_debit +=$action->debit;
                }
            }
        }
        globalFunctions::registerUserActivityLog("discovered","global_discover_until_last_balance",Account::where("name",$request["account"])->first()->id);
        return view("admin.discoverActions.globalDiscover")->with(["actions"=>$actions,"total_credit"=>$total_credit,"total_debit"=>$total_debit,"account_name"=>$request["account"]]);
    }

//
//    public function globalDiscoverByAccount(Request $request)
//    {
//        $this->validate($request,
//            [
//                "account_1"=>"required",
//                "account_2"=>"required"
//            ]
//        );
//
//        $account_1_id = Account::where("name",$request["account_1"])->first()->id;
//        $accounts_2_id = Account::where("name",$request["account_2"])->first()->id+1;
//        if ($account_1_id >$accounts_2_id){
//            session()->flash("error",__("messages.second_greater_than_first"));
//            return back();
//        }
//        $balances = Journal::whereBetween("first_part_id",[$account_1_id,$accounts_2_id])->selectRaw("first_part_name,sum(debit) as debit,sum(credit) as credit")->groupBy(["first_part_name"])->orderBy("first_part_name")->get();
//
//        $maxs = Journal::whereBetween("first_part_id",[$account_1_id,$accounts_2_id])->selectRaw("first_part_name,max(closing_date) max_closing_date")->groupBy(["first_part_name"])->orderBy("first_part_name")->get();
////        $max_row_id = [];
//        dd($maxs);
//        foreach ($maxs as $key=>$max){
//            $information = Journal::selectRaw("first_part_name,closing_date,credit+debit last_action")->where("first_part_name",$max->first_part_name)->where("row_id",$max->max_row_id)->first();
//            $info[] = [
//                "account_name"=>$information->first_part_name,
//                "date"=>$information->closing_date,
//                "balance"=>$balances[$key]->credit-$balances[$key]->debit,
//                "last_cash_action"=>$information->last_action,
//                "percentage"=>round(100*$balances[$key]->credit/$balances[$key]->debit),
//            ];
//        }
////        $information = Journal::selectRaw("first_part_name,closing_date,credit+debit last_action")->whereBetween("first_part_id",[$account_1_id,$accounts_2_id])->whereIn("row_id",$max_row_id)->orderBy("first_part_name")->get();
//        $total_debit_credit = Journal::whereBetween("first_part_id",[$account_1_id,$accounts_2_id])->selectRaw("sum(debit) as debit,sum(credit) as credit")->get()->all();
////        $info = [];
//////        dd($information,$balances);
////        foreach ($balances as $key=>$value){
////            $info[] = [
////                "account_name"=>$information[$key]->first_part_name,
////                "date"=>$information[$key]->closing_date,
////                "balance"=>$value->credit-$value->debit,
////                "last_cash_action"=>$information[$key]->last_action,
////                "percentage"=>round(100*$value->credit/$value->debit),
////            ];
////        }
//        return view("admin.discoverActions.specifiedAccountsDiscover")->with(["info"=>$info,"total_debit"=>$total_debit_credit[0]["debit"],"total_credit"=>$total_debit_credit[0]  ["credit"]]);
//    }

    public function globalDiscoverByAccount(Request $request)
    {
        abort(403,"we are so sorry this page has stopped because of maintenance");
        $this->validate($request,
            [
                "account_1"=>"required",
                "account_2"=>"required"
            ]
        );
        $account_1_id = Account::where("name",$request["account_1"])->first()->id;
        $account_2_id = Account::where("name",$request["account_2"])->first()->id;
        if ($account_1_id > $account_2_id){
            session()->flash("error",__("messages.second_greater_than_first"));
            return back();
        }
//        dd($request->all());
        $balances = Journal::whereBetween("first_part_id",[$account_1_id,$account_2_id])->selectRaw("first_part_name,sum(debit) as debit,sum(credit) as credit")->groupBy(["first_part_name"])->orderBy("first_part_name")->get();
//        $associative_balances = [];
//        foreach ($balances as $value){
//            $associative_balances[$value->first_part_id] = [$value->credit,$value->debit];
//        }
//        dd($associative_balances);
        $max_closing_date = Journal::whereBetween("first_part_id",[$account_1_id,$account_2_id])->selectRaw("first_part_id,max(closing_date) max_closing_date")->groupBy(["first_part_id"])->get();
        $max_row_id = [];
        foreach ($max_closing_date as $m_c_d){
            $max_row_id[] = Journal::where("first_part_id",$m_c_d->first_part_id)->selectRaw("first_part_name,max(row_id) max_row_id")->groupBy(["first_part_name"])->where("closing_date",$m_c_d->max_closing_date)->first()->max_row_id;
        }
        $information = Journal::selectRaw("first_part_name,closing_date,credit+debit last_action")->whereBetween("first_part_id",[$account_1_id,$account_2_id])->whereIn("row_id",$max_row_id)->orderBy("first_part_name")->get();
        $total_debit_credit = Journal::whereBetween("first_part_id",[$account_1_id,$account_2_id])->selectRaw("sum(debit) as debit,sum(credit) as credit")->get()->all();
        $info = [];
//        dd($information,$balances);
        // todo : there is an error that is dividing by zero
        foreach ($balances as $key=>$value){
            $info[] = [
                "account_name"=>$information[$key]->first_part_name,
                "date"=>$information[$key]->closing_date,
                "balance"=>$value->credit-$value->debit,
                "last_cash_action"=>$information[$key]->last_action,
                "percentage"=>round(100*$value->credit/$value->debit),
            ];
        }
        globalFunctions::registerUserActivityLog("discovered","global_discover_by_account",$account_1_id . " -> " . $account_2_id);
        return view("admin.discoverActions.specifiedAccountsDiscover")->with(["info"=>$info,"total_debit"=>$total_debit_credit[0]["debit"],"total_credit"=>$total_debit_credit[0]["credit"]]);
    }

    public function makeCheckPoint(Request $request){
        $result = Journal::where("row_id",$request["check_point_row_id"])->update(["equivalent"=>1]);
        if ($result==1) {
            session()->flash("success",__("messages.created_successfully",["attribute"=>__("global.check_point")]));
        } else {
            session()->flash("error",__("messages.not_created_successfully",["attribute"=>__("global.check_point")]));
        }
        globalFunctions::registerUserActivityLog("made","check_point",$request["check_point_row_id"]);
        return back();
    }


    public function chooseListCashDiscover(){
        return view("admin.discoverActions.cashDiscover")->with("actions",null);
    }

    public function cashDiscoverUntilNow(Request $request){
        $this->validate($request,
            [
                "account"=>"required"
            ]
        );
        $actions = Journal::where("first_part_name",$request["account"])->orderBy("closing_date")->orderBy("invoice_id")->orderBy("row_id")->get();
        $totals = Journal::where("first_part_name",$request["account"])->selectRaw("sum(debit / num_for_pound) as total_debit , sum(credit / num_for_pound) as total_credit")->whereNotIn("num_for_pound",[0,1])->first();
        $total_credit = $totals->total_credit;
        $total_debit = $totals->total_debit;

//        $total_credit = 0;
//        $total_debit = 0;
//        foreach($actions as $action){
//            if (!in_array($action->num_for_pound,[0,1])) {
//                $total_credit +=$action->credit / $action->num_for_pound ;
//                $total_debit +=$action->debit / $action->num_for_pound;
//            }
//        }
        globalFunctions::registerUserActivityLog("discovered","cash_discover_until_now",Account::where("name",$request["account"])->first()->id);
        return view("admin.discoverActions.cashDiscover")->with(["actions"=>$actions,"total_credit"=>$total_credit,"total_debit"=>$total_debit,"account_name"=>$request["account"]]);
    }

    public function cashDiscoverBetweenTowDates(Request $request){

        $this->validate($request,
            [
                "account"=>"required",
                "from"=>"required",
                "to"=>"required",
            ]
        );
        if ($request["from"]>$request["to"]){
            globalFunctions::flashMessage("discover","date_not_correct","");
            return back();
        }
        $start_date =  new DateTime($request["from"]);
        $start_date = $start_date->format("Y-m-d");
//        $start_date = $start_date->format("Y-m-d h:m:s");

        $end_date = new DateTime($request["to"]);
        $end_date = $end_date->format("Y-m-d");
        $actions = Journal::where("first_part_name",$request["account"])->whereBetween("closing_date",[$start_date,$end_date])->orderBy("closing_date")->orderBy("invoice_id")->orderBy("row_id")->get();
        $totals = Journal::where("first_part_name",$request["account"])->whereBetween("closing_date",[$start_date,$end_date])->selectRaw("sum(debit / num_for_pound) as total_debit , sum(credit / num_for_pound) as total_credit")->whereNotIn("num_for_pound",[0,1])->first();
        $total_credit = $totals->total_credit;
        $total_debit = $totals->total_debit;

//        $total_credit = 0;
//        $total_debit = 0;
//        foreach($actions as $action){
//            if (!in_array($action->num_for_pound,[0,1])) {
//                $total_credit +=$action->credit / $action->num_for_pound;
//                $total_debit +=$action->debit / $action->num_for_pound;
//            }
//        }
        globalFunctions::registerUserActivityLog("discovered","cash_discover_between_tow_dates",Account::where("name",$request["account"])->first()->id);
        return view("admin.discoverActions.cashDiscover")->with(["actions"=>$actions,"start_date"=>$request["from"],"end_date"=>$request["to"],"total_credit"=>$total_credit,"total_debit"=>$total_debit,"account_name"=>$request["account"]]);
    }

    public function cashDiscoverAfterLastCheckedPoint(Request $request)
    {
        $this->validate($request,
            [
                "account"=>"required"
            ]
        );
//        $row_id = Journal::where("first_part_name",$request["account"])->orderBy("closing_date","desc")->orderBy("row_id","desc")->firs()->row_id;// finding the index of last check point

        $actions = Journal::where("first_part_name",$request["account"])->orderBy("closing_date")->orderBy("invoice_id")->orderBy("row_id")->get();
        $index_of_last_check_point = -1;
        foreach ($actions as $key=>$action){// finding the index of last check point
            if ($action->equivalent == 1)
                $index_of_last_check_point = $key;
        }

        if ($index_of_last_check_point != -1){
            $roled_debit = 0;
            $roled_credit = 0;
            foreach ($actions as $key=>$action) { // finding the sum of roled records and removing it for the collection
                if ($key <= $index_of_last_check_point){
                    if (!in_array($action->num_for_pound,[0,1])) {
                        $roled_debit += $action->debit / $action->num_for_pound;
                        $roled_credit += $action->credit / $action->num_for_pound;
                    }
                    $actions->forget($key);
                }
            }

            $roled_line = new Journal();
            $roled_line->invoice_type = -1;
            $roled_line->debit = $roled_debit;
            $roled_line->credit = $roled_credit;
            $roled_line->num_for_pound = 1;
            $roled_line->notes = __("global.checked");
            $roled_line->closing_date = new DateTime("01-01-0001");
            $roled_line->invoice_type = -1; // means checked point

            $actions->add($roled_line);
        }
        $actions = $actions->sortBy("closing_date")->sortBy("invoice_id")->sortBy("row_id");
        $total_credit = 0;
        $total_debit = 0;
        foreach($actions as $action){
            if ($action->invoice_type == -1){// so it is the record for roled
                $total_credit += $action->credit ;
                $total_debit += $action->debit ;
            }
            else if (!in_array($action->num_for_pound,[0,1])) {
                $total_credit += $action->credit / $action->num_for_pound;
                $total_debit += $action->debit / $action->num_for_pound;
            }
        }
        globalFunctions::registerUserActivityLog("discovered","cash_discover_after_last_checked_point",Account::where("name",$request["account"])->first()->id);
        return view("admin.discoverActions.cashDiscover")->with(["actions"=>$actions,"start_date"=>$request["from"],"end_date"=>$request["to"],"total_credit"=>$total_credit,"total_debit"=>$total_debit,"account_name"=>$request["account"]]);
    }




    public function chooseListProductDiscover(){
        return view("admin.discoverActions.productDiscover")->with("actions",null);
    }

    public function productDiscoverUntilNow(Request $request){
        $this->validate($request,
            [
                "product"=>"required"
            ]
        );
        $product_id = Product::where("name",$request["product"]);
        $actions = Journal::where("product_name",$request["product"])->whereIn("invoice_type",[1,2,3,4,11,12,13,15])->orderBy("closing_date")->orderBy("invoice_id")->orderBy("row_id")->get();

        $in_quantity = 0;
        $out_quantity = 0;
        $total_price = 0;
        foreach($actions as $action){
            $in_quantity += $action->in_quantity;
            $out_quantity += $action->out_quantity;
            if ( $action->in_quantity !=0)// so it is store in
                $total_price += $action->price * $action->quantity;
            else// so it is store out
                $total_price -= $action->price * $action->quantity;
        }
        globalFunctions::registerUserActivityLog("discovered","product_discover_until_now",Product::where("name",$request["product"])->first()->id);
        return view("admin.discoverActions.productDiscover")->with(["actions"=>$actions,"in_quantity"=>$in_quantity,"out_quantity"=>$out_quantity,"product_name"=>$request["product"],"total_price"=>$total_price]);
    }

    public function productDiscoverBetweenTowDates(Request $request){
        $this->validate($request,
            [
                "product"=>"required",
                "from"=>"required",
                "to"=>"required",
            ]
        );
        if ($request["from"]>$request["to"]){
            globalFunctions::flashMessage("discover","date_not_correct","");
            return back();
        }
        $start_date =  new DateTime($request["from"]);
        $start_date = $start_date->format("Y-m-d");

        $end_date = new DateTime($request["to"]);
        $end_date = $end_date->format("Y-m-d-");
//        $end_date = $end_date->format("Y-m-d- h:m:s");

        $actions = Journal::where("product_name",$request["product"])->whereIn("invoice_type",[1,2,3,4,11,12,13,15])->whereBetween("closing_date",[$start_date,$end_date])->orderBy("closing_date")->orderBy("invoice_id")->orderBy("row_id")->get();

        $in_quantity = 0;
        $out_quantity = 0;
        $total_price = 0;
        foreach($actions as $action){
            $in_quantity += $action->in_quantity;
            $out_quantity += $action->out_quantity;
            if ( $action->in_quantity !=0)// so it is store in
                $total_price += $action->price * $action->quantity;
            else// so it is store out
                $total_price -= $action->price * $action->quantity;
        }
        globalFunctions::registerUserActivityLog("discovered","product_discover_between_tow_dates",Product::where("name",$request["product"])->first()->id);
        return view("admin.discoverActions.productDiscover")->with(["actions"=>$actions,"start_date"=>$request["from"],"end_date"=>$request["to"],"in_quantity"=>$in_quantity,"out_quantity"=>$out_quantity,"product_name"=>$request["product"],"total_price"=>$total_price]);
    }

    public function productDiscoverWithAccount(Request $request)
    {
        $this->validate($request,
            [
                "account"=>"required",
                "product"=>"required"
            ]
        );
        $actions = Journal::where("first_part_name",$request["account"])->where("product_name",$request["product"])->whereIn("invoice_type",[1,2,3,4,11,12,13,15])->orderBy("closing_date")->orderBy("invoice_id")->orderBy("row_id")->get();

        $in_quantity = 0;
        $out_quantity = 0;
        $total_price = 0;
        foreach($actions as $action){
            $in_quantity += $action->in_quantity;
            $out_quantity += $action->out_quantity;
            if ( $action->in_quantity !=0)// so it is store in
                $total_price += $action->price * $action->quantity;
            else// so it is store out
                $total_price -= $action->price * $action->quantity;

        }
        globalFunctions::registerUserActivityLog("discovered","product_discover_with_account",Product::where("name",$request["product"])->first()->id);
        return view("admin.discoverActions.productDiscover")->with(["actions"=>$actions,"in_quantity"=>$in_quantity,"out_quantity"=>$out_quantity,"product_name"=>$request["product"],"account_name"=>$request["account"],"total_price"=>$total_price]);
    }

    public function productDiscoverUntilLastBalance(Request $request)
    {
        $this->validate($request,
            [
                "product"=>"required"
            ]
        );

        $actions = Journal::where("product_name",$request["product"])->whereIn("invoice_type",[1,2,3,4,11,12,13,15])->orderBy("closing_date")->orderBy("invoice_id")->orderBy("row_id")->get();

        $in_quantity = 0;
        $out_quantity = 0;
        $total_price = 0;
        $index_of_last_balanced_debit_with_credit = -1;
        foreach($actions as $key=>$action){
            $in_quantity += $action->in_quantity;
            $out_quantity += $action->out_quantity;
            if ( $action->in_quantity !=0)// so it is store in
                $total_price += $action->price * $action->quantity;
            else// so it is store out
                $total_price -= $action->price * $action->quantity;

            if ($in_quantity == $out_quantity)
                $index_of_last_balanced_debit_with_credit = $key;
        }

        if ($index_of_last_balanced_debit_with_credit != -1){
            $in_quantity = 0;
            $out_quantity = 0;
            $total_price = 0;
            foreach($actions as $key=>$action) {
                if ($key<=$index_of_last_balanced_debit_with_credit)
                    $actions->forget($key);
                else{
                    $in_quantity += $action->in_quantity;
                    $out_quantity += $action->out_quantity;
                    if ( $action->in_quantity !=0)// so it is store in
                        $total_price += $action->price * $action->quantity;
                    else// so it is store out
                        $total_price -= $action->price * $action->quantity;
                }
            }
        }
        globalFunctions::registerUserActivityLog("discovered","product_discover_until_last_balance",Product::where("name",$request["product"])->first()->id);
        return view("admin.discoverActions.productDiscover")->with(["actions"=>$actions,"in_quantity"=>$in_quantity,"out_quantity"=>$out_quantity,"product_name"=>$request["product"],"total_price"=>$total_price]);
    }

    public function productDiscoverByStore(Request $request)
    {
//        dd($request->all());
        $this->validate($request,["store"=>"required"]);
        $store_id = Store::where("name",$request["store"])->first()->id;
        $totals = Journal::where("product_id",">",0)->selectRaw("sum(in_quantity) as total_in_quantity,sum(out_quantity) as total_out_quantity")->join("products","journal.product_id","=","products.id")->where("products.store_id",$store_id)->first();
        $balances = Product::selectRaw("products.id ,products.name ,sum(journal.debit) as debit,sum(journal.credit) as out_quantity,sum(journal.in_quantity) - sum(journal.out_quantity) as balance")->leftJoin("journal","products.id","=","journal.product_id")->where("products.store_id",$store_id)->groupBy(["products.id","products.name"])->orderBy("products.name")->get();
        $products = Product::selectRaw("products.id as product_id, products.name as product_name")->leftJoin("journal","products.id","=","journal.product_id")->groupBy(["name","id"])->orderBy("name")->get();
        $actions = [];
        $accumulated_total_price = 0;
        foreach ($products as $key=>$product){
            if ($request["store_discover_type"] == "last"){
                $maxs = Journal::selectRaw("product_name,max(closing_date) as max_closing_date,max(journal.invoice_id) as max_invoice_id,max(journal.line) as max_line")->where("product_name",$product->product_name)->where("invoice_type",2)->groupBy(["product_name"])->first();
                $name_price=null;
                if ($maxs!=null)
                    $name_price = Journal::selectRaw("product_name,price")->where("invoice_type",2)->where("product_name",$maxs->product_name)->where("closing_date",$maxs->max_closing_date)->where("invoice_id",$maxs->max_invoice_id)->where("line",$maxs->max_line)->groupBy(["product_name","price"])->first();
            }
            else{
                $name_price = Journal::selectRaw("product_name,sum(price)/count(price) as price")->where("product_name",$product->product_name)->where("invoice_type",2)->groupBy(["product_name"])->first();
            }
            if ($name_price!=null) {
                array_push($actions,[
                    "product_id" => $product->product_id,
                    "product_name" => $name_price->product_name,
                    "balance" => ($balances[$key]->balance != null)? $balances[$key]->balance:0,
                    "price" => $name_price->price
                ]);
            }
            else{
                array_push($actions,[
                    "product_id" => $product->product_id,
                    "product_name" => $product->product_name,
                    "balance" => ($balances[$key]->balance != null)? $balances[$key]->balance:0,
                    "price" => 0
                ]);
            }
            $accumulated_total_price+=$actions[$key]["price"] * $actions[$key]["balance"];
        }
        globalFunctions::registerUserActivityLog("discovered","product_discover_by_store",Store::where("name",$request["store"])->first()->id);
        return view("admin.discoverActions.productsDiscoverByStore")->with(["actions"=>$actions,"total_in_quantity"=>$totals->total_in_quantity,"total_out_quantity"=>$totals->total_out_quantity,"total_balance"=>$totals->total_in_quantity-$totals->total_out_quantity,"accumulated_total_price"=>$accumulated_total_price]);
    }


    public function allAccountsDiscover()
    {
//        $actions = Journal::where("first_part_id",">",0)->selectRaw("first_part_id,first_part_name,sum(debit) as debit,sum(credit) as credit")->groupBy(["first_part_name","first_part_id"])->get();
        $actions = Account::selectRaw("accounts.id ,accounts.name ,sum(journal.debit) as debit,sum(journal.credit) as credit")->leftJoin("journal","accounts.id","=","journal.first_part_id")->groupBy(["accounts.id","accounts.name"])->get();

        $total_debit = 0;
        $total_credit = 0;
        foreach ($actions as $action){
            $total = $action->credit - $action->debit;
            if ($total > 0)
                $total_credit+=$total;
            else
                $total_debit+=$total;
        }
        $total_debit = abs($total_debit);
        $total_credit = abs($total_credit);
        globalFunctions::registerUserActivityLog("seen","accounts_balances",null);
        return view("admin.discoverActions.allAccountsDiscover")->with(["actions"=>$actions,"total_debit"=>$total_debit,"total_credit"=>$total_credit]);
    }

    public function allProductsDiscover()
    {
//        $actions = Journal::where("product_id",">",0)->selectRaw("product_id,product_name,sum(in_quantity) as in_quantity,sum(out_quantity) as out_quantity , sum(in_quantity) - sum(out_quantity) as balance")->groupBy(["product_name","product_id"])->get();
        $actions = Product::selectRaw("products.id ,products.name ,sum(journal.in_quantity) as in_quantity,sum(journal.out_quantity) as out_quantity,sum(journal.in_quantity) - sum(journal.out_quantity) as balance")->leftJoin("journal","products.id","=","journal.product_id")->groupBy(["products.id","products.name"])->get();

        $totals = Journal::where("product_id",">",0)->selectRaw("sum(in_quantity) as total_in_quantity,sum(out_quantity) as total_out_quantity")->first();
        globalFunctions::registerUserActivityLog("seen","products_balances",null);
        return view("admin.discoverActions.allProductsDiscover")->with(["actions"=>$actions,"total_in_quantity"=>$totals->total_in_quantity,"total_out_quantity"=>$totals->total_out_quantity,"total_balance"=>$totals->total_in_quantity-$totals->total_out_quantity]);
    }

    public function dailyDiscover()
    {
        $actions = Journal::where("created_at",">=",Carbon::today())->whereIn("invoice_type",[0,1,2,3,4,5,6,7,11,12,13,14,15,16])->get();
        $totals = Journal::where("created_at",">=",Carbon::today())->whereIn("invoice_type",[1,2,3,4,5,11,13,15])->selectRaw("sum(debit) as total_debit , sum(credit) as total_credit")->first();
        globalFunctions::registerUserActivityLog("seen","daily_actions",null);
        return view("admin.discoverActions.dailyDiscover")->with(["actions"=>$actions,"total_debit"=>$totals->total_debit,"total_credit"=>$totals->total_credit,"total_balance"=>$totals->total_credit-$totals->total_debit]);
    }



    public function globalDiscoverLastYear(Request $request){
        $this->validate($request,
            [
                "account"=>"required"
            ]
        );
        $actions = OldJournal::where("first_part_name",$request["account"])->orderBy("closing_date")->orderBy("invoice_id")->orderBy("row_id")->get();
        $totals = OldJournal::where("first_part_name",$request["account"])->selectRaw("sum(debit) as total_debit , sum(credit) as total_credit")->first();
        $total_credit = $totals->total_credit;
        $total_debit = $totals->total_debit;
        //        $total_credit = 0;
//        $total_debit = 0;
//        foreach($actions as $action){
//            $total_credit +=$action->credit;
//            $total_debit +=$action->debit;
//        }
        globalFunctions::registerUserActivityLog("discovered","account_lase_year",Account::where("name",$request["account"])->first()->id);
        return view("admin.discoverActions.globalDiscover")->with(["actions"=>$actions,"total_credit"=>$total_credit,"total_debit"=>$total_debit,"account_name"=>$request["account"],"is_last_year"=>true]);
    }


    public function cashDiscoverLastYear(Request $request){
        $this->validate($request,
            [
                "account"=>"required"
            ]
        );
        $actions = OldJournal::where("first_part_name",$request["account"])->orderBy("closing_date")->orderBy("invoice_id")->orderBy("row_id")->get();
        $totals = OldJournal::where("first_part_name",$request["account"])->selectRaw("sum(debit / num_for_pound) as total_debit , sum(credit / num_for_pound) as total_credit")->whereNotIn("num_for_pound",[0,1])->first();
        $total_credit = $totals->total_credit;
        $total_debit = $totals->total_debit;

//        $total_credit = 0;
//        $total_debit = 0;
//        foreach($actions as $action){
//            if (!in_array($action->num_for_pound,[0,1])) {
//                $total_credit +=$action->credit / $action->num_for_pound ;
//                $total_debit +=$action->debit / $action->num_for_pound;
//            }
//        }
        globalFunctions::registerUserActivityLog("discovered","product_lase_year",Account::where("name",$request["account"])->first()->id);
        return view("admin.discoverActions.cashDiscover")->with(["actions"=>$actions,"total_credit"=>$total_credit,"total_debit"=>$total_debit,"account_name"=>$request["account"],"is_last_year"=>true]);
    }

}
