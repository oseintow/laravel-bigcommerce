<?php

namespace Oseintow\Bigcommerce;

use Config;
use Bigcommerce\Api\Connection as BigcommerceClient;
use Bigcommerce\Api\Client as BigcommerceClientResource;
use Oseintow\Bigcommerce\Exceptions\BigcommerceApiException;


class Bigcommerce
{
    protected $clientId;
    protected $clientSecret;
    protected $storeHash;
    protected $accessToken;

    protected $bigcommerce;
    protected $connection;
    protected $version = "v3";
    protected $authServiceUrl= "https://login.bigcommerce.com/";
    protected $baseApiURL  =  "https://api.bigcommerce.com/";
    protected $redirectUrl;
    protected $resourceURI;
    protected $cipher = 'AES256-SHA';

    public function __construct($connection)
    {
        $this->setConnection($connection);
    }

    private function setConnection($connection)
    {
        $connections = ['oAuth', 'basicAuth'];

        if (!in_array($connection, $connections))
            throw new BigcommerceApiException("No connection set", 403);

        $this->connection = $connection;
        $this->$connection();
    }

    public function verifyPeer($option = false)
    {
        $this->bigcommerce->verifyPeer($option);

        return $this;
    }

    private function oAuth(){
        $this->bigcommerce = new BigcommerceClient();
        $this->bigcommerce->setCipher($this->cipher);
        $this->clientId = Config::get('bigcommerce.'.$this->connection.'.client_id');
        $this->clientSecret = Config::get('bigcommerce.'.$this->connection.'.client_secret');
        $this->redirectUrl = Config::get('bigcommerce.'.$this->connection.'.redirect_url');
        $this->bigcommerce->addHeader("X-Auth-Client", $this->clientId );
    }

    private function basicAuth(){
        BigcommerceClientResource::configure(array(
            'store_url' => Config::get('bigcommerce.'.$this->connection.'.store_url'),
            'username'  => Config::get('bigcommerce.'.$this->connection.'.username'),
            'api_key'   => Config::get('bigcommerce.'.$this->connection.'.api_key')
        ));
    }

    public function setCipher($cipher){
        $this->cipher = $cipher;
        $this->bigcommerce->setCipher($this->cipher);
    }

    /*
     * Set store hash;
     */
    public function setStoreHash(string $storeHash)
    {
        $this->storeHash = $storeHash;

        return $this;
    }

    public function setApiVersion($version){
        $this->version = $version;
    }

    public function getAccessToken($code, $scope, $context)
    {
        $tokenUrl = $this->authServiceUrl . "oauth2/token";

        $response = $this->bigcommerce->post($tokenUrl, [
                "client_id" => $this->clientId,
                "client_secret" => $this->clientSecret,
                "redirect_uri" => $this->redirectUrl,
                "grant_type" => "authorization_code",
                "code" => $code,
                "scope" => $scope,
                "context" => $context
            ]);

        return $response->access_token;
    }

    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        $this->bigcommerce->addHeader("X-Auth-Token", $accessToken);

        return $this;
    }

    public function addHeader($key, $value)
    {
        $this->bigcommerce->addHeader($key, $value);

        return $this;
    }

    /*
     *  $args[0] is for route uri and $args[1] is either request body or query strings
     */
    public function __call($method, $args)
    {
        $httpVerbs = ['get', 'post', 'put', 'delete'];
        if (in_array($method, $httpVerbs)) {
            return $this->makeHttpVerbRequest($method, $args[0], $args[1] ?? null);
        }

        return $this->makeBigcomerceResourceRequest($method, $args);
    }

    public function makeBigcomerceResourceRequest($method, $args)
    {
        try {
//            $data = null;
//            if(count($args) == 2)
//                $data = $this->bigcommerce->$method($args[0], $args[1]);
//            elseif(count($args) == 1)
//                $data = $this->bigcommerce->$method($args[0]);
//            else
//                $data = $this->bigcommerce->$method();

            if($this->connection == "oAuth"){
                BigcommerceClientResource::configure(array(
                    'client_id' => $this->clientId,
                    'auth_token' => $this->accessToken,
                    'store_hash' => $this->storeHash
                ));
            }

            $data = call_user_func_array([BigcommerceClientResource::class, $method], $args);

            return collect($data);
        }catch(Exception $e){
            throw new BigcommerceApiException($e->getMessage(), $e->getCode());
        }
    }

    public function resourceURI($resource){
        $this->resourceURI = $this->baseApiUrl . $this->storeHash . "/{$this->version}/" . $resource;

        return $this->resourceURI;
    }

    public function makeHttpVerbRequest($httpVerb, $resource, $filters = null)
    {
        try {

            $data = $this->bigcommerce->$httpVerb($this->resourceURI($resource), $filters);

            if ($this->bigcommerce->getHeader("X-Retry-After")) {
                if ($this->bigcommerce->getHeader("X-Retry-After") > 0) {
                    sleep($this->bigcommerce->getHeader("X-Retry-After") + 5);

                    return $this->makeHttpVerbRequest($httpVerb, $resource, $filters);
                }
            }

            return collect($data);

        }catch(Exception $e){
            throw new BigcommerceApiException($e->getMessage(), $e->getCode());
        }
    }

    public function getStatus()
    {
        return $this->bigcommerce->getStatus();
    }

    public function getHeaders()
    {
        return $this->bigcommerce->getHeaders();
    }

    public function getHeader($header)
    {
        return $this->bigcommerce->getheader($header);
    }

}