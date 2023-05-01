<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthenticateQuizMiddleware
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
        $key = "key_for_authenticate_the_api_requested_from_quiz_app_done_by_M_B_(QUIZ_APPLICATION)";
        if (!isset($request["key"]) and $request["key"] != $key) {
            return abort(403,"you are not authenticated");
        }
        return $next($request);
    }
}
