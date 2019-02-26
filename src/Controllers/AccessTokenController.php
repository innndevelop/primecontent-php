<?php

namespace Primecontent\Controllers;

use Primecontent\Exceptions\ClientException;
use Primecontent\Exceptions\NotInitializedException;
use Primecontent\Factories\ConfigFactory;

class AccessTokenController extends BaseController
{

    /**
     * AccessTokenController constructor.
     * @param ConfigFactory $config
     */
    public function __construct(ConfigFactory $config)
    {
        parent::__construct($config);
    }

    /**
     * @param string $userId
     * @param array $arguments
     * @return mixed
     * @throws ClientException
     * @throws NotInitializedException
     */
    public function getAll(string $userId, array $arguments = [])
    {
        if (!$this->config->isInitialized()) {
            throw new NotInitializedException();
        }

        $queryParams = $this->buildQueryParameters($arguments);

        $uri = "users/{$userId}/tokens{$queryParams}";

        $httpOptions = $this->config->getAuthHeaders();

        try {
            $response = $this->httpClient->get($uri, $httpOptions);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new ClientException($e->getMessage(), $e->getCode());
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            throw new ClientException($e->getMessage(), $e->getCode());
        }

        $body = json_decode($response->getBody());

        return $body->data;
    }

    /**
     * @param string $userId
     * @param string $tokenId
     * @return mixed
     * @throws ClientException
     */
    public function getOne(string $userId, string $tokenId)
    {

        $uri = "users/{$userId}/tokens/{$tokenId}";

        $httpOptions = $this->config->getAuthHeaders();

        try {
            $response = $this->httpClient->get($uri, $httpOptions);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new ClientException($e->getMessage(), $e->getCode());
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            throw new ClientException($e->getMessage(), $e->getCode());
        }

        $body = json_decode($response->getBody());

        return $body->data;
    }

    /**
     * @param string $userId
     * @param string $tokenId
     * @param mixed $data
     * @return mixed
     * @throws ClientException
     * @throws NotInitializedException
     */
    public function update(string $userId, string $tokenId, $data)
    {
        if (!$this->config->isInitialized()) {
            throw new NotInitializedException();
        }

        $uri = "users/{$userId}/tokens/{$tokenId}";

        $bodyParams = array("json" => $data);
        $httpOptions = array_merge($bodyParams, $this->config->getAuthHeaders());

        try {
            $response = $this->httpClient->put($uri, $httpOptions);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new ClientException($e->getMessage(), $e->getCode());
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            throw new ClientException($e->getMessage(), $e->getCode());
        }

        $body = json_decode($response->getBody());

        return $body->data;
    }

    /**
     * @param string $userId
     * @param mixed $data
     * @return mixed
     * @throws ClientException
     * @throws NotInitializedException
     */
    public function create(string $userId, $data)
    {
        if (!$this->config->isInitialized()) {
            throw new NotInitializedException();
        }

        $uri = "users/{$userId}/tokens";

        $bodyParams = array("json" => $data);
        $httpOptions = array_merge($bodyParams, $this->config->getAuthHeaders());

        try {
            $response = $this->httpClient->post($uri, $httpOptions);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new ClientException($e->getMessage(), $e->getCode());
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            throw new ClientException($e->getMessage(), $e->getCode());
        }

        $body = json_decode($response->getBody());

        return $body->data;
    }

    /**
     * @param string $userId
     * @param string $tokenId
     * @return mixed
     * @throws ClientException
     * @throws NotInitializedException
     */
    public function delete(string $userId, string $tokenId)
    {
        if (!$this->config->isInitialized()) {
            throw new NotInitializedException();
        }

        $uri = "users/{$userId}/tokens/{$tokenId}";

        $httpOptions = $this->config->getAuthHeaders();

        try {
            $response = $this->httpClient->delete($uri, $httpOptions);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new ClientException($e->getMessage(), $e->getCode());
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            throw new ClientException($e->getMessage(), $e->getCode());
        }

        $body = json_decode($response->getBody());

        return $body->data;
    }
}
