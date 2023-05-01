<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Journal;
use Illuminate\Http\Request;
use App\Models\Account;
use App\functions\globalFunctions;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\PseudoTypes\NonEmptyLowercaseString;

class accountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        globalFunctions::registerUserActivityLog("seen_all","accounts",null);
        return view("admin.accounts.viewAccounts")->with("accounts",Account::all());
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
                "id"=>['required','unique:accounts'],
                "name"=>"required",
                "account_type"=>"required",
            ]);
//        if ($request["account_type"] == 0){
//            $this->validate($request,
//                [
//                    "reference"=>"required"
//                ]);
//        }


        if ($request["reference"]!=null && $request["reference"]!=0){
            $ref_account = Account::find($request["reference"]);
            if ($ref_account == null || $ref_account->account_type == 0){// means reference is invalid
                session()->flash("error",__("messages.invalid_reference"));
//                globalFunctions::flashMessage("create",$result,"user");
                return back();
            }
        }

        $result = Account::create($request->all());
//        if ($result!=null) {
//            session()->flash("success",__("messages.created_successfully",["attribute"=>__("global.account")]));
//        }else{
//            session()->flash("success",__("messages.not_created_successfully",["attribute"=>__("global.account")]));
//        }
        globalFunctions::flashMessage("create",$result,"account");
        globalFunctions::registerUserActivityLog("added","account",$result->id);

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        globalFunctions::registerUserActivityLog("seen","account",$account->id);
        return view("admin.accounts.showAccount")->with("account",$account);
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
    public function update(Request $request, Account $account)
    {
        $this->validate($request,
            [
                "name"=>"required",
                "account_type"=>"required",
            ]);
        if ($request["id"] != $account->id){
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
        if ($request["reference"]!=null && $request["reference"]!=0){
            $ref_account = Account::find($request["reference"]);
            if ($ref_account == null || $ref_account->account_type == 0){// means reference is invalid
                session()->flash("error",__("messages.invalid_reference"));
                return back();
            }
        }
        $oldAccount = $request->all();
        $account->id = $oldAccount["id"];
        $account->name = $oldAccount["name"];
        $account->reference = $oldAccount["reference"];
        $account->group = $oldAccount["group"];
        $account->account_type = $oldAccount["account_type"];

        $result = null;
        if ($account->isDirty(["id","name","reference","group","account_type"])) {
//            session()->flash("success",__("messages.updated_successfully",["attribute"=>__("global.account")]));
            $result = $account->save();
        }
//        else{
//            session()->flash("success",__("messages.nothing_to_be_updated"));
//        }
        globalFunctions::flashMessage("update",$result,"account");
        globalFunctions::registerUserActivityLog("updated","account",$account->id);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request,$account_id)
    {
        try {
        DB::beginTransaction();
            if ($account_id > 0) {
                $balance = Journal::where("first_part_id",$account_id)->selectRaw("sum(debit) - sum(credit) as balance")->first()->balance;
                // here whe check if the account balance is zero or not , so if it is zero delete it and update all invoices whose first_part_id = id and set first_part_id = 0 to allow adding new account with the same fo previous id
                 if ($balance != 0 and $balance != null) {
                    globalFunctions::flashMessage("softDelete","account_is_not_zero","account");
                    return back();
                }

                $result = Account::withTrashed()->find($account_id)->forceDelete();
                Journal::where("first_part_id",$account_id)->update(["first_part_id"=>0]);
                globalFunctions::registerUserActivityLog("deleted","account",$account_id);

            } else if (isset($request["multi_ids"])) {

                $ids = $request["multi_ids"];
                $result = Account::withTrashed()->whereIn("id",$ids)->forceDelete();
                Journal::whereIn("first_part_id",$ids)->update(["first_part_id"=>0]);
                foreach ($ids as $id) {
                    $balance = Journal::where("first_part_id", $id)->selectRaw("sum(debit) - sum(credit) as balance")->first()->balance;
                    if ($balance != 0 and $balance != null) {
                        DB::rollBack();
                        globalFunctions::flashMessage("softDelete", "account_is_not_zero", "account");
                        return back();
                    }
                    globalFunctions::registerUserActivityLog("deleted","account",$id);
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
        globalFunctions::flashMessage("delete",$result,"account");

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function viewRecyclebin()
    {
        globalFunctions::registerUserActivityLog("seen","accounts_recyclebin",null);
        return view("admin.accounts.recyclebin")->with("deletedAccounts",Account::onlyTrashed()->get());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Account $account
     * @return \Illuminate\Http\RedirectResponse
     */
    public function softDelete(Request $request,$account_id)
    {

        try {
            DB::beginTransaction();
            if ($account_id > 0) {
                $balance = Journal::where("first_part_id",$account_id)->selectRaw("sum(debit) - sum(credit) as balance")->first()->balance;
                if ($balance != 0 and $balance != null) {
                    globalFunctions::flashMessage("softDelete","account_is_not_zero","account");
                    DB::rollBack();
                    return back();
                }
                $result = Account::find($account_id)->delete();
                globalFunctions::registerUserActivityLog("recycled","account",$account_id);
            } else if (isset($request["multi_ids"])) {
                $ids = $request["multi_ids"];
                $result = Account::whereIn("id", $ids)->delete();
                foreach ($ids as $id) {
                    $balance = Journal::where("first_part_id", $id)->selectRaw("sum(debit) - sum(credit) as balance")->first()->balance;
                    if ($balance != 0 and $balance != null) {
                        DB::rollBack();
                        globalFunctions::flashMessage("softDelete", "account_is_not_zero", "account");
                        return back();
                    }
                    globalFunctions::registerUserActivityLog("recycled", "account", $id);
                }
            } else {
                $result = null;
            }
            DB::commit();
        }catch (\PDOException $e) {
            DB::rollBack();
            $result = null;
        }
        globalFunctions::flashMessage("softDelete",$result,"account");

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request,$account_id)
    {
        if ($account_id > 0) {
            $result = Account::onlyTrashed()->find($account_id)->restore();
            globalFunctions::registerUserActivityLog("restored","account",$account_id);
        } else if (isset($request["multi_ids"])) {
            $ids = $request["multi_ids"];
            $result = Account::onlyTrashed()->whereIn("id",$ids)->restore();
            foreach ($ids as $id){
                globalFunctions::registerUserActivityLog("restored","account",$id);
            }
        } else {
            $result = null;
        }
//        if ($result!=null) {
//            session()->flash("success",__("messages.restored_successfully",["attribute"=>__("global.account")]));
//        }else{
//            session()->flash("success",__("messages.restored_successfully",["attribute"=>__("global.account")]));
//        }
        globalFunctions::flashMessage("restore",$result,"account");

        return back();
    }
}
