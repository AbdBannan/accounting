<?php

namespace App\Http\Middleware;

use App\Models\Account;
use App\Models\Category;
use App\Models\Journal;
use App\Models\Notification;
use App\Models\Permission;
use App\Models\Pound;
use App\Models\Product;
use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OnWelcomePageLoadMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $clean_recyclebin_after = auth()->user()->getConfig("clean_recyclebin_after");
        if ($clean_recyclebin_after <= 0){
            return $next($request);
        }
        User::onlyTrashed()->where("deleted_at","<",Carbon::now()->subtract("day",$clean_recyclebin_after))->forceDelete();
        Account::onlyTrashed()->where("deleted_at","<",Carbon::now()->subtract("day",$clean_recyclebin_after))->forceDelete();
        Role::onlyTrashed()->where("deleted_at","<",Carbon::now()->subtract("day",$clean_recyclebin_after))->forceDelete();
        Permission::onlyTrashed()->where("deleted_at","<",Carbon::now()->subtract("day",$clean_recyclebin_after))->forceDelete();
        Pound::onlyTrashed()->where("deleted_at","<",Carbon::now()->subtract("day",$clean_recyclebin_after))->forceDelete();
        Product::onlyTrashed()->where("deleted_at","<",Carbon::now()->subtract("day",$clean_recyclebin_after))->forceDelete();
        Category::onlyTrashed()->where("deleted_at","<",Carbon::now()->subtract("day",$clean_recyclebin_after))->forceDelete();
        Store::onlyTrashed()->where("deleted_at","<",Carbon::now()->subtract("day",$clean_recyclebin_after))->forceDelete();
        Journal::onlyTrashed()->where("deleted_at","<",Carbon::now()->subtract("day",$clean_recyclebin_after))->forceDelete();

        $actions = Product::selectRaw("products.id ,products.name ,sum(journal.in_quantity) as in_quantity,sum(journal.out_quantity) as out_quantity,sum(journal.in_quantity) - sum(journal.out_quantity) as balance")->leftJoin("journal","products.id","=","journal.product_id")->groupBy(["products.id","products.name"])->get();

        $temp_notifications = Notification::all();
        DB::table("notifications")->truncate();
        // todo : you can check for another type of notification
        foreach ($actions as $action){
            if ($action->balance <= auth()->user()->getConfig("level_of_product_quantity_for_notification")){
                $notification = new Notification();
                $notification->name = $action->name;
                if ($temp_notifications->where("name",$action->name)->count() > 0) {
                    $notification->has_seen = $temp_notifications->where("name",$action->name)->first()->has_seen;
                } else {
                    $notification->has_seen = false;
                }
                $notification->type = "product_quantity_is_not_enough";
                auth()->user()->notifications()->save($notification);
            }
        }

        return $next($request);
    }
}
