<?php

namespace Primecontent\Controllers;

use Primecontent\Exceptions\ClientException;
use Primecontent\Exceptions\NotInitializedException;
use Primecontent\Factories\ConfigFactory;

class FormController extends BaseController
{
    /**
     * FormController constructor.
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

        $uri = "spaces/{$this->config->getSpaceId()}/forms{$queryParams}";

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
     * @param string $formId
     * @return mixed
     * @throws ClientException
     * @throws NotInitializedException
     */
    public function getOne(string $formId)
    {
        if (!$this->config->isInitialized()) {
            throw new NotInitializedException();
        }

        $uri = "spaces/{$this->config->getSpaceId()}/forms/{$formId}";

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
     * @param string $formId
     * @return mixed
     * @throws ClientException
     * @throws NotInitializedException
     */
    public function getVersions(string $formId)
    {
        if (!$this->config->isInitialized()) {
            throw new NotInitializedException();
        }

        $uri = "spaces/{$this->config->getSpaceId()}/forms/{$formId}/versions";

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
     * @param string $entryId
     * @param string $versionId
     * @return mixed
     * @throws ClientException
     * @throws NotInitializedException
     */
    public function restoreVersion(string $entryId, string $versionId)
    {
        if (!$this->config->isInitialized()) {
            throw new NotInitializedException();
        }

        $uri = "spaces/{$this->config->getSpaceId()}/forms/{$entryId}/versions/{$versionId}";

        $httpOptions = $this->config->getAuthHeaders();

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
     * @param string $formId
     * @param mixed $data
     * @return mixed
     * @throws ClientException
     * @throws NotInitializedException
     */
    public function update(string $formId, $data)
    {
        if (!$this->config->isInitialized()) {
            throw new NotInitializedException();
        }

        $uri = "spaces/{$this->config->getSpaceId()}/forms/{$formId}";

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

        $uri = "spaces/{$this->config->getSpaceId()}/forms";

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
     * @param string $formId
     * @return mixed
     * @throws ClientException
     * @throws NotInitializedException
     */
    public function delete(string $formId)
    {
        if (!$this->config->isInitialized()) {
            throw new NotInitializedException();
        }

        $uri = "spaces/{$this->config->getSpaceId()}/forms/{$formId}";

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

    /**
     * @param string $formId
     * @param array $arguments
     * @return mixed
     * @throws ClientException
     * @throws NotInitializedException
     */
    public function getSubmits(string $formId, array $arguments = [])
    {
        if (!$this->config->isInitialized()) {
            throw new NotInitializedException();
        }

        $queryParams = $this->buildQueryParameters($arguments);

        $uri = "spaces/{$this->config->getSpaceId()}/forms/{$formId}/submits{$queryParams}";

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
     * @param string $formId
     * @param array $arguments
     * @return mixed
     * @throws ClientException
     * @throws NotInitializedException
     */
    public function getOneSubmit(string $formId, string $formSubmitId)
    {
        if (!$this->config->isInitialized()) {
            throw new NotInitializedException();
        }

        $uri = "spaces/{$this->config->getSpaceId()}/forms/{$formId}/submits/{$formSubmitId}";

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
     * @param string $formId
     * @param mixed $data
     * @return mixed
     * @throws ClientException
     * @throws NotInitializedException
     */
    public function createSubmit(string $formId, $data)
    {
        if (!$this->config->isInitialized()) {
            throw new NotInitializedException();
        }

        $uri = "spaces/{$this->config->getSpaceId()}/forms/{$formId}/submits";

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
     * @param string $formId
     * @param string $formSubmitId
     * @return mixed
     * @throws ClientException
     * @throws NotInitializedException
     */
    public function deleteSubmit(string $formId, string $formSubmitId)
    {
        if (!$this->config->isInitialized()) {
            throw new NotInitializedException();
        }

        $uri = "spaces/{$this->config->getSpaceId()}/forms/{$formId}/submits/{$formSubmitId}";

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
