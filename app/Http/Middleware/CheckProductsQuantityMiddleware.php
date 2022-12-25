<?php

namespace App\Http\Middleware;

use App\Models\Notification;
use App\Models\Product;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckProductsQuantityMiddleware
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
        try {
            DB::beginTransaction();
            $actions = Product::selectRaw("products.id ,products.name ,sum(journal.in_quantity) as in_quantity,sum(journal.out_quantity) as out_quantity,sum(journal.in_quantity) - sum(journal.out_quantity) as balance")->leftJoin("journal","products.id","=","journal.product_id")->groupBy(["products.id","products.name"])->get();
            $temp_notifications = Notification::all();
            DB::delete("DELETE FROM notifications");
            // todo : you can check for another type of notification
            foreach ($actions as $action){
                if ($action->balance <= auth()->user()->getConfig("level_of_product_quantity_for_notification")){
                    $notification = new Notification();
                    $notification->name = $action->name;
                    if ($temp_notifications->where("name",$action->name)->count() > 0) {
                        $notification->has_seen = $temp_notifications->where("name",$action->name)->first()->has_seen;
                        $notification->created_at = $temp_notifications->where("name",$action->name)->first()->created_at;
                    } else {
                        $notification->has_seen = false;
                    }
                    $notification->type = "product_quantity_is_not_enough";
                    auth()->user()->notifications()->save($notification);
                }
            }
            DB::commit();
        }
        catch(\PDOException $e){
//            dd($e);
            DB::rollBack();
        }

        return $next($request);
    }
}
