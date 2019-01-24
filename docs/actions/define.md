# Define Action

Carisma actions may be generated using the `carisma:action` Artisan command. By default, all actions are placed in the `app/Carisma/Actions` directory:


```shell
php artisan carisma:action EmailAccountProfile
```

In this example, we'll define an action that sends an email message to a user or group of users:

```php
<?php

namespace App\Carisma\Actions;

use Illuminate\Bus\Queueable;
use Carisma\Actions\Action;
use Carisma\Http\Requests\ActionRequest;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailAccountProfile extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Perform the action on the given models.
     *
     * @param  \Carisma\Http\Requests\ActionRequest  $request
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function run(ActionRequest $request, Collection $models)
    {
        foreach ($models as $model) {
            (new AccountData($model))->send();
        }
    }
}
```

The `run` method receives the current request instance, as well as a collection of selected models. The `run`method **always** receives a `Collection` of models, even if the action is only being performed against a single model.

Within the `run` method, you may perform whatever tasks are necessary to complete the action. You are free to update database records, send emails, call other services, etc. The sky is the limit!
