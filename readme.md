# PaginatesModels

 [![Packagist](https://img.shields.io/packagist/vpre/ryanwinchester/laravel-paginates.svg?maxAge=2592000)]()
 [![Packagist](https://img.shields.io/packagist/l/ryanwinchester/laravel-paginates.svg?maxAge=2592000)]()

This trait adds a super duper handy method that will give you behaviour from requests slightly similar to what something like `league/fractal` gives you without all the setup and needing to create transformers.

Between this trait, and Eloquent Models' `$casts` and `$hidden` properties, starting a basic API with about as much control as some more *heavyweight* packages give you, will be really quick.

### Install:

Composer:

```
composer require "ryanwinchester/laravel-paginates:^0.3"
```

### To use it:

Add it to your controller (or base controller, as shown):
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use RyanWinchester\Paginates\PaginatesModels;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, PaginatesModels;
}
```

Then use it in your controller methods like so:
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Product;

class ProductsController extends Controller
{
    public function index()
    {
        $products = $this->paginate(Product::class);

        return response()->json($products);
    }
}
```

Sometimes because of security or privacy, you may want to limit things like `includes`

```php
$products = $this->paginate(
    Product::class,
    $request->except('include')
);
```

Or, say you want to define them yourself:

```php
$products = $this->paginate(
    Product::with(['variations']),
    $request->except('include')
);
```

Then you can go to your route and add some of these optional parameters to page and filter:
![url](http://s.ryanwinchester.ca/22413y1l2z3a/Screenshot%202016-10-03%2020.35.46.png)

![response](http://s.ryanwinchester.ca/0m3x0305111q/Screenshot%202016-10-03%2020.07.10.png)


## URL Query Parameters:

- **page**    : `page=3` the page number
- **perPage** : `perPage=10` amount to show per page
- **columns** : `columns=title,body,author` limit to certain columns
- **include** : `include=categories,tags` load relations
- **orderBy** : `orderBy=published|desc` order the items by a column and direction

# Please try it out and give feedback.

[Taylor thinks it's a good idea](https://github.com/laravel/framework/pull/15741), so I mean what other reason do you need?
