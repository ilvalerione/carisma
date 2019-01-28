# Resource Events

All Carisma operations use Eloquent model of the resource so are available all native events you are familiar with. Therefore, it is easy to listen for model events triggered by Carisma and react to them. The easiest approach is to simply attach a [model observer](https://laravel.com/docs/eloquent#observers) to a model:

```php
namespace App\Providers;

use App\User;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
```

If you would like to attach any observer **only during** Carisma related HTTP requests, you may register observers within `Carisma::serving` event listener in your application's `EventServiceProvider`. This listener will only be executed during Carisma requests:

```php
use App\User;
use Carisma\Facades\Carisma;
use App\Observers\CarismaUserObserver;

/**
 * Bootstrap any application services.
 *
 * @return void
 */
public function boot()
{
    parent::boot();

    Carisma::serving(function () {
        User::observe(CarismaUserObserver::class);
    });
}
```

