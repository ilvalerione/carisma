<?php

namespace Carisma\Http\Controllers;

use Carisma\Http\Requests\RelationshipRequest;
use Illuminate\Routing\Controller;

class RelationshipController extends Controller
{
    public function get(RelationshipRequest $request)
    {
        return $request->findResourceOrFail()
            ->resolvesRelationship($request);
    }

    public function attach(RelationshipRequest $request)
    {
        $request->findResourceOrFail();
    }

    public function detach(RelationshipRequest $request)
    {
        $request->findResourceOrFail();
    }

    public function sync(RelationshipRequest $request)
    {
        $request->findResourceOrFail();
    }

    public function toggle(RelationshipRequest $request)
    {
        $request->findResourceOrFail();
    }
}