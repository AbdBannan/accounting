<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Account;
use App\functions\globalFunctions;
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
     * @return \Illuminate\Http\Response
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
                session()->flash("error",__("messages.invalid_reference",[],session("lang")));
//                globalFunctions::flashMessage("create",$result,"user");
                return back();
            }
        }

        $result = Account::create($request->all());
//        if ($result!=null) {
//            session()->flash("success",__("messages.created_successfully",["attribute"=>__("global.account",[],session("lang"))],session("lang")));
//        }else{
//            session()->flash("success",__("messages.not_created_successfully",["attribute"=>__("global.account",[],session("lang"))],session("lang")));
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
     * @return \Illuminate\Http\Response
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
                session()->flash("error",__("messages.invalid_reference",[],session("lang")));
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
//            session()->flash("success",__("messages.updated_successfully",["attribute"=>__("global.account",[],session("lang"))],session("lang")));
            $result = $account->save();
        }
//        else{
//            session()->flash("success",__("messages.nothing_to_be_updated",[],session("lang")));
//        }
        globalFunctions::flashMessage("update",$result,"account");
        globalFunctions::registerUserActivityLog("updated","account",$account->id);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($account_id)
    {
        $result = Account::onlyTrashed()->where("id",$account_id)->forceDelete();

//        if ($result!=null) {
//            session()->flash("success",__("messages.deleted_successfully",["attribute"=>__("global.account",[],session("lang"))],session("lang")));
//        }else{
//            session()->flash("success",__("messages.not_deleted_successfully",["attribute"=>__("global.account",[],session("lang"))],session("lang")));
//        }
        globalFunctions::flashMessage("delete",$result,"account");
        globalFunctions::registerUserActivityLog("deleted","account",$account_id);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function softDelete(Account $account)
    {
        $result = $account->delete();

//        if ($result!=null) {
//            session()->flash("success",__("messages.recycled_successfully",["attribute"=>__("global.account",[],session("lang"))],session("lang")));
//        }else{
//            session()->flash("success",__("messages.not_recycled_successfully",["attribute"=>__("global.account",[],session("lang"))],session("lang")));
//        }
        globalFunctions::flashMessage("softDelete",$result,"account");
        globalFunctions::registerUserActivityLog("recycled","account",$account->id);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($account_id)
    {
        $result = Account::onlyTrashed()->where("id",$account_id)->restore();

//        if ($result!=null) {
//            session()->flash("success",__("messages.restored_successfully",["attribute"=>__("global.account",[],session("lang"))],session("lang")));
//        }else{
//            session()->flash("success",__("messages.restored_successfully",["attribute"=>__("global.account",[],session("lang"))],session("lang")));
//        }
        globalFunctions::flashMessage("restore",$result,"account");
        globalFunctions::registerUserActivityLog("restored","account",$account_id);

        return back();
    }
}
