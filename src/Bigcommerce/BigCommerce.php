<?php

namespace Oseintow\Bigcommerce;

use Bigcommerce\Api\Connection as ApiConnection;
use Bigcommerce\Api\Client as ApiClient;
use Exceptions\BigcommerceApiException;


class Bigcommerce
{
    protected $clientId;
    protected $clientSecret;
    protected $storeHash;
    protected $accessToken;

    protected $connection;
    protected $client;
    protected $version;
    protected $authServiceUrl = "https://login.bigcommerce.com/";
    protected $baseApiUrl = "https://api.bigcommerce.com/";
    protected $redirectUrl;
    protected $resourceUri;

    public function __construct()
    {
        $this->connection = new ApiConnection();
        $this->version = config('bigcommerce.default_version', 'v3');

        if ($hash = config('bigcommerce.store_hash')) {
            $this->setStoreHash($hash);
        }

        if ($token = config('bigcommerce.access_token')) {
            $this->setAccessToken($token);
        }

        $this->clientId = config('bigcommerce.client_id');
        $this->clientSecret = config('bigcommerce.client_secret');
        $this->redirectUrl = config('bigcommerce.redirect_url');

        $this->connection->addHeader("X-Auth-Client", $this->clientId );
    }

    /*
     * Set store hash;
     */
    public function setStoreHash($storeHash)
    {
        $this->storeHash = $storeHash;
        return $this;
    }

    public function setApiVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    public function setAccessToken($accessToken)
    {
        return tap($this, function ($bc) use ($accessToken) {
            $bc->accessToken = $accessToken;
            $bc->connection->addHeader("X-Auth-Token", $accessToken);
        });
    }

    /*
     *  $args[0] is for route uri and $args[1] is either request body or query strings
     */
    public function __call($method, $args)
    {
        if (in_array($method, ['get', 'post', 'put', 'delete'])) {
            return $this->makeBasicRequest($method, $args[0], $args[1] ?? null);
        }

        return $this->proxyClientRequest($method, $args);
    }

    public function makeBasicRequest($httpVerb, $resource, $filters = null)
    {
        dump($this->resourceUri($resource));
        try {
            $data = $this->connection->$httpVerb($this->resourceUri($resource), $filters);

            if ($retryAfter = $this->connection->getHeader("X-Retry-After") &&
                $retryAfter > 0) {
                sleep($retryAfter + 5);
                return $this->makeBasicRequest($httpVerb, $resource, $filters);
            }

            return $this->version == "v2" ?
                collect($data) : collect($data)->map(function ($value) {
                    return collect($value);
                });

        } catch (Exception $e) {
            throw new BigcommerceApiException($e->getMessage(), $e->getCode());
        }
    }

    public function proxyClientRequest($method, $args)
    {
        try {
            ApiClient::configure([
                'client_id'  => $this->clientId,
                'auth_token' => $this->accessToken,
                'store_hash' => $this->storeHash
            ]);

            $data = call_user_func_array([ApiClient::class, $method], $args);

            return $data;
        } catch (Exception $e) {
            throw new BigcommerceApiException($e->getMessage(), $e->getCode());
        }
    }

    public function resourceUri($resource)
    {
        $this->resourceUri = $this->baseApiUrl . "stores/" . $this->storeHash . "/{$this->version}/" . $resource;

        return $this->resourceUri;
    }

    public function addHeader($key, $value)
    {
        $this->connection->addHeader($key, $value);

        return $this;
    }

    public function removeHeader($header)
    {
        $this->connection->remove($header);
    }

    public function getStatus()
    {
        return $this->connection->getStatus();
    }

    public function getHeaders()
    {
        return $this->connection->getHeaders();
    }

    public function getHeader($header)
    {
        return $this->connection->getheader($header);
    }

}