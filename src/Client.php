<?php namespace Primecontent;

/**
 *  Main SDK Class
 *
 *  Use this class to instanciate PrimeContent PHP Client to manage and consume contents.
 *
 * @author Alex Martín|Juan Manuel Castillo
 */

use Primecontent\Controllers\AccessTokenController;
use Primecontent\Controllers\AssetController;
use Primecontent\Controllers\AuthController;
use Primecontent\Controllers\CompoundFieldController;
use Primecontent\Controllers\ConfigController;
use Primecontent\Controllers\ContentTypeController;
use Primecontent\Controllers\EntryController;
use Primecontent\Controllers\FormController;
use Primecontent\Controllers\FormTypeController;
use Primecontent\Controllers\GroupController;
use Primecontent\Controllers\HookController;
use Primecontent\Controllers\OrganizationController;
use Primecontent\Controllers\SpaceController;
use Primecontent\Controllers\TagController;
use Primecontent\Controllers\UserController;
use Primecontent\Exceptions\InvalidConfigException;
use Primecontent\Exceptions\NotFoundPropertyException;
use Primecontent\Exceptions\NotInitializedException;
use Primecontent\Factories\ConfigFactory;

class Client
{
    const CORE_DOMAIN = "https://core.primecontent.io";
    const CDN_DOMAIN = "https://cdn.primecontent.io";
    const ALLOWED_PROPERTIES = array(
        'auth',
        'asset',
        'entry',
        'entryType',
        'form',
        'formType',
        'space',
        'organization',
        'compound',
        'hook',
        'tag',
        'user',
        'config',
        'group',
        'token'
    );

    private $initialized = false;
    private $clientConfig;
    private $config;
    private $auth;
    private $asset;
    private $entry;
    private $entryType;
    private $form;
    private $formType;
    private $hook;
    private $tag;
    private $compound;
    private $space;
    private $token;
    private $organization;
    private $user;
    private $group;

    /**
     * Client constructor.
     * @param string $mode
     * @param string $token
     * @param string $refreshToken
     * @param string $space
     * @param int $requestTimeout
     * @param bool $verifySSL
     * @throws Exceptions\InvalidClientConfigArgsException
     * @throws InvalidConfigException
     */
    public function __construct(string $mode = 'jwt', string $token = "", string $refreshToken = "", string $space = "", int $requestTimeout = ConfigFactory::DEFAULT_TIMEOUT, bool $verifySSL = ConfigFactory::DEFAULT_VERIFY_SSL)
    {
        $args = array(
            "coreDomain" => self::CORE_DOMAIN,
            "cdnDomain" => self::CDN_DOMAIN,
            "mode" => $mode,
            "timeout" => $requestTimeout,
        );

        if ("" !== $token) {
            $args["token"] = $token;
        }

        if ("" !== $space) {
            $args["space"] = $space;
        }

        if ("" !== $refreshToken) {
            $args["refreshToken"] = $refreshToken;
        }

        $args["verifySSL"] = $verifySSL;

        $this->clientConfig = ConfigFactory::build($args);

        $this->auth = new AuthController($this->clientConfig);
        $this->token = new AccessTokenController($this->clientConfig);

        $this->initialized = false;

        if ("" !== $space && "" !== $token && ConfigFactory::JWT_MODE === $mode) {
            $this->setup($args);
        }

        if ("" !== $space && "" !== $token && "" !== $refreshToken && ConfigFactory::ACCESS_TOKEN_MODE === $mode) {
            $this->setup($args);
        }


    }

    /**
     * @param string $property
     * @return mixed
     * @throws NotFoundPropertyException
     */
    public function __get(string $property)
    {
        if (in_array($property, self::ALLOWED_PROPERTIES) && $this->initialized) {
            return $this->$property;
        }

        if (($property === "auth" || $property === "token") && in_array($property, self::ALLOWED_PROPERTIES)) {
            return $this->$property;
        }

        throw new NotFoundPropertyException('Propiedad en primecontent no encontrada.');
    }

    /**
     * @param array $config
     * @return bool
     * @throws Exceptions\InvalidClientConfigArgsException
     * @throws InvalidConfigException
     */
    public function setup(array $config)
    {
        $config["coreDomain"] = self::CORE_DOMAIN;
        $config["cdnDomain"] = self::CDN_DOMAIN;

        if (!array_key_exists("token", $config)
            && !array_key_exists("space", $config)) {
            throw new InvalidConfigException();
        }

        if (!array_key_exists("refreshToken", $config) &&
            ConfigFactory::ACCESS_TOKEN_MODE === $this->clientConfig->getMode()) {
            throw new InvalidConfigException();
        }

        $this->clientConfig->setup($config);

        $this->initialized = true;

        $this->organization = new OrganizationController($this->clientConfig);
        $this->space = new SpaceController($this->clientConfig);
        $this->entry = new EntryController($this->clientConfig);
        $this->entryType = new ContentTypeController($this->clientConfig);
        $this->asset = new AssetController($this->clientConfig);
        $this->form = new FormController($this->clientConfig);
        $this->formType = new FormTypeController($this->clientConfig);
        $this->hook = new HookController($this->clientConfig);
        $this->compound = new CompoundFieldController($this->clientConfig);
        $this->tag = new TagController($this->clientConfig);
        $this->user = new UserController($this->clientConfig);
        $this->config = new ConfigController($this->clientConfig);
        $this->group = new GroupController($this->clientConfig);

        return $this->initialized;
    }

    /**
     * @return bool
     */
    public function isInitialized()
    {
        return $this->initialized;
    }

    /**
     * @return string
     * @throws NotInitializedException
     */
    public function getToken()
    {
        if (false === $this->initialized) {
            throw new NotInitializedException();
        }

        return $this->clientConfig->getToken();
    }
}
