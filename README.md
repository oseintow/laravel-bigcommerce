# Laravel Bigcommerce

Laravel Bigcommerce is a simple package which helps to build robust integration into bigcommerce.

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

###BasicAuth

You will need to set **API_KEY** , **USERNAME** AND **STORE URL**

###OAUTH

You will need to set **CLIENT ID** , **CLIENT SECRET** AND **REDIRECT URL**

##Usage

Will Fill Later.

Let's retrieve access token

```php5
Route::get("process_oauth_result",function(\Illuminate\Http\Request $request)
{
    $accesToken = Bigcommerce::getAccessToken($request->code, $request->scope, $request->context));

    dd($accessToken);
});
```

To access API resource use

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
use Oseintow\Bigcommerce\Facades\Bigcommerce;

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














