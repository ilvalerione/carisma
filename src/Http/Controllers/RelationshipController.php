<?php

namespace Carisma\Http\Controllers;

use Carisma\Http\Requests\RelationshipRequest;
use Illuminate\Routing\Controller;

class RelationshipController extends Controller
{
    public function handle(RelationshipRequest $request)
    {
        return $request->findResourceOrFail()->resolvesRelationship($request);
    }
}