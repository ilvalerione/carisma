<?php

namespace Carisma\Fields;

use Illuminate\Http\Request;

class File extends Field
{
    /**
     * The name of the disk the file uses by default.
     *
     * @var string
     */
    protected $disk = 'public';

    /**
     * The file storage path.
     *
     * @var string
     */
    public $storagePath = '/';

    /**
     * The callback that should be executed to store the file.
     *
     * @var callable
     */
    public $storageCallback;

    /**
     * The callback that should be used to determine the file's storage name.
     *
     * @var callable|null
     */
    public $storeAsCallback;

    public function __construct($name = null, $attribute = null, $storageCallback = null)
    {
        parent::__construct($name, $attribute);

        $this->prepareStorageCallback($storageCallback);
    }

    /**
     * Set the name of the disk the file is stored on by default.
     *
     * @param  string  $disk
     * @return $this
     */
    public function disk($disk)
    {
        $this->disk = $disk;

        return $this;
    }

    /**
     * Set the file's storage path.
     *
     * @param  string  $path
     * @return $this
     */
    public function path($path)
    {
        $this->storagePath = $path;

        return $this;
    }

    /**
     * Prepare the storage callback.
     *
     * @param  callable|null  $storageCallback
     * @return void
     */
    protected function prepareStorageCallback($storageCallback)
    {
        $this->storageCallback = $storageCallback ?? function ($request, $model) {
                return $this->storeFile($request);
            };
    }

    /**
     * Store the file on disk.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function storeFile($request)
    {
        if (! $this->storeAsCallback) {
            return $request->file($this->attribute)->store($this->storagePath, $this->disk);
        }

        return $request->file($this->attribute)->storeAs(
            $this->storagePath, call_user_func($this->storeAsCallback, $request), $this->disk
        );
    }

    /**
     * Specify the callback that should be used to store the file.
     *
     * @param  callable  $storageCallback
     * @return $this
     */
    public function store(callable $storageCallback)
    {
        $this->storageCallback = $storageCallback;

        return $this;
    }

    /**
     * Specify the callback that should be used to determine the file's storage name.
     *
     * @param  callable  $storeAsCallback
     * @return $this
     */
    public function storeAs(callable $storeAsCallback)
    {
        $this->storeAsCallback = $storeAsCallback;

        return $this;
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  object  $model
     * @return void
     */
    public function fill(Request $request, $model)
    {
        if (is_null($file = $request->file($this->attribute)) || ! $file->isValid()) {
            return;
        }

        $result = call_user_func($this->storageCallback, $request, $model);

        if ($result === true) {
            return;
        }

        $model->{$this->attribute} = $result;
    }
}