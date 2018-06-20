<?php

namespace Oseintow\Bigcommerce;

use Oseintow\Bigcommerce\Exceptions\BigcommerceApiException;
use \Oseintow\Bigcommerce\Facades\Bigcommerce;

class Webhooks
{
    public static function create($scope, $destination = null, $active = true, $extraHeaders = [])
    {
        $webhook = [
            'is_active' => true,
            'destination' => $destination ?? static::getDefaultWebhookUrl(),
            'scope' => $scope,
            'headers' => array_merge([
                'X-Bcl-Secret' => config('bigcommerce.webhook_secret')
            ], $extraHeaders)
        ];
        try {
            return Bigcommerce::createWebhook((object)$webhook);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function getDefaultWebhookUrl() {
        return sprintf('%s%s',
            rtrim(config('bigcommerce.webhook_url_base'), "\n\t/"),
            route('bigcommerce.webhook', [], false));
    }

    public static function delete($arg)
    {
        return BigCommerce::deleteWebhook($arg);
    }

    public static function deactivate($scope)
    {
        $hooks = Bigcommerce::listWebhooks();
    }
}