# Laravel BigCommerce Webhook Quickstart

This package is a kickstart-style package for Laravel which makes rapidly building and locally
developing Webhook-focused integrations.

## Prerequisites

This package assumes you have [`ngrok`][0] installed. You only a free account in order
to take advantage of this package. The `ngrok` command line application must be installed and
must be in your path.

## Installation

Require the package in your project:

    composer require vervecommerce/laravel-bigcommerce

## Configuration

Before getting started, you'll need to configure the integration. Here is the configuration stub for your `.env` file:

    BIGCOMMERCE_CLIENT_ID=a6t18jv7evll0s8m77jx0qitc8q0ecr
    BIGCOMMERCE_CLIENT_SECRET=5ay6wbemr44a8bngnp0ut2m5xf8qewi
    BIGCOMMERCE_STORE_HASH=mj8zesutwp
    BIGCOMMERCE_ACCESS_TOKEN=607h4d4fxk6nupvjycyjodr5j1sd55i
    BIGCOMMERCE_API_VERSION=v2
    BIGCOMMERCE_NGROK_URL=
    BIGCOMMERCE_WEBHOOK_SECRET=

You'll also want to publish the configuration file:

    php artisan vendor:publish

And then you'll want to generate a secret key:

    php artisan bigcommerce:

This will create a `config/bigcommerce.php` file in the config directory. If you need more information about the config,
there is plenty of documentation in the config file itself.

## oAuth

This module only supports oAuth since it's the only method webhooks are supported.

Set **CLIENT ID** , **CLIENT SECRET** AND **REDIRECT URL**

## Usage

There are 2 ways to access resource from bigcommerce using this package.

1. Using the http verbs(ie. this gives you more flexibility and also support api v3 and also returns laravel collection)
2. Using Bigcommerce Collection (this does not support api v3 and laravel collection).

By default the package support **API v3**

To set it to version 2 or 3 use

```php5
Bigcommerce::setApiVersion('v2');
```

or

```php5
Bigcommerce::setApiVersion('v3');
```

## Using Http verbs

```php5
Bigcommerce::get("resource uri",["query string params"]);
Bigcommerce::post("resource uri",["post body"]);
Bigcommerce::put("resource uri",["put body"]);
Bigcommerce::delete("resource uri");
```

Let use our access token to get products from bigcommerce.

**NB:** You can use this to access any resource on bigcommerce (be it Products, Shops, Orders, etc).
And also you dont need store hash and access token when using basic auth.

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

## Controller Example

If you prefer to use dependency injection over facades like me, then you can inject the Class:

```php5
use Illuminate\Http\Request;
use VerveCommerce\Bigcommerce\Bigcommerce;

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

## Miscellaneous

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

#### Testing Configuration

Use code below To test if configuration is correct. Returns false if unsuccessful otherwise return DateTime Object.

```php5
$time = Bigcommerce::getTime();
```

### Accessing Resources
```php5
//  oauth
$storeHash = "afw2w";
$accessToken = "xxxxxxxxxxxxxxxxxxxxx";
$products = Bigcommerce::setStoreHash($storeHash)->setAccessToken($accessToken)->getProducts();

//Basic Auth
$products = Bigcommerce::getProducts();
```


## Paging and Filtering

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

[0]: https://ngrok.com

