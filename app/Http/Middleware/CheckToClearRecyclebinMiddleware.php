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

class CheckToClearRecyclebinMiddleware
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

        return $next($request);
    }
}
