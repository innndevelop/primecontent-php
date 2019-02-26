<?php

namespace Primecontent\Controllers;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Psr7\Response;
use Primecontent\Factories\ConfigFactory;

class BaseController
{
    protected $config;
    protected $httpClient;

    /**
     * BaseController constructor.
     * @param ConfigFactory $config
     */
    public function __construct(ConfigFactory $config)
    {
        $this->config = $config;

        $this->httpClient = new Guzzle([
            'base_uri' => $this->config->getEndPoint(),
            'timeout' => $this->config->getTimeout(),
            'allow_redirects' => false,
            'verify' => $this->config->isVerifySSL(),
        ]);
    }

    public function checkIsNeededNewToken(Response $response)
    {
        if (ConfigFactory::JWT_MODE === $this->config->getMode() && 401 === $response->getStatusCode()) {
            //retry call
        }
    }

    /**
     * @param array $arguments
     * @return string
     */
    protected function buildQueryParameters(array $arguments)
    {
        $queryParams = http_build_query($arguments, '', '&');

        if ("" !== $queryParams) {
            $queryParams .= "?";
        }

        return $queryParams;
    }
}
