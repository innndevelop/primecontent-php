<?php

namespace Primecontent\Controllers;

use Primecontent\Exceptions\ClientException;
use Primecontent\Exceptions\NotInitializedException;
use Primecontent\Factories\ConfigFactory;

class UserController extends BaseController
{
    /**
     * UserController constructor.
     * @param ConfigFactory $config
     */
    public function __construct(ConfigFactory $config)
    {
        parent::__construct($config);
    }

    /**
     * @param array $arguments
     * @return mixed
     * @throws ClientException
     * @throws NotInitializedException
     */
    public function getAll(array $arguments = [])
    {
        if (!$this->config->isInitialized()) {
            throw new NotInitializedException();
        }

        $queryParams = $this->buildQueryParameters($arguments);

        $uri = "users{$queryParams}";

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
     * @return mixed
     * @throws ClientException
     * @throws NotInitializedException
     */
    public function getOne(string $userId)
    {
        if (!$this->config->isInitialized()) {
            throw new NotInitializedException();
        }

        $uri = "users/{$userId}";

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
     * @param mixed $data
     * @return mixed
     * @throws ClientException
     * @throws NotInitializedException
     */
    public function update(string $userId, $data)
    {
        if (!$this->config->isInitialized()) {
            throw new NotInitializedException();
        }

        $uri = "users/{$userId}";

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
     * @param mixed $data
     * @return mixed
     * @throws ClientException
     * @throws NotInitializedException
     */
    public function create($data)
    {
        if (!$this->config->isInitialized()) {
            throw new NotInitializedException();
        }

        $uri = "users";

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
     * @return mixed
     * @throws ClientException
     * @throws NotInitializedException
     */
    public function delete(string $userId)
    {
        if (!$this->config->isInitialized()) {
            throw new NotInitializedException();
        }

        $uri = "users/{$userId}";

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
