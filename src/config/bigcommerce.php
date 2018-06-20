<?php

return [

    /*
    |--------------------------------------------------------------------------
    | BigCommerce API Credentials
    |--------------------------------------------------------------------------
    |
    | This file is for setting the credentials for BigCommerce API key and
    | secret.
    |
    | To create these values for your store, go to Admin panel > Advanced
    | settings > API Accounts > Create API Account. You will need to configure
    | the scopes accordingly, based on what you are wanting this application to
    | do. If you need help with Scopes, you can find more information at
    | https://developer.bigcommerce.com/api/#oauth-scopes
    |
    */

    'client_id' => env('BIGCOMMERCE_CLIENT_ID'),

    'client_secret' => env('BIGCOMMERCE_CLIENT_SECRET'),

    'redirect_url' => env('BIGCOMMERCE_REDIRECT_URL'),

    /*
    |--------------------------------------------------------------------------
    | Single Tenant App Configuration
    |--------------------------------------------------------------------------
    |
    | If you are building a single-tenant app, then you will want to add the
    | Access token you created when creating the API Account and your store
    | hash.
    |
    | If you are building a multi-tenant (e.g. Big Commerce app store) app,
    | leave the access_token empty and set it within the application.
    |
    | The store hash can be found by logging into your store and looking at
    | the URL. For example, in this URL,
    |
    |   https://store-XXXXXXXXXX.mybigcommerce.com/
    |
    | XXXXXXXXXX is the store hash.
    |
    */

    'access_token' => env('BIGCOMMERCE_ACCESS_TOKEN'),

    'store_hash' => env('BIGCOMMERCE_STORE_HASH'),

    'default_version' => env('BIGCOMMERCE_API_VERSION', 'v3'),

];
