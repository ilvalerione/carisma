<?php

namespace Carisma\Requests;


trait HasTypes
{
    /**
     * Check if current http request if for creating a new resource
     *
     * @return bool
     */
    public function isCreatingRequest() : bool
    {
        return $this->method() === 'POST';
    }

    /**
     * Check if current http request if for updating resource
     *
     * @return bool
     */
    public function isUpdatingRequest() : bool
    {
        return $this->method() === 'PUT';
    }

    /**
     * Check if current http request if for deleting resource
     *
     * @return bool
     */
    public function isDeletingRequest() : bool
    {
        return $this->method() === 'DELETE';
    }
}