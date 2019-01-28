<?php

namespace Carisma\Http\Middlewares;


use Carisma\Facades\Carisma;

class Authenticate
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        return Carisma::authCheck($request) ? $next($request) : abort(403);
    }
}