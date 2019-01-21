<?php

namespace Carisma\Http\Requests;


class ActionRequest extends CarismaRequest
{
    /**
     * Get the action instance specified by the request.
     *
     * @return \Carisma\Actions\Action
     */
    public function action()
    {
        return $this->availableActions()->first(function ($action) {
            return $action->name() == $this->query('action');
        }) ?: abort(404);
    }

    /**
     * Get the possible actions for the request.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function availableActions()
    {
        return $this->newResource()->availableActions($this);
    }
}