<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class SaveRequestHistory
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

        if (!session("request_history")){
            session(["request_history"=>[URL::current()]]);
        } else {
            $temp = session("request_history");
            if (last($temp)!= URL::current() and !str_contains(URL::current(),"/back" ) and strtolower($request->method()) == "get"){
                $temp[] = URL::current();
            }
            session(["request_history"=>$temp]);
        }


//        session(["last_method"=>session("current_method")]);
//        session(["last_params"=>session("current_params")]);
//
//        session(["current_method"=>$request->method()]);
//        session(["current_params"=>$request->all()]);

        return $next($request);
    }
}
