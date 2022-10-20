<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SaveCurrentRequest
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

//        session(["last_method"=>session("current_method")]);
//        session(["last_params"=>session("current_params")]);
//
//        session(["current_method"=>$request->method()]);
//        session(["current_params"=>$request->all()]);

        return $next($request);
    }
}
