<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use Closure;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $header = $request->headers->all();
        if(!isset($header['authorization'])||$header['authorization']==''){
            return Controller::setResponse($header,400,-4023);
        }
        return $next($request);
    }
}
