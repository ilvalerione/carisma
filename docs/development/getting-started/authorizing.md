# Authorizing Carisma

Carisma is just to treat data of your API, you can build Carisma APIs under any authentication system you want.

Over your authentication system by default, any user can access the Carisma API. You are free to refine authorization as needed to restrict access only for your Carisma installation using `auth` method of the Carisma service in your `App\Providers\AuthServiceProvider`:

```php
use Carisma\Carisma;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Carisma::auth(function ($request){
            return in_array($request->user()->email, [
                'example@email.com',
            ]);
        })
    }
}
```

