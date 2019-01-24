# Shortcut

Always keep in mind that CarismaResource extrends Laravel's JsonResource class, so you can use all its functionality to write clever json representation of your model.

There are some typical attributes that you will probably add to any resources. Resource class provide you some useful method to attach most common attributes sets to the json quickly and easily.

```php
/**
 * Get the fields displayed by the resource.
 *
 * @param  \Illuminate\Http\Request $request
 * @return array
 */
public function fields(Request $request)
{
    return [
        Field::make('id')->exceptOnForms(),

        Field::make('name')
        	->rules('required', 'string', 'max:255'),
        
        // Add "created_at" and "updated_at" as DateTime fields
        $this->timestamps(),
        
        // Add "deleted_at" as DateTime field
        $this->softDelete(),
    ];
}
```
