<?php

namespace Primecontent\Factories;

use Primecontent\Exceptions\InvalidClientConfigArgsException;

class ConfigFactory
{
    const JWT_MODE = "jwt";
    const ACCESS_TOKEN_MODE = "access_token";
    const ALLOWED_MODES = array(
        self::JWT_MODE,
        self::ACCESS_TOKEN_MODE
    );
    const DEFAULT_TIMEOUT = 15;
    const DEFAULT_VERIFY_SSL = true;

    private static $instance = null;

    /** @var bool $initialized */
    protected $initialized = false;
    /** @var array $coreEndPoint */
    protected $endPoints;
    /** @var string $spaceId */
    protected $spaceId;
    /** @var string $mode */
    protected $mode;
    /** @var string $token */
    protected $token;
    /** @var string $refreshToken */
    protected $refreshToken;
    /** @var int $timeout */
    protected $timeout;
    /** @var bool $verifySSL */
    protected $verifySSL;

    /**
     * ConfigFactory constructor (private).
     * @param array|null $args
     * @throws InvalidClientConfigArgsException
     */

    private function __construct(array $args = null)
    {
        $this->validateArgs($args);

        $this->endPoints = array(
            self::JWT_MODE => $args["coreDomain"],
            self::ACCESS_TOKEN_MODE => $args["cdnDomain"]
        );

        $this->mode = $args["mode"];

        if (array_key_exists("token", $args)
            && array_key_exists("space", $args)) {
            $this->setup($args["space"], $args["token"]);
        }

        if (array_key_exists("refreshToken", $args)) {
            $this->setRefreshToken($args["refreshToken"]);
        }

        if (array_key_exists("timeout", $args)) {
            $this->timeout = $args["timeout"];
        }

        $this->verifySSL = false;
        if (array_key_exists("verifySSL", $args)) {
            $this->verifySSL = $args["verifySSL"];
        }
    }

    private function __clone()
    {
    }

    private function __sleep()
    {
    }

    /**
     * @param null|array $args
     * @return null|ConfigFactory
     * @throws InvalidClientConfigArgsException
     */
    public static function build(array $args = null)
    {
        return new ConfigFactory($args);
    }

    /**
     * @param mixed $args
     * @return bool
     * @throws InvalidClientConfigArgsException
     */
    private function validateArgs($args)
    {
        if (null === $args) {
            throw new InvalidClientConfigArgsException("Missing arguments.");
        }

        if (!array_key_exists("coreDomain", $args)) {
            throw new InvalidClientConfigArgsException("Missing coreDomain parameter.");
        }

        if (!array_key_exists("cdnDomain", $args)) {
            throw new InvalidClientConfigArgsException("Missing cdnDomain parameter.");
        }

        if (!array_key_exists("mode", $args)) {
            throw new InvalidClientConfigArgsException("Missing mode parameter.");
        }

        if (!in_array($args["mode"], self::ALLOWED_MODES)) {
            throw new InvalidClientConfigArgsException("Not supported auth mode (support list: " . join(", ", self::ALLOWED_MODES) . ").");
        }
        /*
        if(!array_key_exists("token",$args)){
            throw new InvalidClientConfigArgsException("Missing token parameter.");
        }

        if(!array_key_exists("space",$args)){
            throw new InvalidClientConfigArgsException("Missing space parameter.");
        }*/

        return true;
    }

    /**
     * @param array|null $args
     * @throws InvalidClientConfigArgsException
     */
    public function setup(array $args =  null)
    {
        if (!array_key_exists("mode", $args)) {
            $args["mode"] = self::JWT_MODE;
        }

        if (!array_key_exists("verifySSL", $args)) {
            $args["verifySSL"] = self::DEFAULT_VERIFY_SSL;
        }

        if (!array_key_exists("timeout", $args)) {
            $args["timeout"] = self::DEFAULT_TIMEOUT;
        }

        $this->validateArgs($args);

        $this->setMode($args["mode"]);
        $this->verifySSL = $args["verifySSL"];
        $this->timeout = $args["timeout"];
        $this->endPoints = array(
            self::JWT_MODE => $args["coreDomain"],
            self::ACCESS_TOKEN_MODE => $args["cdnDomain"]
        );
        $this->setSpaceId($args["space"]);
        $this->setToken($args["token"]);
        $this->setRefreshToken($args["refreshToken"]);

        $this->initialized = true;
    }

    /**
     * @return string
     */
    public function getSpaceId()
    {
        return $this->spaceId;
    }

    /**
     * @param string $spaceId
     * @return ConfigFactory
     */
    public function setSpaceId(string $spaceId)
    {
        $this->spaceId = $spaceId;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return ConfigFactory
     */
    public function setToken(string $token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @param string $refreshToken
     * @return ConfigFactory
     */
    public function setRefreshToken(string $refreshToken)
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * @return mixed|null
     */
    public function getEndPoint()
    {
        if (key_exists($this->mode, $this->endPoints)) {
            return $this->endPoints[$this->mode];
        }

        return null;
    }

    /**
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @return array
     */
    public function getAuthHeaders()
    {
        if (self::JWT_MODE === $this->mode) {
            return array(
                'headers' => array(
                    'Authorization' => 'Bearer ' . $this->token
                ));
        }

        if (self::ACCESS_TOKEN_MODE === $this->mode) {
            return array(
                'headers' => array(
                    'Authorization' => 'Bearer ' . $this->token
                ));
        }

        return [];
    }

    /**
     * @return bool
     */
    public function isVerifySSL()
    {
        return $this->verifySSL;
    }

    /**
     * @return bool
     */
    public function isInitialized()
    {
        return $this->initialized;
    }

    /**
     * @param string $mode
     */
    public function setMode(string $mode){
        $this->mode = $mode;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }
}
