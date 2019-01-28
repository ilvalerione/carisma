<?php

namespace Carisma\Http\Requests;


class RelationshipRequest extends CarismaRequest
{
    /**
     * Get the relationship instance specified by the request.
     *
     * @return \Carisma\Fields\Relationships\Relationship
     */
    public function relationship()
    {
        return $this->availableRelationships()
            ->first(function ($relationship) {
                return $relationship->name == $this->route('relationship');
            }) ?: abort(404);
    }

    /**
     * Get the possible relationships for the request.
     *
     * @return \Illuminate\Support\Collection
     */
    public function availableRelationships()
    {
        return $this->newResource()->availableRelationships($this);
    }
}