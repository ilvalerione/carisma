<?php

namespace Carisma\Http\Controllers;


use Carisma\Http\Requests\CarismaRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class DestroyController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @param CarismaRequest $request
     * @return mixed
     */
    public function handle(CarismaRequest $request)
    {
        $resource = $request->findResourceOrFail();

        $resource->authorizeToDelete($request);

        DB::transaction(function () use ($request, $resource){
            $resource->resource->delete();
        });

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}