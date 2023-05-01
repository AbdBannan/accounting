<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
class ActivatedMiddleWare
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

        if ($request->email != null){
            $user = User::where("email",$request->email)->first();
        }else{
            $user = auth()->user();
        }

        if ($user != null && !$user->active == 1){
            abort(403,"sorry you are not activated yet");
        }
        return $next($request);
    }
}
