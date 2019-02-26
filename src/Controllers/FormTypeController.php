<?php

namespace Primecontent\Controllers;

use Primecontent\Exceptions\ClientException;
use Primecontent\Exceptions\NotInitializedException;
use Primecontent\Factories\ConfigFactory;

class FormTypeController extends BaseController
{
    /**
     * FormTypeController constructor.
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

        $uri = "spaces/{$this->config->getSpaceId()}/form-types{$queryParams}";

        $httpOptions = $this->config->getAuthHeaders();

        try {
            $response = $this->httpClient->get($uri, $httpOptions);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new ClientException($e->getMessage(), $e->getCode());
        }

        $body = json_decode($response->getBody());

        return $body->data;
    }

    /**
     * @param string $formTypeId
     * @return mixed
     * @throws ClientException
     * @throws NotInitializedException
     */
    public function getOne(string $formTypeId)
    {
        if (!$this->config->isInitialized()) {
            throw new NotInitializedException();
        }

        $uri = "spaces/{$this->config->getSpaceId()}/form-types/{$formTypeId}";

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
     * @param string $formTypeId
     * @param mixed $data
     * @return mixed
     * @throws ClientException
     * @throws NotInitializedException
     */
    public function update(string $formTypeId, $data)
    {
        if (!$this->config->isInitialized()) {
            throw new NotInitializedException();
        }

        $uri = "spaces/{$this->config->getSpaceId()}/form-types/{$formTypeId}";

        $bodyParams = array("json" => $data);
        $httpOptions = array_merge($bodyParams, $this->config->getAuthHeaders());

        try {
            $response = $this->httpClient->put($uri, $httpOptions);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
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

        $uri = "spaces/{$this->config->getSpaceId()}/form-types";

        $bodyParams = array("json" => $data);
        $httpOptions = array_merge($bodyParams, $this->config->getAuthHeaders());

        try {
            $response = $this->httpClient->post($uri, $httpOptions);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new ClientException($e->getMessage(), $e->getCode());
        }

        $body = json_decode($response->getBody());

        return $body->data;
    }

    /**
     * @param string $formTypeId
     * @return mixed
     * @throws ClientException
     * @throws NotInitializedException
     */
    public function delete(string $formTypeId)
    {
        if (!$this->config->isInitialized()) {
            throw new NotInitializedException();
        }

        $uri = "spaces/{$this->config->getSpaceId()}/form-types/{$formTypeId}";

        $httpOptions = $this->config->getAuthHeaders();

        try {
            $response = $this->httpClient->delete($uri, $httpOptions);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new ClientException($e->getMessage(), $e->getCode());
        }

        $body = json_decode($response->getBody());

        return $body->data;
    }
}
