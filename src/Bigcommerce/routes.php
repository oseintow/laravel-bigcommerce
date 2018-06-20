<?php
    /*
    |--------------------------------------------------------------------------
    | BigCommerce Webhook Route
    |--------------------------------------------------------------------------
    |
    | This default route provides a standard base for hooks which are auto-
    | generated using the CLI tools. You can override this in your own
    | application by creating any POST route and giving it the name
    | 'bigcommerce.webhook'.
    |
    */

Route::post('bigcommerce/webhook',
    '\VerveCommerce\Bigcommerce\Controllers\Webhook@process')->name('bigcommerce.webhook');
