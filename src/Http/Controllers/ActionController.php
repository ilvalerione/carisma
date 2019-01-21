<?php

namespace Carisma\Http\Controllers;

use Carisma\Http\Requests\ActionRequest;
use Illuminate\Routing\Controller;

class ActionController extends Controller
{
    /**
     * Perform an action on the specified resources.
     *
     * @param ActionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function handle(ActionRequest $request)
    {
        return $request->action()->handleRequest($request);
    }
}