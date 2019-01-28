<?php

namespace Carisma\Http\Middlewares;

use Carisma\Events\ServingCarisma;

class ServeCarisma
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
        ServingCarisma::dispatch($request);

        return $next($request);
    }
}