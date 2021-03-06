# simple-router

## SimpleRouter

A very simple and small PHP router for url routing in your project.

### Simple example:
```php
// code in: ./public/index.php

// Require the router class
include '../src/Route.php';

// Require the controller class (change path to match your project)
include '../app/Controllers/HomeController.php';

// Use router namespace
use WebHoanHao\SimpleRouter\Route;

// Use app namespace (change namespace to match your project)
use App\Controllers\HomeController;

// Add your routes
Route::add('/', HomeController::class,'index','index-route-name');
Route::add('/hello', HomeController::class,'hello','hello-route-name');
Route::add('/product-detail', ProductController::class,'detail','product-detail');
// ....

// Run the router
Route::run();
```

### Example with Composer  
For installation, just run 
`composer require webhoanhao/simple-router`

Then add the autoloader to your project like this:
```php
// code in: ./public/index.php

// Autoload files using composer
require_once __DIR__ . '/../vendor/autoload.php';

// Use router namespace
use WebHoanHao\SimpleRouter\Route;

// Use app namespace (change namespace to match your project)
use App\Controllers\HomeController;

// Add your routes
Route::add('/', HomeController::class,'index','index-route-name');
Route::add('/hello', HomeController::class,'hello','hello-route-name');
Route::add('/product-detail', ProductController::class,'detail','product-detail');
// ....

// Run the router
Route::run();
```

### Get URL from route BY NAME
```php
$url = Route::url('routeName');
```
If there are some params
```php
$url = Route::url('routeName',[$param_1,2,'param3']);
```

### Get params in target method

```php
    public function detail($param1, $param2, $param3)
    {
        echo "Param1 = ".$param1;
        echo "Param2 = ".$param2;
        echo "Param3 = ".$param3;
    }
```

### License
This project is licensed under the MIT License. See LICENSE for further information.
