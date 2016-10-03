# Laravel Bigcommerce

Laravel Bigcommerce is a simple package which helps to build robust integration into bigcommerce.
This package support the Version 2 and 3 of the Bigcommerce Api.

##Installation

Add package to composer.json

    composer require oseintow/laravel-bigcommerce

Add the service provider to config/app.php in the providers array.

```php5
<?php

'providers' => [
    ...
    Oseintow\Bigcommerce\BigcommerceServiceProvider::class,
],
```

Setup alias for the Facade

```php5
<?php

'aliases' => [
    ...
    'Bigcommerce' => Oseintow\Bigcommerce\Facades\Bigcommerce::class,
],
```

##Configuration

Laravel Bigcommerce requires connection configuration. You will need to publish vendor assets

    php artisan vendor:publish

This will create a bigcommerce.php file in the config directory. You will need to set your **auth** keys

####BasicAuth

Set **API_KEY** , **USERNAME** AND **STORE URL**

####OAUTH

Set **CLIENT ID** , **CLIENT SECRET** AND **REDIRECT URL**

##Usage

Will Fill Later.

Let's retrieve access token

```php5
Route::get("process_oauth_result",function(\Illuminate\Http\Request $request)
{
    $response = Bigcommerce::getAccessToken($request->code, $request->scope, $request->context));

    dd($response);
});
```

By default the package support **API v3**

To set it to version 2 or 3 use

```php5
Bigcommerce::setApiVersion('v2');
```

or

```php5
Bigcommerce::setApiVersion('v2');
```

There are 2 ways to access resource from bigcommerce using this package.

1. Using the http verbs(ie. this gives you more flexibility and also support api v3 and also returns laravel collection)
2. Using Bigcommerce Collection (this does not support api v3 and laravel collection).

##Using Http verbs

```php5
Bigcommerce::get("resource uri",["query string params"]);
Bigcommerce::post("resource uri",["post body"]);
Bigcommerce::put("resource uri",["put body"]);
Bigcommerce::delete("resource uri");
```

Let use our access token to get products from bigcommerce.

**NB:** You can use this to access any resource on bigcommerce (be it Products, Shops, Orders, etc)

```php5
$storeHash = "ecswer";
$accessToken = "xxxxxxxxxxxxxxxxxxxxx";
$products = Bigcommerce::setStoreHash($storeHash)->setAccessToken($accessToken)->get("products");
```

To pass query params

```php5
// returns Collection
$bigcommerce = Bigcommerce::setStoreHash($storeHash)->setAccessToken($accessToken);
$products = $bigcommerce->get("admin/products.json", ["limit"=>20, "page" => 1]);
```

##Controller Example

If you prefer to use dependency injection over facades like me, then you can inject the Facade:

```php5
use Illuminate\Http\Request;
use Oseintow\Bigcommerce\Bigcommerce;

class Foo
{
    protected $bigcommerce;

    public function __construct(Bigcommerce $bigcommerce)
    {
        $this->bigcommerce = $bigcommerce;
    }

    /*
    * returns Collection
    */
    public function getProducts(Request $request)
    {
        $products = $this->bigcommerce->setStoreHash($storeHash)
            ->setAccessToken($accessToken)
            ->get('products');

        $products->each(function($product){
             \Log::info($product->title);
        });
    }
}
```

##Miscellaneous

To get Response headers

```php5
Bigcommerce::getHeaders();
```

To get specific header
```php5
Bigcommerce::getHeader("Content-Type");
```

To get response status code or status message
```php5
Bigcommerce::getStatus(); // 200
```

## Using Bigcommerce Collection

####Testing Configuration

Use code below To test if configuration is correct. Returns false if unsuccessful otherwise return DateTime Object.

```php5
$time = Bigcommerce::getTime();
```

###Accessing Resources
```php5
//  oauth
$storeHash = "afw2w";
$accessToken = "xxxxxxxxxxxxxxxxxxxxx";
$products = Bigcommerce::setStoreHash($storeHash)->setAccessToken($accessToken)->getProducts();

//Basic Auth
$products = Bigcommerce::getProducts();
```


##Paging and Filtering

All the default collection methods support paging, by passing the page number to the method as an integer:

$products = Bigcommerce::getProducts(3);

If you require more specific numbering and paging, you can explicitly specify a limit parameter:

```php5
$filter = array("page" => 3, "limit" => 30);

$products = Bigcommerce::getProducts($filter);
```

To filter a collection, you can also pass parameters to filter by as key-value pairs:

```php5
$filter = array("is_featured" => true);

$featured = Bigcommerce::getProducts($filter);
```

See the API documentation for each resource for a list of supported filter parameters.

Updating existing resources (PUT)

To update a single resource:

```php5
$product = Bigcommerce::getProduct(11);

$product->name = "MacBook Air";
$product->price = 99.95;
$product->update();
```

For more info on the Bigcommerce Collection check [this](https://packagist.org/packages/bigcommerce/api)

















