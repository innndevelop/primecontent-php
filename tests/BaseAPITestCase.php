<?php

namespace Primecontent\Tests;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Primecontent\Factories\ConfigFactory;

class BaseAPITestCase extends TestCase
{
    const USER_ID = "test-adm";
    const SPACE_ID = "test";
    const ACCESS_TOKEN_ID = "test";

    protected $mode = ConfigFactory::JWT_MODE;
    protected $token;
    protected $refreshToken;
    protected $accessToken;
    /** @var \Primecontent\Client $primecontent */
    protected $primecontent;
    /** @var \Primecontent\Client $primecontent */
    protected $primecontentSA;
    protected $userId;
    /** @var Client $httpClient */
    protected $httpClient;

    public function setUp()
    {

        try {
            $this->userId = self::USER_ID;

            $this->primecontent = new \Primecontent\Client("jwt", "", "", "test", 15);
            $this->primecontentSA = new \Primecontent\Client("jwt", "", "", "test", 15);

            $response = $this->primecontent->auth->login(self::USER_ID, "test");

            $this->token = $response->token;
            $this->refreshToken = $response->refresh_token;

            $this->primecontent->setup(array(
                "token" => $response->token,
                "space" => self::SPACE_ID
            ));

            $response = $this->primecontentSA->auth->login("test", "test");

            $this->primecontentSA->setup(array(
                "token" => $response->token,
                "space" => self::SPACE_ID
            ));
            //$response = $this->primecontent->token->getOne(self::USER_ID,self::ACCESS_TOKEN_ID);

            //$this->accessToken = $response->token;


        } catch (\Primecontent\Exceptions\InvalidClientConfigArgsException $e) {
            $this->token = "";
            $this->refreshToken = "";
            $this->accessToken = "";
            echo "{$e->getCode()} - {$e->getMessage()}";
        } catch(\Exception $e){
            $this->token = "";
            $this->refreshToken = "";
            $this->accessToken = "";
            echo "{$e->getCode()} - {$e->getMessage()}";
        }
    }

    public function setAPIMode(string $mode = ConfigFactory::JWT_MODE){

        $this->mode = $mode;
    }

    public function getCurrentToken(){

        if(ConfigFactory::JWT_MODE === $this->mode){
            return $this->token;
        }

        if(ConfigFactory::ACCESS_TOKEN_MODE === $this->mode){
            return $this->accessToken;
        }

        return null;
    }

}
