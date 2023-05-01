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

        $current = URL::full();
        if (!session("request_history")){
            session(["request_history"=>[$current]]);
//        } elseif (session("error") || count($errors) > 0){
        } elseif (session("error")){
            $temp = session("request_history");
            unset($temp[count($temp)-1]);
            session(["request_history"=>$temp]);
        } else{
            $temp = session("request_history");
            if (last($temp)!= $current and !str_contains($current,"/back" ) and !str_contains($current,"restore" ) and strtolower($request->method()) == "get"){
                $temp[] = $current;
            }
            session(["request_history"=>$temp]);
        }

        return $next($request);
    }
}
