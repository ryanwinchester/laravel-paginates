# PaginatesModels

 [![Packagist](https://img.shields.io/packagist/vpre/ryanwinchester/laravel-paginates.svg?maxAge=2592000)](https://packagist.org/packages/ryanwinchester/laravel-paginates)
 [![Packagist](https://img.shields.io/packagist/l/ryanwinchester/laravel-paginates.svg?maxAge=2592000)](https://packagist.org/packages/ryanwinchester/laravel-paginates)

This trait adds a super duper handy method that will give you behaviour from requests slightly similar to what something like `league/fractal` gives you without all the setup and needing to create transformers.

Between this trait, and Eloquent Models' `$casts` and `$hidden` properties, starting a basic API with about as much control as some more *heavyweight* packages give you, will be really quick.

## Install

```
composer require ryanwinchester/laravel-paginates
```

## Usage

Add it to your controller (or base controller, as shown):
```php
// ...
use RyanWinchester\Paginates\PaginatesModels;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, PaginatesModels;
}
```

Then use it in your controller methods like so:
```php
class ProductsController extends Controller
{
    public function index()
    {
        $products = $this->paginate(Product::class);

        return response()->json($products);
    }
}
```

Sometimes because of security or privacy, you may want to limit `include` relationships and/or `columns`.
If that is the case, then be sure to include the parameters you want, making sure to exclude any user-supplied include parameters.

```php
$products = $this->paginate(
    Product::class,
    $request->except(['include', 'columns'])
);
```

Or, say you want to define some included relationships yourself:

```php
$products = $this->paginate(
    Product::with('variations'),
    $request->except(['include', 'columns'])
);
```

Or, even limit to specific columns:

```php
$products = $this->paginate(
    Product::select(['id', 'price', 'in_stock']),
    $request->except('columns')
);
```

You can pass in any builder instance or a model class name.

### Parameters:

- **page**    : `page=3` the page number
- **perPage** : `perPage=10` amount to show per page
- **columns** : `columns=title,body,author` limit to certain columns
- **include** : `include=categories,tags` load relations
- **orderBy** : `orderBy=published|desc` order the items by a column and direction

## In action

Then you can go to your route and add some of these optional parameters to page and filter:
![url](http://s.ryanwinchester.ca/22413y1l2z3a/Screenshot%202016-10-03%2020.35.46.png)

![response](http://s.ryanwinchester.ca/0m3x0305111q/Screenshot%202016-10-03%2020.07.10.png)


# Please try it out and give feedback.

[Taylor thinks it's a good idea](https://github.com/laravel/framework/pull/15741), so I mean what other reason do you need?
