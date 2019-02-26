<?php

namespace Primecontent\Controllers;

use Primecontent\Exceptions\ClientException;
use Primecontent\Factories\ConfigFactory;

class AuthController extends BaseController
{
    /**
     * AuthController constructor.
     * @param ConfigFactory $config
     */
    public function __construct(ConfigFactory $config)
    {
        parent::__construct($config);
    }

    /**
     * @param string $username
     * @param string $password
     * @return \Psr\Http\Message\StreamInterface
     * @throws ClientException
     */
    public function login(string $username, string $password)
    {
        $data = array(
            'username' => $username,
            'password' => $password
        );

        $uri = "login";

        $bodyParams = array("json" => $data);

        try {
            $response = $this->httpClient->post($uri, $bodyParams);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new ClientException($e->getMessage(), $e->getCode());
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            throw new ClientException($e->getMessage(), $e->getCode());
        }

        $body = json_decode($response->getBody());

        $this->config->setToken($body->token);
        $this->config->setRefreshToken($body->refresh_token);

        return $body;
    }

    /**
     * @param string $token
     * @return \Psr\Http\Message\StreamInterface
     * @throws ClientException
     */
    public function refresh(string $token)
    {
        $data = array(
            'refresh_token' => $token
        );

        $uri = "/jwt/refresh";

        $bodyParams = array("json" => $data);

        try {
            $response = $this->httpClient->post($uri, $bodyParams);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new ClientException($e->getMessage(), $e->getCode());
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            throw new ClientException($e->getMessage(), $e->getCode());
        }

        $body = json_decode($response->getBody());

        $this->config->setToken($body->token);

        return $response->getBody();
    }
}
